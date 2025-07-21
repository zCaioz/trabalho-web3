<?php

require_once __DIR__ . '/DbService.php';
require_once __DIR__ . '/../models/Usuario.php';

class UsuarioService {
    private PDO $pdo;

    public function __construct() {
        $this->pdo = DbService::getInstance();
    }

    public function cadastrar(Usuario $usuario): int {
        $stmt = $this->pdo->prepare("SELECT id FROM usuarios WHERE email = :email");
        $stmt->execute(['email' => $usuario->getEmail()]);
        if ($stmt->fetch()) {
            throw new Exception("EMAIL_IN_USE");
        }

        $stmt = $this->pdo->prepare("INSERT INTO usuarios (email, senha) VALUES (:email, :senha)");
        $stmt->execute([
            'email' => $usuario->getEmail(),
            'senha' => password_hash($usuario->getSenha(), PASSWORD_BCRYPT)
        ]);

        return (int)$this->pdo->lastInsertId();
    }

    public function autenticar(string $email, string $senha): ?Usuario {
        $stmt = $this->pdo->prepare("SELECT * FROM usuarios WHERE email = :email");
        $stmt->execute(['email' => $email]);

        $dados = $stmt->fetch();

        if ($dados && password_verify($senha, $dados['senha'])) {
            return new Usuario($dados);
        }

        return null;
    }
}
