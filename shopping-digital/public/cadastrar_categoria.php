<?php
session_start();
require_once __DIR__ . '/../app/conexao.php';

// Verifica se o usuário está logado e é lojista
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo'] !== 'lojista') {
    header("Location: ../login.php");
    exit();
}

$usuario = $_SESSION['usuario'];

// Busca a loja do lojista logado
$sql = "SELECT id FROM lojas WHERE usuario_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$usuario['id']]);
$loja = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$loja) {
    $_SESSION['erro'] = "Você precisa cadastrar sua loja antes de criar categorias!";
    header("Location: cadastrar_loja.php");
    exit();
}

$loja_id = $loja['id'];

// Busca todas as categorias cadastradas para a loja do lojista
$sql = "SELECT id, nome FROM categorias WHERE loja_id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$loja_id]);
$categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Categoria</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
            font-family: 'Roboto', sans-serif;
        }
        .container {
            max-width: 600px;
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.1);
            margin-top: 50px;
        }
        .form-label {
            font-weight: bold;
        }
        .btn-success {
            width: 100%;
        }
        .btn-danger {
            padding: 5px 10px;
        }
        .loading-spinner {
            display: none;
            margin-left: 10px;
        }
    </style>
</head>
<body>
<div class="container">
    <h2 class="text-center text-primary">Cadastrar Categoria</h2>

    <?php if (isset($_SESSION['erro'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['erro']; unset($_SESSION['erro']); ?></div>
    <?php endif; ?>

    <?php if (isset($_SESSION['sucesso'])): ?>
        <div class="alert alert-success"><?= $_SESSION['sucesso']; unset($_SESSION['sucesso']); ?></div>
    <?php endif; ?>

    <!-- Formulário de Cadastro -->
    <form action="processa_cadastro_categoria.php" method="POST" onsubmit="return validarFormulario()">
        <div class="mb-3">
            <label for="nome_categoria" class="form-label">Nome da Categoria</label>
            <input type="text" class="form-control" id="nome_categoria" name="nome_categoria" required>
            <div class="invalid-feedback">Por favor, insira um nome válido.</div>
        </div>
        <button type="submit" class="btn btn-success" id="btnCadastrar">
            <i class="fas fa-save"></i> Cadastrar Categoria
            <span class="spinner-border spinner-border-sm loading-spinner"></span>
        </button>
    </form>

    <!-- Listagem das Categorias -->
    <h3 class="mt-4 text-secondary">Categorias Cadastradas</h3>
    <?php if (count($categorias) > 0): ?>
        <ul class="list-group mt-3">
            <?php foreach ($categorias as $categoria): ?>
                <li class="list-group-item d-flex justify-content-between align-items-center">
                    <?= htmlspecialchars($categoria['nome']) ?>
                    <button class="btn btn-danger btn-sm" onclick="confirmarExclusao(<?= $categoria['id'] ?>)">
                        <i class="fas fa-trash-alt"></i> Apagar
                    </button>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p class="text-muted mt-2">Nenhuma categoria cadastrada.</p>
    <?php endif; ?>

    <a href="cadastrar_produto.php" class="btn btn-secondary mt-3 w-100">Voltar</a>
</div>

<script>
    function validarFormulario() {
        let nomeCategoria = document.getElementById("nome_categoria").value.trim();
        let botao = document.getElementById("btnCadastrar");
        let spinner = document.querySelector(".loading-spinner");

        if (nomeCategoria.length < 2) {
            document.getElementById("nome_categoria").classList.add("is-invalid");
            return false;
        } else {
            document.getElementById("nome_categoria").classList.remove("is-invalid");
        }

        botao.disabled = true;
        spinner.style.display = "inline-block";

        return true;
    }

    function confirmarExclusao(id) {
        if (confirm("Tem certeza que deseja apagar esta categoria?")) {
            window.location.href = "processa_excluir_categoria.php?id=" + id;
        }
    }
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
