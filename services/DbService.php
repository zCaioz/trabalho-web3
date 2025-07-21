<?php

class DbService {
    private static ?PDO $instance = null;

    private static string $host = 'localhost';
    private static string $dbName = 'biblioteca';  
    private static string $username = 'root';
    private static string $password = '';
    private static string $charset = 'utf8mb4';

    private function __construct() {}

    
    public static function getInstance(): PDO {
        if (self::$instance === null) {
            $dsn = "mysql:host=" . self::$host . ";dbname=" . self::$dbName . ";charset=" . self::$charset;
            try {
                self::$instance = new PDO($dsn, self::$username, self::$password, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                ]);
            } catch (PDOException $e) {
                http_response_code(500);
                echo json_encode(['erro' => 'Erro ao conectar com o banco de dados', 'detalhes' => $e->getMessage()]);
                exit;
            }
        }
        return self::$instance;
    }
}
