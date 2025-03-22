<?php
session_start();

// Verifica se o usuário está logado e é lojista
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo'] !== 'lojista') {
    header("Location: ../login.php");
    exit();
}

$usuario = $_SESSION['usuario'];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Painel do Lojista</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/painel_loja.css">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f8f9fa;
        }
        .navbar {
            background: linear-gradient(to right, #003366, #0066cc);
        }
        .card {
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .card:hover {
            transform: scale(1.05);
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.3);
        }
        .footer {
            background-color: #003366;
            color: white;
            padding: 20px 0;
        }
        .footer a {
            color: #f8f9fa;
            text-decoration: none;
        }
        .footer a:hover {
            color: #d4af37; /* Dourado */
        }
        .stat-card {
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            text-align: center;
        }
        .hover-shadow {
            transition: box-shadow 0.2s;
        }
        .hover-shadow:hover {
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
        }
    </style>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container">
            <a class="navbar-brand" href="#">Painel do Lojista</a>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="cadastrar_produto.php">Cadastrar Produto <i class="fas fa-plus-circle"></i></a></li>
                    <li class="nav-item"><a class="nav-link" href="listar_produtos.php">Meus Produtos <i class="fas fa-box"></i></a></li>
                    <li class="nav-item"><a class="nav-link" href="pedidos_loja.php">Pedidos Recebidos <i class="fas fa-shopping-cart"></i></a></li>
                    <li class="nav-item"><a class="nav-link" href="editar_loja.php">Editar Loja <i class="fas fa-edit"></i></a></li>
                    <li class="nav-item"><a class="nav-link text-danger" href="../public/logout.php">Sair <i class="fas fa-sign-out-alt"></i></a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container text-center mt-5">
        <h1>Bem-vindo, <?php echo htmlspecialchars($usuario['nome']); ?>!</h1>
        <p class="lead">Gerencie sua loja e produtos de forma eficiente.</p>
    </div>

    <div class="container mt-5">
        <h1 class="fw-bold text-primary text-center">Painel do Lojista</h1>

        <!-- Painel de Estatísticas -->
        <div class="row g-2 mt-4">
            <div class="col-md-3">
                <div class="stat-card">
                    <h5>Total de Vendas</h5>
                    <p class="fs-3 text-success">R$ 0,00</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <h5>Produtos Cadastrados</h5>
                    <p class="fs-3 text-primary">0</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <h5>Pedidos Recebidos</h5>
                    <p class="fs-3 text-warning">0</p>
                </div>
            </div>
            <div class="col-md-3">
                <div class="stat-card">
                    <h5>Acessos em 24h</h5>
                    <p class="fs-3 text-warning">0</p>
                </div>
            </div>
            
        </div>

        <!-- Cartões de Navegação -->
        <div class="row g-4 mt-4">
            <div class="col-md-6 col-lg-3">
                <a href="cadastrar_produto.php" class="card-link">
                    <div class="card shadow-lg hover-shadow">
                        <div class="card-body text-center">
                            <i class="fas fa-plus-circle text-primary fs-1"></i>
                            <h5 class="mt-3">Cadastrar Produto</h5>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-6 col-lg-3">
                <a href="listar_produtos.php" class="card-link">
                    <div class="card shadow-lg hover-shadow">
                        <div class="card-body text-center">
                            <i class="fas fa-box text-success fs-1"></i>
                            <h5 class="mt-3">Meus Produtos</h5>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-6 col-lg-3">
                <a href="pedidos_loja.php" class="card-link">
                    <div class="card shadow-lg hover-shadow">
                        <div class="card-body text-center">
                            <i class="fas fa-shopping-cart text-warning fs-1"></i>
                            <h5 class="mt-3">Pedidos Recebidos</h5>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-md-6 col-lg-3">
                <a href="editar_loja.php" class="card-link">
                    <div class="card shadow-lg hover-shadow">
                        <div class="card-body text-center">
                            <i class="fas fa-edit text-danger fs-1"></i>
                            <h5 class="mt-3">Editar Loja</h5>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Dicas e Ajuda -->
        <div class="row g-4 mt-5">
            <div class="col-12">
                <h2 class="fw-bold text-primary text-center">Dicas para Otimização</h2>
                <div class="alert alert-info mt-3" role="alert">
                    <strong>Dica:</strong> Utilize imagens de alta qualidade para seus produtos e escreva descrições detalhadas para atrair mais clientes.
                </div>
            </div>
        </div>
    </div>

    <footer class="footer mt-5">
        <div class="container text-center">
            <p>&copy; <?php echo date("Y"); ?> Shopping Digital. Todos os direitos reservados.</p>
            <div class="d-flex justify-content-center">
                <a href="contato.php" class="mx-3">Contato</a>
                <a href="suporte.php" class="mx-3">Suporte</a>
                <a href="privacidade.php" class="mx-3">Política de Privacidade</a>
            </div>
            <div class="mt-3">
                <a href="#" class="mx-2"><i class="fab fa-facebook fa-lg"></i></a>
                <a href="#" class="mx-2"><i class="fab fa-instagram fa-lg"></i></a>
                <a href="#" class="mx-2"><i class="fab fa-twitter fa-lg"></i></a>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
