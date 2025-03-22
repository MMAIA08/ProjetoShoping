<?php
session_start();

// Verifica se o usuário está logado e é cliente
if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo'] !== 'cliente') {
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
    <title>Painel do Cliente</title>
    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background-color: #f8f9fa;
        }
        /* Navbar */
        .navbar {
            background: linear-gradient(to right, #003366, #0066cc);
        }
        .navbar-brand {
            font-weight: bold;
        }
        .nav-link {
            font-size: 1.1rem;
        }
        .nav-link:hover {
            color: #d4af37 !important;
        }
        /* Banner de Promoções */
        .promo-banner {
            width: 100%;
            height: 250px;
            background: url('../assets/img/promo.jpg') no-repeat center center;
            background-size: cover;
            color: white;
            text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.7);
        }
        /* Cards */
        .card {
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2);
        }
        /* Rodapé */
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
            color: #d4af37;
        }
    </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark">
    <div class="container">
        <a class="navbar-brand" href="#">Shopping Digital</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a class="nav-link" href="explorar_lojas.php">Explorar Lojas</a></li>
                <li class="nav-item"><a class="nav-link" href="meus_pedidos.php">Meus Pedidos</a></li>
                <li class="nav-item"><a class="nav-link" href="editar_perfil.php">Editar Perfil</a></li>
                <li class="nav-item"><a class="nav-link text-danger" href="logout.php">Sair</a></li>
            </ul>
        </div>
    </div>
</nav>

<!-- Banner de Promoções -->
<div class="promo-banner text-center d-flex align-items-center justify-content-center">
    <h1 class="fw-bold">Aproveite as melhores ofertas do dia!</h1>
</div>

<!-- Conteúdo do Painel -->
<div class="container mt-4">
    <div class="text-center">
        <h2>Bem-vindo, <?php echo htmlspecialchars($usuario['nome']); ?>!</h2>
        <p class="lead">Descubra ofertas exclusivas, acompanhe seus pedidos e gerencie sua conta.</p>
    </div>

    <!-- Carrossel de Destaques -->
    <div id="carouselExampleIndicators" class="carousel slide mt-1" data-bs-ride="carousel">
        <div class="carousel-indicators">
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active"></button>
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1"></button>
            <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2"></button>
        </div>
        <div class="carousel-inner">
            <div class="carousel-item active">
                <img src="../assets/img/1.jpeg" class="d-block w-0" alt="Promoção 1">
            </div>
            <div class="carousel-item">
                <img src="../assets/img/2.jpeg" class="d-block w-50" alt="Promoção 2">
            </div>
            <div class="carousel-item">
                <img src="../assets/img/3.jpeg" class="d-block w-50" alt="Promoção 3">
            </div>
        </div>
    </div>

    <!-- Seções de Destaque -->
    <div class="row mt-4">
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Ofertas Relâmpago</h5>
                    <p class="card-text">Descontos incríveis por tempo limitado!</p>
                    <a href="#" class="btn btn-danger">Ver Agora</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Mais Vendidos</h5>
                    <p class="card-text">Os produtos mais populares do momento.</p>
                    <a href="#" class="btn btn-warning">Descobrir</a>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-center">
                <div class="card-body">
                    <h5 class="card-title">Novidades</h5>
                    <p class="card-text">Confira os lançamentos mais recentes.</p>
                    <a href="#" class="btn btn-primary">Ver Lançamentos</a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Rodapé -->
<footer class="footer mt-5">
    <div class="container text-center">
        <p>&copy; <?php echo date("Y"); ?> Shopping Digital. Todos os direitos reservados.</p>
    </div>
</footer>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
