<?php
session_start();
require_once __DIR__ . '/../app/conexao.php';

$email = trim($_POST['email']);
$senha = trim($_POST['senha']);

$sql = "SELECT * FROM usuarios WHERE email = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$email]);
$usuario = $stmt->fetch(PDO::FETCH_ASSOC);

if ($usuario && password_verify($senha, $usuario['senha'])) {
    // Se for um lojista, buscamos o ID da loja
    if ($usuario['tipo'] === 'lojista') {
        $sql_loja = "SELECT id FROM lojas WHERE usuario_id = ?";
        $stmt_loja = $pdo->prepare($sql_loja);
        $stmt_loja->execute([$usuario['id']]);
        $loja = $stmt_loja->fetch(PDO::FETCH_ASSOC);

        if ($loja) {
            $usuario['loja_id'] = $loja['id']; // Adiciona o ID da loja ao usuário
        }
    }

    $_SESSION['usuario'] = $usuario; // Armazena o usuário na sessão

    // Redirecionamento com base no tipo de usuário
    switch ($usuario['tipo']) {
        case 'lojista':
            header("Location: painel_loja.php");
            break;
        case 'cliente':
            header("Location: painel_cliente.php"); // Página inicial da loja
            break;
        case 'entregador':
            header("Location: painel_entregador.php"); // Painel do entregador
            break;
        case 'admin':
            header("Location: painel_admin.php"); // Painel do administrador
            break;
        default:
            header("Location: index.php"); // Página principal
            break;
    }

    exit();
} else {
    $_SESSION['erro'] = "Email ou senha incorretos.";
    header("Location: login.php");
    exit();
}

?>
