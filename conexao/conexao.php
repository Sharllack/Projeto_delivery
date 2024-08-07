<?php
require_once __DIR__ . '/../vendor/autoload.php'; // Inclui o autoload do Composer

use Dotenv\Dotenv;

// Carrega o arquivo .env
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Recupera as variáveis do arquivo .env
$usuario = $_ENV['DB_USER'];
$senha = $_ENV['DB_PASS'];
$database = $_ENV['DB_NAME'];
$host = $_ENV['DB_HOST'];

// Cria uma nova conexão MySQLi
$mysqli = new mysqli($host, $usuario, $senha, $database);

// Verifica se houve um erro na conexão
if ($mysqli->connect_error) {
    die("Falha ao conectar ao banco de dados: " . $mysqli->connect_error);
}
?>