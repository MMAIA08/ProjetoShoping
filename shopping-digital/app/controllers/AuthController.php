<?php
require_once __DIR__ . '/../models/User.php';

class AuthController {
    private $userModel;

    public function __construct() {
        $this->userModel = new User();
    }

    public function login($email, $senha) {
        $usuario = $this->userModel->findByEmail($email);
        if ($usuario && password_verify($senha, $usuario['senha'])) {
            session_start();
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['usuario_nome'] = $usuario['nome'];
            $_SESSION['usuario_tipo'] = $usuario['tipo'];

            header("Location: ../public/index.php");
            exit();
        } else {
            echo "<script>alert('E-mail ou senha incorretos!' ); window.location='../public/login.php';</script>";
       
        }
    }

    public function logout() {
        session_start();
        session_destroy();
        header("Location: ../public/login.php");
        exit();
    }
}
?>
