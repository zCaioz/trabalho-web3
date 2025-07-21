<?php

require_once __DIR__ . '/../models/Livro.php';
require_once __DIR__ . '/DbService.php';

class LivroService {
    private static ?LivroService $instance = null;
    private PDO $pdo;

    private function __construct() {
        $this->pdo = DbService::getInstance();
    }

    public static function getInstance(): LivroService {
        if (self::$instance === null) {
            self::$instance = new LivroService();
        }
        return self::$instance;
    }

    public function listar(): array {
        try {
            $stmt = $this->pdo->query("SELECT * FROM livros");
            $livros = [];
            while ($row = $stmt->fetch()) {
                $livros[] = new Livro($row);
            }
            return $livros;
        } catch (PDOException $e) {
            throw new Exception("Erro ao listar livros: " . $e->getMessage());
        }
    }

    public function buscarPorId(int $id): ?Livro {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM livros WHERE id = :id");
            $stmt->execute(['id' => $id]);
            $dados = $stmt->fetch();
            return $dados ? new Livro($dados) : null;
        } catch (PDOException $e) {
            throw new Exception("Erro ao buscar livro por ID: " . $e->getMessage());
        }
    }

    public function criar(Livro $livro): int {
        try {
            $sql = "INSERT INTO livros (titulo, autor, numero_paginas, editora, ano_publicacao)
                    VALUES (:titulo, :autor, :numero_paginas, :editora, :ano_publicacao)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                'titulo' => $livro->getTitulo(),
                'autor' => $livro->getAutor(),
                'numero_paginas' => $livro->getNumeroPaginas(),
                'editora' => $livro->getEditora(),
                'ano_publicacao' => $livro->getAnoPublicacao(),
            ]);
            return (int)$this->pdo->lastInsertId();
        } catch (PDOException $e) {
            throw new Exception("Erro ao criar livro: " . $e->getMessage());
        }
    }

    public function atualizar(Livro $livro): bool {
        try {
            $sql = "UPDATE livros SET titulo = :titulo, autor = :autor, numero_paginas = :numero_paginas,
                    editora = :editora, ano_publicacao = :ano_publicacao WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([
                'titulo' => $livro->getTitulo(),
                'autor' => $livro->getAutor(),
                'numero_paginas' => $livro->getNumeroPaginas(),
                'editora' => $livro->getEditora(),
                'ano_publicacao' => $livro->getAnoPublicacao(),
                'id' => $livro->getId(),
            ]);
        } catch (PDOException $e) {
            throw new Exception("Erro ao atualizar livro: " . $e->getMessage());
        }
    }

    public function atualizarParcial(Livro $livro): bool {
        try {
            $campos = [];
            $params = ['id' => $livro->getId()];

            if ($livro->getTitulo() !== null) {
                $campos[] = "titulo = :titulo";
                $params['titulo'] = $livro->getTitulo();
            }
            if ($livro->getAutor() !== null) {
                $campos[] = "autor = :autor";
                $params['autor'] = $livro->getAutor();
            }
            if ($livro->getNumeroPaginas() !== null) {
                $campos[] = "numero_paginas = :numero_paginas";
                $params['numero_paginas'] = $livro->getNumeroPaginas();
            }
            if ($livro->getEditora() !== null) {
                $campos[] = "editora = :editora";
                $params['editora'] = $livro->getEditora();
            }
            if ($livro->getAnoPublicacao() !== null) {
                $campos[] = "ano_publicacao = :ano_publicacao";
                $params['ano_publicacao'] = $livro->getAnoPublicacao();
            }

            if (empty($campos)) {
                throw new Exception("Nenhum campo informado para atualização.");
            }

            $sql = "UPDATE livros SET " . implode(", ", $campos) . " WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            throw new Exception("Erro ao atualizar parcialmente o livro: " . $e->getMessage());
        }
    }

    public function deletar(int $id): bool {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM livros WHERE id = :id");
            return $stmt->execute(['id' => $id]);
        } catch (PDOException $e) {
            throw new Exception("Erro ao deletar livro: " . $e->getMessage());
        }
    }
}
