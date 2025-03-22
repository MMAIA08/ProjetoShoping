<?php
session_start();
require_once __DIR__ . '/../app/conexao.php';

// Verifica se o usuário está logado e é lojista
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo'] !== 'lojista') {
    header("Location: ../login.php");
    exit();
}

$usuario = $_SESSION['usuario'];
$loja_id = isset($_SESSION['usuario']['loja_id']) ? $_SESSION['usuario']['loja_id'] : null;

if (!$loja_id) {
    die("Erro: ID da loja não encontrado na sessão.");
}

// Obtendo categorias da loja para o filtro
$sql_categorias = "SELECT id, nome FROM categorias WHERE loja_id = ?";
$stmt_categorias = $pdo->prepare($sql_categorias);
$stmt_categorias->execute([$loja_id]);
$categorias = $stmt_categorias->fetchAll(PDO::FETCH_ASSOC);

// Obtendo produtos filtrados
$termo_pesquisa = isset($_GET['search']) ? trim($_GET['search']) : '';
$categoria_id = isset($_GET['categoria_id']) ? intval($_GET['categoria_id']) : 0;

// Query para buscar os produtos
$sql = "SELECT p.*, c.nome AS categoria_nome FROM produtos p 
        LEFT JOIN categorias c ON p.categoria_id = c.id 
        WHERE p.loja_id = ?";

// Adicionando filtros dinâmicos
$params = [$loja_id];

if (!empty($termo_pesquisa)) {
    $sql .= " AND p.nome LIKE ?";
    $params[] = "%$termo_pesquisa%";
}

if ($categoria_id > 0) {
    $sql .= " AND p.categoria_id = ?";
    $params[] = $categoria_id;
}

$sql .= " ORDER BY p.nome ASC"; // Ordenação alfabética

$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$produtos = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listar Produtos</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<div class="container mt-4">
    <h2>Gerenciar Produtos</h2>

    <!-- Formulário de Pesquisa e Filtro -->
    <form method="GET" action="listar_produtos.php" class="mb-3 d-flex">
        <input type="text" name="search" class="form-control me-2" placeholder="Buscar produto..." value="<?= htmlspecialchars($termo_pesquisa); ?>">
        <select name="categoria_id" class="form-select me-2">
            <option value="0">Todas as Categorias</option>
            <?php foreach ($categorias as $categoria): ?>
                <option value="<?= $categoria['id']; ?>" <?= ($categoria_id == $categoria['id']) ? 'selected' : ''; ?>>
                    <?= htmlspecialchars($categoria['nome']); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <button type="submit" class="btn btn-primary">Buscar</button>
    </form>

    <!-- Tabela de Produtos -->
    <table class="table table-striped">
        <thead>
            <tr>
                <th>Imagem</th>
                <th>Nome</th>
                <th>Categoria</th>
                <th>Preço</th>
                <th>Estoque</th>
                <th>Ações</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($produtos)): ?>
                <tr><td colspan="6" class="text-center">Nenhum produto encontrado.</td></tr>
            <?php else: ?>
                <?php foreach ($produtos as $produto): ?>
                    <tr>
                        <td>
                            <?php if (!empty($produto['imagem'])): ?>
                                <img src="../<?= htmlspecialchars($produto['imagem']); ?>" alt="Imagem do Produto" width="50">
                            <?php else: ?>
                                <span>Sem imagem</span>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($produto['nome']); ?></td>
                        <td><?= htmlspecialchars($produto['categoria_nome'] ?? 'Sem categoria'); ?></td>
                        <td>R$ <?= number_format($produto['preco'], 2, ',', '.'); ?></td>
                        <td><?= $produto['estoque']; ?></td>
                        <td>
                            <a href="editar_produto.php?id=<?= $produto['id']; ?>" class="btn btn-warning btn-sm">Editar</a>
                            <a href="excluir_produto.php?id=<?= $produto['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Tem certeza que deseja excluir este produto?')">Excluir</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>

    <a href="cadastrar_produto.php" class="btn btn-success">Adicionar Produto</a>
    <a href="painel_loja.php" class="btn btn-secondary">Voltar</a>
</div>

</body>
</html>
