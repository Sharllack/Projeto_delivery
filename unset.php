<?php 

include('./conexao/conexao.php');

if(!isset($_SESSION)) {
    session_start();
}

unset($_SESSION['pedido_finalizado'][$_SESSION['idUsuario']]);
header('Location: ./conexao/receber_pedido.php');

?>