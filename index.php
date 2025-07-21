<?php
require_once __DIR__ . '/controllers/LivroController.php';
require_once __DIR__ . '/controllers/DiscoController.php';
require_once __DIR__ . '/controllers/UsuarioController.php';
require_once __DIR__ . '/controllers/AuthController.php';

session_start();

session_set_cookie_params([
  'lifetime' => 0,
  'path' => '/',
  'domain' => '', 
  'secure' => true,
  'httponly' => true,
  'samesite' => 'None'
]);

header("Access-Control-Allow-Origin: https://bibilioteca-frontend.netlify.app");
header("Access-Control-Allow-Credentials: true");
header("Access-Control-Allow-Methods: GET, POST, PATCH, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$method = $_SERVER['REQUEST_METHOD'];

$uri = str_replace('/trabalhoweb2', '', $uri);

$parts = array_values(array_filter(explode('/', $uri)));

if (count($parts) < 3 || $parts[0] !== 'api' || $parts[1] !== 'v1') {
    http_response_code(404);
    echo json_encode(['erro' => 'Endpoint inválido']);
    exit;
}

$resource = $parts[2];

switch ($resource) {
    case 'livros':
        $controller = new LivroController();
        break;
    case 'discos':
        $controller = new DiscoController();
        break;
    case 'usuarios':
        $controller = new UsuarioController();
        break;
    case 'auth':
        $controller = new AuthController();
        break;
    default:
        http_response_code(404);
        echo json_encode(['erro' => 'Recurso não encontrado']);
        exit;
}

$controller->processRequest();
