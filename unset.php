<?php 

include('./conexao/conexao.php');

if(!isset($_SESSION)) {
    session_start();
}

$idUsuario = $_SESSION['idUsuario'];

// Verifique se o usuário atual é o mesmo do pedido sendo manipulado
if ($_SESSION['idUsuario'] == $idUsuario) {
    unset($_SESSION['pedido_finalizado_' . $_SESSION['idUsuario']]);
    header('Location: ./conexao/receber_pedido.php');
}

?>