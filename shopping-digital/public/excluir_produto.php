<?php
session_start();
require_once __DIR__ . '/../app/conexao.php';

// Verifica se o usuário está logado e é lojista
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo'] !== 'lojista') {
    header("Location: ../login.php");
    exit();
}

$usuario = $_SESSION['usuario'];

// Verifica se o ID do produto foi enviado
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['erro'] = "Produto não encontrado.";
    header("Location: listar_produtos.php");
    exit();
}

$produto_id = intval($_GET['id']);

// Verifica se o produto pertence ao lojista logado antes de excluir
$sql = "SELECT p.id, p.imagem FROM produtos p 
        JOIN lojas l ON p.loja_id = l.id 
        WHERE p.id = ? AND l.usuario_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$produto_id, $usuario['id']]);
$produto = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$produto) {
    $_SESSION['erro'] = "Produto não encontrado ou você não tem permissão para excluí-lo.";
    header("Location: listar_produtos.php");
    exit();
}

try {
    // Exclui a imagem do produto, se existir
    if (!empty($produto['imagem']) && file_exists(__DIR__ . '/../' . $produto['imagem'])) {
        unlink(__DIR__ . '/../' . $produto['imagem']);
    }

    // Exclui o produto do banco de dados
    $sql = "DELETE FROM produtos WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$produto_id]);

    $_SESSION['sucesso'] = "Produto excluído com sucesso!";
    header("Location: listar_produtos.php");
    exit();
} catch (PDOException $e) {
    $_SESSION['erro'] = "Erro ao excluir o produto: " . $e->getMessage();
    header("Location: listar_produtos.php");
    exit();
}
?>
