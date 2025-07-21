<?php

class Livro implements JsonSerializable {
    private int $id;
    private string $titulo;
    private string $autor;
    private int $numeroPaginas;
    private ?string $editora;
    private ?int $anoPublicacao;

    public function __construct(array $dados = []) {
        $this->id = $dados['id'] ?? 0;
        $this->titulo = $dados['titulo'] ?? '';
        $this->autor = $dados['autor'] ?? '';
        $this->numeroPaginas = $dados['numero_paginas'] ?? 0;
        $this->editora = $dados['editora'] ?? null;
        $this->anoPublicacao = $dados['ano_publicacao'] ?? null;
    }

    public function getId(): int { return $this->id; }
    public function setId(int $id): void { $this->id = $id; }

    public function getTitulo(): string { return $this->titulo; }
    public function setTitulo(string $titulo): void { $this->titulo = $titulo; }

    public function getAutor(): string { return $this->autor; }
    public function setAutor(string $autor): void { $this->autor = $autor; }

    public function getNumeroPaginas(): int { return $this->numeroPaginas; }
    public function setNumeroPaginas(int $numeroPaginas): void { $this->numeroPaginas = $numeroPaginas; }

    public function getEditora(): ?string { return $this->editora; }
    public function setEditora(?string $editora): void { $this->editora = $editora; }

    public function getAnoPublicacao(): ?int { return $this->anoPublicacao; }
    public function setAnoPublicacao(?int $anoPublicacao): void { $this->anoPublicacao = $anoPublicacao; }

    public function jsonSerialize() {
        return [
            'id' => $this->id,
            'titulo' => $this->titulo,
            'autor' => $this->autor,
            'numeroPaginas' => $this->numeroPaginas,
            'editora' => $this->editora,
            'anoPublicacao' => $this->anoPublicacao,
        ];
    }
}
