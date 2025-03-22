<?php
session_start();
require_once __DIR__ . '/../app/conexao.php';

// Verifica se o usuário está logado e é lojista
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo'] !== 'lojista') {
    header("Location: ../login.php");
    exit();
}

// Verifica se foi passado um ID válido
if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    $_SESSION['erro'] = "ID de categoria inválido.";
    header("Location: cadastrar_categoria.php");
    exit();
}

$id_categoria = $_GET['id'];

// Exclui a categoria do banco de dados
try {
    $stmt = $pdo->prepare("DELETE FROM categorias WHERE id = ?");
    $stmt->execute([$id_categoria]);

    $_SESSION['sucesso'] = "Categoria apagada com sucesso!";
} catch (PDOException $e) {
    $_SESSION['erro'] = "Erro ao excluir categoria: " . $e->getMessage();
}

header("Location: cadastrar_categoria.php");
exit();
