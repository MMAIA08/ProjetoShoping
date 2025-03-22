<?php
session_start();
require_once __DIR__ . '/../app/conexao.php';

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['tipo'] !== 'lojista') {
    $_SESSION['erro'] = "Acesso negado!";
    header("Location: ../login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        $loja_id = $_POST['loja_id'];
        $nome = trim($_POST['nome']);
        $descricao = trim($_POST['descricao']);
        $endereco = trim($_POST['endereco']);
        $telefone = trim($_POST['telefone']);
        $horario_funcionamento = trim($_POST['horario_funcionamento']);
        $imagem_atual = $_POST['imagem_atual'] ?? null; // Mantém a imagem antiga se não for alterada

        // Verifica se um novo arquivo foi enviado
        if (!empty($_FILES['imagem']['name'])) {
            $imagem = $_FILES['imagem'];
            $extensao = strtolower(pathinfo($imagem['name'], PATHINFO_EXTENSION));

            if (!in_array($extensao, ['jpg', 'jpeg', 'png', 'gif'])) {
                $_SESSION['erro'] = "Formato de imagem inválido! Use JPG, JPEG, PNG ou GIF.";
                header("Location: editar_loja.php");
                exit();
            }

            $novo_nome = "uploads/lojas/" . uniqid() . ".$extensao";
            if (move_uploaded_file($imagem['tmp_name'], "../$novo_nome")) {
                $imagem_atual = $novo_nome; // Atualiza com a nova imagem
            } else {
                $_SESSION['erro'] = "Erro ao fazer upload da imagem.";
                header("Location: editar_loja.php");
                exit();
            }
        }

        // Atualiza os dados da loja
        $sql = "UPDATE lojas SET nome = :nome, descricao = :descricao, endereco = :endereco, telefone = :telefone, 
                horario_funcionamento = :horario_funcionamento, imagem = :imagem WHERE id = :loja_id";

        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':descricao', $descricao);
        $stmt->bindParam(':endereco', $endereco);
        $stmt->bindParam(':telefone', $telefone);
        $stmt->bindParam(':horario_funcionamento', $horario_funcionamento);
        $stmt->bindParam(':imagem', $imagem_atual);
        $stmt->bindParam(':loja_id', $loja_id);

        if ($stmt->execute()) {
            $_SESSION['sucesso'] = "Loja atualizada com sucesso!";
        } else {
            $_SESSION['erro'] = "Erro ao atualizar a loja!";
        }

    } catch (Exception $e) {
        $_SESSION['erro'] = "Erro: " . $e->getMessage();
    }

    header("Location: editar_loja.php");
    exit();
} else {
    $_SESSION['erro'] = "Acesso inválido!";
    header("Location: editar_loja.php");
    exit();
}
