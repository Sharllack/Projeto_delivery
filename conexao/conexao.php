<?php
$usuario = 'root';
$senha = 'LoideMartha12*';
$database = 'venda_produtos';
$host = 'localhost';

$mysqli = new mysqli($host, $usuario, $senha, $database);

if ($mysqli->connect_error) {
    die("Falha ao conectar ao banco de dados: " . $mysqli->connect_error);
}
?>