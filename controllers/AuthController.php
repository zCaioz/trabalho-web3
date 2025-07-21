<?php

require_once __DIR__ . '/../services/UsuarioService.php';
require_once __DIR__ . '/../models/Usuario.php';

class AuthController {
    private UsuarioService $service;

    public function __construct() {
        $this->service = new UsuarioService();
        header('Content-Type: application/json');
    }

    public function processRequest(): void {
        $method = $_SERVER['REQUEST_METHOD'];

        switch ($method) {
            case 'POST':
                $this->handleLogin();
                break;
            case 'GET':
                $this->handleUsuarioLogado();
                break;
            case 'DELETE':
                $this->handleLogout();
                break;
            default:
                http_response_code(405);
                echo json_encode(['error' => 'Método não permitido']);
        }
    }

    private function handleLogin(): void {
        try {
            $data = json_decode(file_get_contents('php://input'), true);
            if (!isset($data['email'], $data['password'])) {
                throw new Exception("Dados inválidos");
            }

            $usuario = $this->service->autenticar($data['email'], $data['password']);
            if (!$usuario) {
                http_response_code(401);
                echo json_encode(['success' => false, 'message' => 'Credenciais inválidas']);
                return;
            }

            $_SESSION['usuario'] = [
                'id' => $usuario->getId(),
                'email' => $usuario->getEmail()
            ];

            echo json_encode(['success' => true]);

        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode(['success' => false, 'error' => $e->getMessage()]);
        }
    }

    private function handleLogout(): void {
        session_destroy();
        echo json_encode(['success' => true]);
    }

    private function handleUsuarioLogado(): void {
        if (!isset($_SESSION['usuario'])) {
            http_response_code(401);
            echo json_encode(['logado' => false]);
        } else {
            echo json_encode([
                'logado' => true,
                'usuario' => $_SESSION['usuario']
            ]);
        }
    }
}
