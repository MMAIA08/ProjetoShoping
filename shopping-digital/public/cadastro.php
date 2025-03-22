<?php
require_once '../app/controllers/AuthController.php';
require_once '../app/models/User.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $tipo = $_POST['tipo'];
    $cpf = $_POST['cpf'];
    $telefone = $_POST['telefone'];
    $endereco = $_POST['endereco'];
    $veiculo = isset($_POST['veiculo']) ? $_POST['veiculo'] : null;

    $user = new User();
    $mensagem = $user->cadastrar($nome, $email, $senha, $tipo, $cpf, $telefone, $endereco);

    if ($mensagem !== true) {
        echo "<script>alert('$mensagem');</script>";  // Exibe a mensagem de erro se o e-mail já estiver em uso
    } else {
        echo "<script>alert('Cadastro realizado com sucesso!'); window.location='login.php';</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro - Shopping Digital</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleVeiculoField() {
            var tipo = document.getElementById("tipo").value;
            var veiculoField = document.getElementById("veiculo-group");
            veiculoField.style.display = tipo === "entregador" ? "block" : "none";
        }
    </script>
    <style>
        body {
            background-color: #f8f9fa; /* Fundo claro */
        }
        .card {
            border-radius: 10px; /* Bordas arredondadas */
        }
        .btn-primary {
            background-color: #007bff; /* Azul do Bootstrap */
            border: none;
        }
        .btn-primary:hover {
            background-color: #0056b3; /* Tom mais escuro no hover */
        }
    </style>
</head>
<body class="bg-light">

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8 col-lg-6">
            <div class="card shadow-lg">
                <div class="card-body">
                    <h3 class="text-center mb-4">Criar Conta</h3>
                    <form action="cadastro.php" method="POST">
                        <div class="mb-3">
                            <label for="nome" class="form-label">Nome</label>
                            <input type="text" id="nome" name="nome" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="email" class="form-label">E-mail</label>
                            <input type="email" id="email" name="email" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="cpf" class="form-label">CPF</label>
                            <input type="text" id="cpf" name="cpf" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="telefone" class="form-label">Telefone</label>
                            <input type="text" id="telefone" name="telefone" class="form-control">
                        </div>

                        <div class="mb-3">
                            <label for="endereco" class="form-label">Endereço</label>
                            <input type="text" id="endereco" name="endereco" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="senha" class="form-label">Senha</label>
                            <input type="password" id="senha" name="senha" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label for="tipo" class="form-label">Tipo de Conta</label>
                            <select id="tipo" name="tipo" class="form-select" required onchange="toggleVeiculoField()">
                                <option value="cliente">Cliente</option>
                                <option value="lojista">Lojista</option>
                                <option value="entregador">Entregador</option>
                            </select>
                        </div>

                        <div class="mb-3" id="veiculo-group" style="display: none;">
                            <label for="veiculo" class="form-label">Veículo (para entregadores)</label>
                            <input type="text" id="veiculo" name="veiculo" class="form-control">
                        </div>

                        <button type="submit" class="btn btn-primary w-100">Cadastrar</button>

                        <p class="text-center mt-3">Já tem uma conta? <a href="login.php">Faça login</a></p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

</body>
</html>
