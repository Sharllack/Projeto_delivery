<?php

include('./conexao/conexao.php');

if(isset($_GET['idPedido'])) {
    $idPedido = intval($_GET['idPedido']);

    $stmt = $mysqli->prepare("SELECT idUsuario FROM pedidos WHERE idPedido = ?");
    $stmt->bind_param("i", $idPedido);
    $stmt->execute();
    $stmt->bind_result($idUsuario);
    $stmt->fetch();
    $stmt->close();

    $stmt = $mysqli->prepare("DELETE FROM pedidos WHERE idPedido = ?");
    $stmt->bind_param("i", $idPedido);
    $stmt->execute();
    $stmt->close();

    $stmt = $mysqli->prepare("DELETE FROM itenscarrinho WHERE idUsuario = ?");
    $stmt->bind_param("i", $idUsuario);
    $stmt->execute();
    $stmt->close();

    $stmt = $mysqli->prepare("DELETE FROM carrinho WHERE idUsuario = ?");
    $stmt->bind_param("i", $idUsuario);
    $stmt->execute();
    $stmt->close();

    header('Location: ./unset.php?idPedido=' . $idPedido);

}

?>