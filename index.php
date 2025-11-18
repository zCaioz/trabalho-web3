<?php
require_once __DIR__ . '/controllers/LivroController.php';
require_once __DIR__ . '/controllers/DiscoController.php';
require_once __DIR__ . '/controllers/UsuarioController.php';
require_once __DIR__ . '/controllers/AuthController.php';

$origin = $_SERVER['HTTP_ORIGIN'] ?? '';
$allowed_origins = ['https://bibilioteca-frontend.netlify.app'];

if (in_array($origin, $allowed_origins)) {
    header("Access-Control-Allow-Origin: $origin");
    header("Access-Control-Allow-Credentials: true");
}

header("Access-Control-Allow-Methods: GET, POST, PATCH, DELETE, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

session_set_cookie_params([
  'lifetime' => 0,
  'path' => '/',
  'domain' => '', 
  'secure' => true,
  'httponly' => true,
  'samesite' => 'None'
]);
session_start();

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

