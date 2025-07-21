<?php

require_once __DIR__ . '/../services/LivroService.php';
require_once __DIR__ . '/../models/Livro.php';
require_once __DIR__ . '/../AuthMiddleware.php';



class LivroController {
    private LivroService $service;

    public function __construct() {
        require_login();
        $this->service = LivroService::getInstance();
        header('Content-Type: application/json');
    }

    public function processRequest(): void {
        $method = $_SERVER['REQUEST_METHOD'];

        try {
            switch ($method) {
                case 'GET':
                    $this->handleGet();
                    break;
                case 'POST':
                    $this->handlePost();
                    break;
                case 'PATCH':
                    $this->handlePatch();
                    break;
                case 'DELETE':
                    $this->handleDelete();
                    break;
                default:
                    http_response_code(405);
                    echo json_encode(['erro' => 'Método não permitido']);
            }
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode(['erro' => $e->getMessage()]);
        }
    }

    private function handleGet(): void {
        if (isset($_GET['id'])) {
            $id = (int)$_GET['id'];
            $livro = $this->service->buscarPorId($id);
            if ($livro) {
                echo json_encode($livro);
            } else {
                http_response_code(404);
                echo json_encode(['erro' => 'Livro não encontrado']);
            }
        } else {
            $livros = $this->service->listar();
            echo json_encode($livros);
        }
    }

    private function handlePost(): void {
        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data) {
            throw new Exception("Dados inválidos ou ausentes.");
        }
        $livro = new Livro($data);
        $id = $this->service->criar($livro);
        http_response_code(201);
        echo json_encode(['sucesso' => true, 'id' => $id]);
    }

    private function handlePatch(): void {
        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data || !isset($data['id'])) {
            throw new Exception("Dados inválidos ou ID ausente para atualização.");
        }
        $livro = new Livro($data);
        $ok = $this->service->atualizarParcial($livro);
        echo json_encode(['sucesso' => $ok]);
    }

    private function handleDelete(): void {
        if (!isset($_GET['id'])) {
            throw new Exception("ID é obrigatório para exclusão.");
        }

        $id = (int)$_GET['id'];
        $ok = $this->service->deletar($id);
        echo json_encode(['sucesso' => $ok]);
    }
}
