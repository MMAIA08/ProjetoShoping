<?php
session_start();
require_once __DIR__ . '/../app/conexao.php';

// Verifica se o usuário está logado e é lojista
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo'] !== 'lojista') {
    header("Location: ../login.php");
    exit();
}

$usuario = $_SESSION['usuario'];

// Busca os dados da loja do lojista logado
$sql = "SELECT * FROM lojas WHERE usuario_id = :usuario_id";
$stmt = $pdo->prepare($sql);
$stmt->bindParam(':usuario_id', $usuario['id']);
$stmt->execute();
$loja = $stmt->fetch(PDO::FETCH_ASSOC);

// Se a loja não existir, redireciona
if (!$loja) {
    $_SESSION['erro'] = "Loja não encontrada!";
    header("Location: painel_loja.php");
    exit();
}

// Busca os produtos cadastrados na loja
$sqlProdutos = "SELECT * FROM produtos WHERE loja_id = :loja_id";
$stmtProdutos = $pdo->prepare($sqlProdutos);
$stmtProdutos->bindParam(':loja_id', $loja['id']);
$stmtProdutos->execute();
$produtos = $stmtProdutos->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Loja</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f8f9fa;
        }
        .container {
            background-color: #fff;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
        .btn-primary {
            background-color: #007bff;
            border: none;
        }
        .btn-secondary {
            background-color: #6c757d;
            border: none;
        }
    </style>
</head>
<body>

<div class="container mt-5">
    <h2 class="text-primary text-center">Editar Informações da Loja</h2>

    <?php if (isset($_SESSION['sucesso'])): ?>
        <div class="alert alert-success"><?php echo $_SESSION['sucesso']; unset($_SESSION['sucesso']); ?></div>
    <?php endif; ?>

    <?php if (isset($_SESSION['erro'])): ?>
        <div class="alert alert-danger"><?php echo $_SESSION['erro']; unset($_SESSION['erro']); ?></div>
    <?php endif; ?>

    <form action="processa_editar_loja.php" method="POST" enctype="multipart/form-data" class="card p-4">
        <input type="hidden" name="loja_id" value="<?php echo $loja['id']; ?>">

        <!-- Imagem da Loja -->
        <div class="text-center mb-3">
            <?php if (!empty($loja['imagem'])): ?>
                <img src="../<?php echo htmlspecialchars($loja['imagem']); ?>" alt="Imagem da Loja" class="img-thumbnail" style="max-width: 200px;">
            <?php else: ?>
                <p class="text-muted">Sem imagem</p>
            <?php endif; ?>
        </div>

        <div class="mb-3">
            <label class="form-label">Alterar Imagem da Loja</label>
            <input type="file" class="form-control" name="imagem">
        </div>

        <div class="mb-3">
            <label class="form-label">Nome da Loja</label>
            <input type="text" class="form-control" name="nome" value="<?php echo htmlspecialchars($loja['nome']); ?>" required placeholder="Digite o nome da loja">
        </div>

        <div class="mb-3">
            <label class="form-label">Descrição</label>
            <textarea class="form-control" name="descricao" rows="3" placeholder="Descreva sua loja..."><?php echo htmlspecialchars($loja['descricao']); ?></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Endereço</label>
            <input type="text" class="form-control" name="endereco" value="<?php echo htmlspecialchars($loja['endereco']); ?>" required placeholder="Digite o endereço da loja">
        </div>

        <div class="mb-3">
            <label class="form-label">Telefone</label>
            <input type="text" class="form-control" name="telefone" value="<?php echo htmlspecialchars($loja['telefone']); ?>" placeholder="Digite o telefone da loja">
        </div>

        <div class="mb-3">
            <label class="form-label">Horário de Funcionamento</label>
            <input type="text" class="form-control" name="horario_funcionamento" value="<?php echo htmlspecialchars($loja['horario_funcionamento']); ?>" placeholder="Ex: 09:00 às 18:00">
        </div>

        <button type="submit" class="btn btn-primary">Salvar Alterações</button>
        <a href="painel_loja.php" class="btn btn-secondary">Voltar ao Painel</a>
    </form>

    <hr>

    <h3 class="mt-4 text-center">Produtos da Loja</h3>
    <div class="text-center mb-3">
        <a href="cadastrar_produto.php" class="btn btn-success">Cadastrar Novo Produto</a>
    </div>

    <div class="row">
        <?php if (count($produtos) > 0): ?>
            <?php foreach ($produtos as $produto): ?>
                <div class="col-md-4">
                    <div class="card mb-4 shadow-sm">
                        <img src="../<?php echo htmlspecialchars($produto['imagem']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($produto['nome']); ?>" style="height: 200px; object-fit: cover;">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo htmlspecialchars($produto['nome']); ?></h5>
                            <p class="card-text"><?php echo nl2br(htmlspecialchars($produto['descricao'])); ?></p>
                            <p class="card-text"><strong>R$ <?php echo number_format($produto['preco'], 2, ',', '.'); ?></strong></p>
                            <p class="card-text">Estoque: <?php echo htmlspecialchars($produto['estoque']); ?></p>
                            <div class="d-flex justify-content-between">
                                <a href="editar_produto.php?id=<?php echo $produto['id']; ?>" class="btn btn-warning">Editar</a>
                                <a href="excluir_produto.php?id=<?php echo $produto['id']; ?>" class="btn btn-danger" onclick="return confirm('Tem certeza que deseja excluir este produto?')">Excluir</a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p class="text-muted text-center">Nenhum produto cadastrado.</p>
        <?php endif; ?>
    </div>
</div>

</body>
</html>
