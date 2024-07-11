<?php 

include('./conexao/conexao.php');

if(!isset($_SESSION)) {
    session_start();
}

if(isset($_GET['idPedido'])) {
    $idPedido = intval($_GET['idPedido']);

    // Limpe a variável de sessão específica para este usuário, se existir
    if (isset($_SESSION['pedido_finalizado'][$_SESSION['idUsuario']])) {
        unset($_SESSION['pedido_finalizado'][$_SESSION['idUsuario']]);
    }

    // Redirecionamento após limpar a variável de sessão
    header("Location: ./conexao/receber_pedido.php");
    exit();
}

?>