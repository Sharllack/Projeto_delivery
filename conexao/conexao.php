<?php
$usuario = 'root';
$senha = 'LoideMartha12*';
$database = 'delivery';
$host = 'localhost';

$mysqli = new mysqli($host, $usuario, $senha, $database);

if ($mysqli->connect_error) {
    die("Falha ao conectar ao banco de dados: " . $mysqli->connect_error);
}
?>