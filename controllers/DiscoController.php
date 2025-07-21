<?php

require_once __DIR__ . '/../services/DiscoService.php';
require_once __DIR__ . '/../models/Disco.php';
require_once __DIR__ . '/../AuthMiddleware.php';


class DiscoController {
    private DiscoService $service;

    public function __construct() {
        require_login();
        $this->service = DiscoService::getInstance();
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
            $disco = $this->service->buscarPorId($id);
            if ($disco) {
                echo json_encode($disco);
            } else {
                http_response_code(404);
                echo json_encode(['erro' => 'Disco não encontrado']);
            }
        } else {
            $discos = $this->service->listar();
            echo json_encode($discos);
        }
    }

    private function handlePost(): void {
        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data) {
            throw new Exception("Dados inválidos ou ausentes.");
        }
        $disco = new Disco($data);
        $id = $this->service->criar($disco);
        http_response_code(201);
        echo json_encode(['sucesso' => true, 'id' => $id]);
    }

    private function handlePatch(): void {
        $data = json_decode(file_get_contents('php://input'), true);
        if (!$data || !isset($data['id'])) {
            throw new Exception("Dados inválidos ou ID ausente para atualização.");
        }
        $disco = new Disco($data);
        $ok = $this->service->atualizarParcial($disco);
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
