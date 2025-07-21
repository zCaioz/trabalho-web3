<?php

function require_login() {
    if (!isset($_SESSION['usuario'])) {
        http_response_code(401);
        echo json_encode(['erro' => 'Usuário não autenticado']);
        exit;
    }
}
