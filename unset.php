<?php 

include('./conexao/conexao.php');

if(!isset($_SESSION)) {
    session_start();
}

if(isset($_GET['idPedido'])) {
    $idPedido = intval($_GET['idPedido']);

    // Verifica se já existe um carrinho ativo para o usuário
    $stmt = $mysqli->prepare("SELECT idCarrinho FROM pedidos WHERE idPedido = ?");
    $stmt->bind_param("i", $idPedido);
    $stmt->execute();
    $stmt->bind_result($idCarrinho);
    $stmt->fetch();
    $stmt->close();

    // Limpe a variável de sessão específica para este usuário, se existir
    if (isset($_SESSION['pedido_finalizado'][$idCarrinho])) {
        unset($_SESSION['pedido_finalizado'][$idCarrinho]);
    }

    // Redirecionamento após limpar a variável de sessão
    header("Location: ./conexao/receber_pedido.php");
    exit();
}

?>