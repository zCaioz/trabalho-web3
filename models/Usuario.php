<?php

class Usuario {
    private int $id;
    private string $email;
    private string $senha;

    public function __construct(array $dados = []) {
        $this->id = $dados['id'] ?? 0;
        $this->email = $dados['email'] ?? '';
        $this->senha = $dados['senha'] ?? '';
    }

    public function getId(): int { return $this->id; }
    public function getEmail(): string { return $this->email; }
    public function getSenha(): string { return $this->senha; }

    public function setId(int $id): void { $this->id = $id; }
    public function setEmail(string $email): void { $this->email = $email; }
    public function setSenha(string $senha): void { $this->senha = $senha; }
}
