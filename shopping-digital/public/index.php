<?php
session_start();

// Verifica se o usuário está logado
if (!isset($_SESSION['usuario_id'])) {
    header("Location: login.php");
    exit();
}

// Obtém informações do usuário logado
$nome = $_SESSION['usuario_nome'];
$tipo = ucfirst($_SESSION['usuario_tipo']); // Primeira letra maiúscula
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Inicial</title>
    <link rel="stylesheet" href="../assets/css/index.css">
</head>
<body>
    <div class="container">
        <h1>Bem-vindo, <?= htmlspecialchars($nome) ?>!</h1>
        <p>Você está logado como <strong><?= htmlspecialchars($tipo) ?></strong>.</p>
        
        <a href="login.php" class="btn">Sair</a>
    </div>
</body>
</html>
