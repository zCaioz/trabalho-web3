<?php

require_once __DIR__ . '/../services/UsuarioService.php';
require_once __DIR__ . '/../models/Usuario.php';

class UsuarioController {
    private UsuarioService $service;

    public function __construct() {
        $this->service = new UsuarioService();
        header('Content-Type: application/json');
    }

    public function processRequest(): void {
        $method = $_SERVER['REQUEST_METHOD'];

        if ($method === 'POST') {
            $this->handleCadastro();
        } else {
            http_response_code(405);
            echo json_encode(['error' => 'Método não permitido']);
        }
    }

    private function handleCadastro(): void {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            if (!isset($data['email'], $data['password'])) {
                throw new Exception("Dados inválidos");
            }

            $usuario = new Usuario([
                'email' => $data['email'],
                'senha' => $data['password']
            ]);

            $id = $this->service->cadastrar($usuario);
            echo json_encode(['success' => true, 'id' => $id]);

        } catch (Exception $e) {
            if ($e->getMessage() === "EMAIL_IN_USE") {
                echo json_encode(['success' => false, 'code' => 'EMAIL_IN_USE']);
            } else {
                http_response_code(400);
                echo json_encode(['success' => false, 'error' => $e->getMessage()]);
            }
        }
    }
}
