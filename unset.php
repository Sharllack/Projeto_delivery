<?php 

include('./conexao/conexao.php');

if(!isset($_SESSION)) {
    session_start();
}

if(isset($_GET['idUsuario'])) {
    $idUsuario = intval($_GET['idUsuario']);
    
    // Limpe a variável de sessão específica para este usuário, se existir
    if (isset($_SESSION['pedido_finalizado_' . $idUsuario])) {
        unset($_SESSION['pedido_finalizado_' . $idUsuario]);

        header("Location: ./conexao/receber_pedido.php");
    }
}

?>