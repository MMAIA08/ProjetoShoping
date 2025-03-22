<?php
require_once __DIR__ . '/../conexao.php'; // Inclui a conexão com o banco de dados

class User {
    private $pdo;

    public function __construct() {
        global $pdo; // Usa a conexão do arquivo conexao.php
        $this->pdo = $pdo;
    }

    public function cadastrar($nome, $email, $senha, $tipo, $cpf, $telefone, $endereco) {
        // Verifica se o e-mail já existe
        $sql_verifica = "SELECT id FROM usuarios WHERE email = :email";
        $stmt_verifica = $this->pdo->prepare($sql_verifica);
        $stmt_verifica->bindParam(':email', $email);
        $stmt_verifica->execute();
        
        // Se o e-mail já existir, retorna um erro
        if ($stmt_verifica->rowCount() > 0) {
            return "Erro: E-mail já está em uso.";
        }
    
        // Se não existir, faz a inserção no banco de dados
        $sql = "INSERT INTO usuarios (nome, email, senha, tipo, cpf, telefone, endereco) VALUES (:nome, :email, :senha, :tipo, :cpf, :telefone, :endereco)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindParam(':nome', $nome);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':senha', password_hash($senha, PASSWORD_DEFAULT)); // Criptografando a senha
        $stmt->bindParam(':tipo', $tipo);
        $stmt->bindParam(':cpf', $cpf);
        $stmt->bindParam(':telefone', $telefone);
        $stmt->bindParam(':endereco', $endereco);
        
        if ($stmt->execute()) {
            return true;  // Cadastro realizado com sucesso
        } else {
            return "Erro ao cadastrar.";
        }
    }
} 
?>
