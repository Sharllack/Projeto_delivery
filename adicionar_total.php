<?php

include('./conexao/conexao.php');

if(isset($_GET['finalizar'])) {
    $protocolo = intval($_GET['finalizar']);
    $valorTotal = $_POST['valor_produto'];
    $quantidade = $_POST['qtd'];

    $stmti = $mysqli->prepare("UPDATE carrinho SET quantidade=?, valorTotal=?");
    $stmti->bind_param("id", $quantidade, $valorTotal);
    $stmti->execute();
    $stmti->close();

    header("Location: ./pagamento_endereco.php");
    exit();
}

?>