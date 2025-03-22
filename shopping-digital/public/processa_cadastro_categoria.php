<?php
session_start();
require_once __DIR__ . '/../app/conexao.php';

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo'] !== 'lojista') {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome_categoria = trim($_POST["nome_categoria"]);

    if (empty($nome_categoria)) {
        $_SESSION['erro'] = "O nome da categoria não pode estar vazio.";
        header("Location: cadastrar_categoria.php");
        exit();
    }

    // Buscar a loja do lojista logado
    $usuario_id = $_SESSION['usuario']['id'];
    $sql = "SELECT id FROM lojas WHERE usuario_id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$usuario_id]);
    $loja = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$loja) {
        $_SESSION['erro'] = "Erro: Loja não encontrada!";
        header("Location: painel_loja.php");
        exit();
    }

    $loja_id = $loja['id'];

    // Insere a categoria no banco
    try {
        $sql = "INSERT INTO categorias (nome, loja_id) VALUES (?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$nome_categoria, $loja_id]);

        $_SESSION['sucesso'] = "Categoria cadastrada com sucesso!";
header("Location: cadastrar_produto.php"); // Redireciona para a tela de cadastro de produto
exit();

    } catch (PDOException $e) {
        $_SESSION["erro"] = "Erro ao cadastrar a categoria: " . $e->getMessage();
        header("Location: cadastrar_categoria.php");
        exit();
    }
} else {
    header("Location: cadastrar_categoria.php");
    exit();
}
