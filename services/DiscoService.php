<?php

require_once __DIR__ . '/../models/Disco.php';
require_once __DIR__ . '/DbService.php';

class DiscoService {
    private static ?DiscoService $instance = null;
    private PDO $pdo;

    private function __construct() {
        $this->pdo = DbService::getInstance();
    }

    public static function getInstance(): DiscoService {
        if (self::$instance === null) {
            self::$instance = new DiscoService();
        }
        return self::$instance;
    }

    public function listar(): array {
        try {
            $stmt = $this->pdo->query("SELECT * FROM discos");
            $discos = [];
            while ($row = $stmt->fetch()) {
                $discos[] = new Disco($row);
            }
            return $discos;
        } catch (PDOException $e) {
            throw new Exception("Erro ao listar discos: " . $e->getMessage());
        }
    }

    public function buscarPorId(int $id): ?Disco {
        try {
            $stmt = $this->pdo->prepare("SELECT * FROM discos WHERE id = :id");
            $stmt->execute(['id' => $id]);
            $dados = $stmt->fetch();
            return $dados ? new Disco($dados) : null;
        } catch (PDOException $e) {
            throw new Exception("Erro ao buscar disco por ID: " . $e->getMessage());
        }
    }

    public function criar(Disco $disco): int {
        try {
            $sql = "INSERT INTO discos (titulo, artista, numero_faixas, gravadora, ano_lancamento)
                    VALUES (:titulo, :artista, :numero_faixas, :gravadora, :ano_lancamento)";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute([
                'titulo' => $disco->getTitulo(),
                'artista' => $disco->getArtista(),
                'numero_faixas' => $disco->getNumeroFaixas(),
                'gravadora' => $disco->getGravadora(),
                'ano_lancamento' => $disco->getAnoLancamento(),
            ]);
            return (int)$this->pdo->lastInsertId();
        } catch (PDOException $e) {
            throw new Exception("Erro ao criar disco: " . $e->getMessage());
        }
    }

    public function atualizar(Disco $disco): bool {
        try {
            $sql = "UPDATE discos SET titulo = :titulo, artista = :artista, numero_faixas = :numero_faixas,
                    gravadora = :gravadora, ano_lancamento = :ano_lancamento WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute([
                'titulo' => $disco->getTitulo(),
                'artista' => $disco->getArtista(),
                'numero_faixas' => $disco->getNumeroFaixas(),
                'gravadora' => $disco->getGravadora(),
                'ano_lancamento' => $disco->getAnoLancamento(),
                'id' => $disco->getId(),
            ]);
        } catch (PDOException $e) {
            throw new Exception("Erro ao atualizar disco: " . $e->getMessage());
        }
    }

    public function atualizarParcial(Disco $disco): bool {
        try {
            $campos = [];
            $params = ['id' => $disco->getId()];

            if ($disco->getTitulo() !== null) {
                $campos[] = "titulo = :titulo";
                $params['titulo'] = $disco->getTitulo();
            }
            if ($disco->getArtista() !== null) {
                $campos[] = "artista = :artista";
                $params['artista'] = $disco->getArtista();
            }
            if ($disco->getNumeroFaixas() !== null) {
                $campos[] = "numero_faixas = :numero_faixas";
                $params['numero_faixas'] = $disco->getNumeroFaixas();
            }
            if ($disco->getGravadora() !== null) {
                $campos[] = "gravadora = :gravadora";
                $params['gravadora'] = $disco->getGravadora();
            }
            if ($disco->getAnoLancamento() !== null) {
                $campos[] = "ano_lancamento = :ano_lancamento";
                $params['ano_lancamento'] = $disco->getAnoLancamento();
            }

            if (empty($campos)) {
                throw new Exception("Nenhum campo informado para atualização parcial.");
            }

            $sql = "UPDATE discos SET " . implode(", ", $campos) . " WHERE id = :id";
            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute($params);
        } catch (PDOException $e) {
            throw new Exception("Erro ao atualizar parcialmente o disco: " . $e->getMessage());
        }
    }

    public function deletar(int $id): bool {
        try {
            $stmt = $this->pdo->prepare("DELETE FROM discos WHERE id = :id");
            return $stmt->execute(['id' => $id]);
        } catch (PDOException $e) {
            throw new Exception("Erro ao deletar disco: " . $e->getMessage());
        }
    }
}
