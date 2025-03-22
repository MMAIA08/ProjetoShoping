<?php
require_once '../app/conexao.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = trim($_POST['nome']);
    $email = trim($_POST['email']);
    $cpf = trim($_POST['cpf']);
    $telefone = trim($_POST['telefone']);
    $endereco = trim($_POST['endereco']);
    $senha = password_hash($_POST['senha'], PASSWORD_BCRYPT);
    $tipo = $_POST['tipo'];
    $veiculo = isset($_POST['veiculo']) ? trim($_POST['veiculo']) : null;

    // Verifica se o e-mail ou CPF já existem
    $sql_verifica = "SELECT id FROM usuarios WHERE email = ? OR cpf = ?";
    $stmt = $pdo->prepare($sql_verifica);
    $stmt->execute([$email, $cpf]);

    if ($stmt->rowCount() > 0) {
        echo "<script>alert('E-mail ou CPF já cadastrado!'); window.location='cadastro.php';</script>";
        exit();
    }

    // Insere no banco de dados
    $sql = "INSERT INTO usuarios (nome, email, senha, tipo, cpf, telefone, endereco_padrao, veiculo) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    
    if ($stmt->execute([$nome, $email, $senha, $tipo, $cpf, $telefone, $endereco, $veiculo])) {
        echo "<script>alert('Cadastro realizado com sucesso!'); window.location='login.php';</script>";
    } else {
        echo "<script>alert('Erro ao cadastrar!');</script>";
    }
}
?>
