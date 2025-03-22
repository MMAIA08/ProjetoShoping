<?php
session_start();
require_once __DIR__ . "/../app/conexao.php";

// Verifica se o usuário está logado como cliente
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo'] !== 'cliente') {
    header("Location: ../login.php");
    exit();
}

// Filtros de pesquisa
$busca = isset($_GET['busca']) ? trim($_GET['busca']) : '';
$categoria = isset($_GET['categoria']) ? trim($_GET['categoria']) : '';

// Monta a query com filtros
$sql = "SELECT * FROM loja WHERE status = 'ativo'";

// Aplica filtro de busca por nome
if (!empty($busca)) {
    $sql .= " AND nome LIKE :busca";
}

// Aplica filtro por categoria, se selecionada
if (!empty($categoria)) {
    $sql .= " AND categoria = :categoria";
}

// Prioriza lojas patrocinadas e ordena por nome
$sql .= " ORDER BY patrocinado DESC, nome ASC";

$stmt = $pdo->prepare($sql);

// Bind dos parâmetros
if (!empty($busca)) {
    $stmt->bindValue(':busca', "%$busca%", PDO::PARAM_STR);
}
if (!empty($categoria)) {
    $stmt->bindValue(':categoria', $categoria, PDO::PARAM_STR);
}

$stmt->execute();
$lojas = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Busca categorias únicas para o filtro
$stmtCategorias = $pdo->query("SELECT DISTINCT categoria FROM loja WHERE status = 'ativo'");
$categorias = $stmtCategorias->fetchAll(PDO::FETCH_COLUMN);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Explorar Lojas</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- Barra de Navegação -->
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="painel_cliente.php">Shopping Digital</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="meus_pedidos.php">Meus Pedidos</a></li>
                <li class="nav-item"><a class="nav-link" href="editar_perfil.php">Editar Perfil</a></li>
                <li class="nav-item"><a class="nav-link text-danger" href="logout.php">Sair</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Conteúdo -->
<div class="container mt-4">
    <h2 class="text-center">Explorar Lojas</h2>
    <p class="text-center">Escolha uma loja e veja os produtos disponíveis.</p>

    <!-- Formulário de Filtros -->
    <form method="GET" class="row mb-4">
        <div class="col-md-6">
            <input type="text" name="busca" class="form-control" placeholder="Buscar loja..." value="<?php echo htmlspecialchars($busca); ?>">
        </div>
        <div class="col-md-4">
            <select name="categoria" class="form-select">
                <option value="">Todas as categorias</option>
                <?php foreach ($categorias as $cat): ?>
                    <option value="<?php echo htmlspecialchars($cat); ?>" <?php echo ($cat == $categoria) ? 'selected' : ''; ?>>
                        <?php echo htmlspecialchars($cat); ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">Filtrar</button>
        </div>
    </form>

    <div class="row">
        <?php if (empty($lojas)): ?>
            <p class="text-center text-muted">Nenhuma loja disponível no momento.</p>
        <?php else: ?>
            <?php foreach ($lojas as $loja): ?>
                <div class="col-md-4">
                    <div class="card mb-4 <?php echo $loja['patrocinado'] ? 'border-warning' : ''; ?>">
                        <div class="card-body">
                            <h5 class="card-title">
                                <?php echo htmlspecialchars($loja['nome']); ?>
                                <?php if ($loja['patrocinado']): ?>
                                    <span class="badge bg-warning text-dark">Patrocinado</span>
                                <?php endif; ?>
                            </h5>
                            <p class="card-text"><?php echo nl2br(htmlspecialchars($loja['descricao'])); ?></p>
                            <p><strong>Endereço:</strong> <?php echo htmlspecialchars($loja['endereco']); ?></p>
                            <p><strong>Telefone:</strong> <?php echo htmlspecialchars($loja['telefone']); ?></p>
                            <p><strong>Funcionamento:</strong> <?php echo htmlspecialchars($loja['horario_funcionamento']); ?></p>
                            <a href="produtos_mercado.php?id=<?php echo $loja['id']; ?>" class="btn btn-primary">Ver Produtos</a>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
