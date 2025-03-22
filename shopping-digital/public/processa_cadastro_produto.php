<?php
session_start();
require_once __DIR__ . '/../app/conexao.php';

// Verifica se o usuário está logado e é lojista
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo'] !== 'lojista') {
    header("Location: ../login.php");
    exit();
}

$usuario = $_SESSION['usuario'];

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = trim($_POST["nome"]);
    $descricao = trim($_POST["descricao"]);
    $preco = floatval($_POST["preco"]);
    $estoque = intval($_POST["estoque"]);
    $categoria_id = isset($_POST["categoria"]) ? intval($_POST["categoria"]) : null;
    $imagem = null;

    // Busca a loja do lojista logado
    try {
        $stmt = $pdo->prepare("SELECT id FROM lojas WHERE usuario_id = ?");
        $stmt->execute([$usuario["id"]]);
        $loja = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$loja) {
            $_SESSION["erro"] = "Erro: Loja não encontrada para este lojista.";
            header("Location: cadastrar_produto.php");
            exit();
        }
        $loja_id = $loja["id"];
    } catch (PDOException $e) {
        $_SESSION["erro"] = "Erro ao buscar a loja: " . $e->getMessage();
        header("Location: cadastrar_produto.php");
        exit();
    }

    // Processamento da imagem (se enviada)
    if (!empty($_FILES["imagem"]["name"])) {
        $diretorio_destino = "../uploads/produtos/";
        if (!is_dir($diretorio_destino)) {
            mkdir($diretorio_destino, 0777, true);
        }

        $nome_arquivo = uniqid() . "_" . basename($_FILES["imagem"]["name"]);
        $caminho_arquivo = $diretorio_destino . $nome_arquivo;

        if (move_uploaded_file($_FILES["imagem"]["tmp_name"], $caminho_arquivo)) {
            $imagem = "uploads/produtos/" . $nome_arquivo;
        } else {
            $_SESSION["erro"] = "Erro ao fazer upload da imagem.";
            header("Location: cadastrar_produto.php");
            exit();
        }
    }

    // Inserção no banco de dados
    try {
        $sql = "INSERT INTO produtos (loja_id, categoria_id, nome, descricao, preco, estoque, imagem) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$loja_id, $categoria_id, $nome, $descricao, $preco, $estoque, $imagem]);

        $_SESSION["sucesso"] = "Produto cadastrado com sucesso!";
        header("Location: listar_produtos.php");
        exit();
    } catch (PDOException $e) {
        $_SESSION["erro"] = "Erro ao cadastrar o produto: " . $e->getMessage();
        header("Location: cadastrar_produto.php");
        exit();
    }
} else {
    header("Location: cadastrar_produto.php");
    exit();
}
?>
