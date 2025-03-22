<?php
session_start();
require_once __DIR__ . '/../app/conexao.php'; // Conexão com o banco de dados

// Verifica se o usuário está logado e é um lojista
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo'] !== 'lojista') {
    header("Location: ../login.php");
    exit();
}

$usuario = $_SESSION['usuario'];

// Verifica se o formulário foi enviado via POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    $_SESSION['erro'] = "Acesso inválido.";
    header("Location: listar_produtos.php");
    exit();
}

// Captura e valida os dados do formulário
$produto_id = intval($_POST['produto_id']);
$nome = trim($_POST['nome']);
$descricao = trim($_POST['descricao']);
$preco = str_replace(',', '.', $_POST['preco']); // Converte valores do formato brasileiro para decimal
$estoque = intval($_POST['estoque']);
$categoria_id = !empty($_POST['categoria_id']) ? intval($_POST['categoria_id']) : null;
$imagem = null;

// Verifica se o produto pertence ao lojista logado antes de editar
$sql_verifica = "SELECT p.*, l.usuario_id FROM produtos p 
                 JOIN lojas l ON p.loja_id = l.id 
                 WHERE p.id = ? AND l.usuario_id = ?";
$stmt_verifica = $pdo->prepare($sql_verifica);
$stmt_verifica->execute([$produto_id, $usuario['id']]);
$produto = $stmt_verifica->fetch(PDO::FETCH_ASSOC);

if (!$produto) {
    $_SESSION['erro'] = "Produto não encontrado ou sem permissão para editar.";
    header("Location: listar_produtos.php");
    exit();
}

// Processamento da imagem, se houver upload
if (!empty($_FILES['imagem']['name'])) {
    $target_dir = __DIR__ . "/../public/uploads/";
    
    // Garante que o diretório de uploads existe
    if (!is_dir($target_dir)) {
        mkdir($target_dir, 0777, true);
    }

    $imagem_nome = time() . "_" . basename($_FILES["imagem"]["name"]); // Nome único
    $target_file = $target_dir . $imagem_nome;

    // Move a imagem para a pasta de uploads
    if (move_uploaded_file($_FILES["imagem"]["tmp_name"], $target_file)) {
        // Se o produto já tinha uma imagem, excluí-la do servidor
        if (!empty($produto['imagem']) && file_exists(__DIR__ . "/../public/uploads/" . $produto['imagem'])) {
            unlink(__DIR__ . "/../public/uploads/" . $produto['imagem']);
        }

        $imagem = "public/uploads/" . $imagem_nome;
    } else {
        $_SESSION['erro'] = "Erro ao fazer upload da imagem.";
        header("Location: editar_produto.php?id=" . $produto_id);
        exit();
    }
}

// Atualiza o produto no banco de dados
try {
    if ($imagem) {
        $sql_update = "UPDATE produtos SET nome = ?, descricao = ?, preco = ?, estoque = ?, categoria_id = ?, imagem = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql_update);
        $stmt->execute([$nome, $descricao, $preco, $estoque, $categoria_id, $imagem, $produto_id]);
    } else {
        $sql_update = "UPDATE produtos SET nome = ?, descricao = ?, preco = ?, estoque = ?, categoria_id = ? WHERE id = ?";
        $stmt = $pdo->prepare($sql_update);
        $stmt->execute([$nome, $descricao, $preco, $estoque, $categoria_id, $produto_id]);
    }

    $_SESSION['sucesso'] = "Produto atualizado com sucesso!";
    header("Location: listar_produtos.php");
    exit();
} catch (PDOException $e) {
    $_SESSION['erro'] = "Erro ao atualizar o produto: " . $e->getMessage();
    header("Location: editar_produto.php?id=" . $produto_id);
    exit();
}
?>
