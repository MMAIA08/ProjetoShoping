<?php
require_once '../app/controllers/AuthController.php';
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $auth = new AuthController();
    $auth->login($email, $senha);
}
?>

<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Shopping Digital</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../assets/css/login.css">
    <style>
        body {
            background-color: #f8f9fa; /* Fundo claro */
        }
        .login-container {
            max-width: 400px;
            margin: 100px auto;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .btn {
            background-color: #007bff; /* Azul do Bootstrap */
            color: white;
        }
        .register-link a {
            color: #007bff; /* Azul do Bootstrap */
        }
        .register-link a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2 class="text-center">Bem-vindo ao Shopping Digital</h2>
        <form action="processa_login.php" method="POST">
            <div class="mb-3">
                <label for="email" class="form-label">E-mail:</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="senha" class="form-label">Senha:</label>
                <input type="password" id="senha" name="senha" class="form-control" required>
            </div>

            <button type="submit" class="btn btn-primary w-100">Entrar</button>
        </form>

        <p class="register-link text-center mt-3">Ainda não tem uma conta? <a href="cadastro.php">Cadastre-se</a></p>
        <p class="register-link text-center">Recuperar senha: <a href="#">Esqueci senha</a></p>
    </div>
    
</body>
</html>
