<?php

class Disco implements JsonSerializable {
    private int $id;
    private string $titulo;
    private string $artista;
    private int $numeroFaixas;
    private ?string $gravadora;
    private ?int $anoLancamento;

    public function __construct(array $dados = []) {
        $this->id = $dados['id'] ?? 0;
        $this->titulo = $dados['titulo'] ?? '';
        $this->artista = $dados['artista'] ?? '';
        $this->numeroFaixas = $dados['numero_faixas'] ?? 0;
        $this->gravadora = $dados['gravadora'] ?? null;
        $this->anoLancamento = $dados['ano_lancamento'] ?? null;
    }

    public function getId(): int { return $this->id; }
    public function setId(int $id): void { $this->id = $id; }

    public function getTitulo(): string { return $this->titulo; }
    public function setTitulo(string $titulo): void { $this->titulo = $titulo; }

    public function getArtista(): string { return $this->artista; }
    public function setArtista(string $artista): void { $this->artista = $artista; }

    public function getNumeroFaixas(): int { return $this->numeroFaixas; }
    public function setNumeroFaixas(int $numeroFaixas): void { $this->numeroFaixas = $numeroFaixas; }

    public function getGravadora(): ?string { return $this->gravadora; }
    public function setGravadora(?string $gravadora): void { $this->gravadora = $gravadora; }

    public function getAnoLancamento(): ?int { return $this->anoLancamento; }
    public function setAnoLancamento(?int $anoLancamento): void { $this->anoLancamento = $anoLancamento; }

    public function jsonSerialize() {
        return [
            'id' => $this->id,
            'titulo' => $this->titulo,
            'artista' => $this->artista,
            'numeroFaixas' => $this->numeroFaixas,
            'gravadora' => $this->gravadora,
            'anoLancamento' => $this->anoLancamento,
        ];
    }
}
