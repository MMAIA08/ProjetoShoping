<?php
session_start();
require_once __DIR__ . '/../app/conexao.php';

// Verifica se o usuário está logado e é um lojista
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo'] !== 'lojista') {
    header("Location: ../login.php");
    exit();
}

$usuario = $_SESSION['usuario'];

// Verifica se o ID do produto foi enviado via GET
if (!isset($_GET['id']) || empty($_GET['id'])) {
    $_SESSION['erro'] = "Produto não encontrado.";
    header("Location: listar_produtos.php");
    exit();
}

$produto_id = intval($_GET['id']);

// Busca o produto para edição, garantindo que pertence ao lojista logado
$sql = "SELECT * FROM produtos WHERE id = ? AND loja_id = (SELECT id FROM lojas WHERE usuario_id = ?)";
$stmt = $pdo->prepare($sql);
$stmt->execute([$produto_id, $usuario['id']]);
$produto = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$produto) {
    $_SESSION['erro'] = "Produto não encontrado ou você não tem permissão para editá-lo.";
    header("Location: listar_produtos.php");
    exit();
}

// Busca as categorias da loja do lojista
$sqlCategorias = "SELECT id, nome FROM categorias WHERE loja_id = (SELECT id FROM lojas WHERE usuario_id = ?)";
$stmtCategorias = $pdo->prepare($sqlCategorias);
$stmtCategorias->execute([$usuario['id']]);
$categorias = $stmtCategorias->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Produto</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <h2>Editar Produto</h2>
        
        <?php if (isset($_SESSION['erro'])): ?>
            <div class="alert alert-danger"><?php echo $_SESSION['erro']; unset($_SESSION['erro']); ?></div>
        <?php endif; ?>

        <form action="processa_editar_produto.php" method="POST" enctype="multipart/form-data">
            <input type="hidden" name="produto_id" value="<?php echo $produto['id']; ?>">

            <div class="mb-3">
                <label class="form-label">Nome do Produto</label>
                <input type="text" class="form-control" name="nome" value="<?php echo htmlspecialchars($produto['nome']); ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Descrição</label>
                <textarea class="form-control" name="descricao"><?php echo htmlspecialchars($produto['descricao']); ?></textarea>
            </div>

            <div class="mb-3">
                <label class="form-label">Preço</label>
                <input type="number" step="0.01" class="form-control" name="preco" value="<?php echo $produto['preco']; ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Estoque</label>
                <input type="number" class="form-control" name="estoque" value="<?php echo $produto['estoque']; ?>" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Categoria</label>
                <select class="form-control" name="categoria_id">
                    <option value="">Selecione uma categoria</option>
                    <?php foreach ($categorias as $categoria): ?>
                        <option value="<?php echo $categoria['id']; ?>" <?php echo ($produto['categoria_id'] == $categoria['id']) ? 'selected' : ''; ?>>
                            <?php echo htmlspecialchars($categoria['nome']); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Imagem Atual</label><br>
                <?php if (!empty($produto['imagem'])): ?>
                    <img src="../<?php echo $produto['imagem']; ?>" width="100"><br>
                <?php endif; ?>
                <label class="form-label">Nova Imagem (opcional)</label>
                <input type="file" class="form-control" name="imagem">
            </div>

            <button type="submit" class="btn btn-primary">Salvar Alterações</button>
            <a href="listar_produtos.php" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</body>
</html>
