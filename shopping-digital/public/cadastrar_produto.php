<?php
session_start();
require_once __DIR__ . '/../app/conexao.php';

// Verifica se o usuário está logado e é lojista
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo'] !== 'lojista') {
    header("Location: ../login.php");
    exit();
}

// Busca categorias disponíveis no banco de dados
try {
    $stmt = $pdo->query("SELECT id, nome FROM categorias");
    $categorias = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    die("Erro ao buscar categorias: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastrar Produto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
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
        .btn-primary {
            background-color: #003366;
            border: none;
        }
        .btn-primary:hover {
            background-color: #0055b3;
        }
        .preview-img {
            width: 100%;
            max-height: 200px;
            object-fit: cover;
            border-radius: 5px;
            margin-top: 10px;
            display: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2 class="text-center text-primary">Cadastrar Novo Produto</h2>

        <?php if (isset($_SESSION["erro"])): ?>
            <div class="alert alert-danger"><?= $_SESSION["erro"] ?></div>
            <?php unset($_SESSION["erro"]); ?>
        <?php endif; ?>
        <?php if (isset($_SESSION["sucesso"])): ?>
            <div class="alert alert-success"><?= $_SESSION["sucesso"] ?></div>
            <?php unset($_SESSION["sucesso"]); ?>
        <?php endif; ?>

        <div class="d-flex justify-content-between mb-3">
            <span class="text-muted">Preencha os detalhes do produto</span>
            <a href="cadastrar_categoria.php" class="btn btn-outline-primary btn-sm">
                <i class="fas fa-plus"></i> Criar Categoria
            </a>
        </div>

        <form action="processa_cadastro_produto.php" method="POST" enctype="multipart/form-data">
            <div class="mb-3">
                <label class="form-label">Nome do Produto</label>
                <input type="text" class="form-control" name="nome" required>
            </div>
            
            <div class="mb-3">
                <label class="form-label">Descrição</label>
                <textarea class="form-control" name="descricao" rows="3" required></textarea>
            </div>
            
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Preço</label>
                    <input type="number" step="0.01" class="form-control" name="preco" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Estoque</label>
                    <input type="number" class="form-control" name="estoque" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Categoria</label>
                <select class="form-control" name="categoria" required>
                    <option value="">Selecione uma categoria</option>
                    <?php foreach ($categorias as $categoria): ?>
                        <option value="<?= $categoria['id'] ?>"><?= htmlspecialchars($categoria['nome']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Imagem do Produto</label>
                <input type="file" class="form-control" name="imagem" accept="image/*" id="imagemInput">
                <img id="preview" class="preview-img">
            </div>

            <div class="d-flex justify-content-between">
                <a href="painel_loja.php" class="btn btn-secondary">Voltar</a>
                <button type="submit" class="btn btn-success">Cadastrar</button>
            </div>
        </form>
    </div>

    <script>
        document.getElementById("imagemInput").addEventListener("change", function(event) {
            const file = event.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const preview = document.getElementById("preview");
                    preview.src = e.target.result;
                    preview.style.display = "block";
                };
                reader.readAsDataURL(file);
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
