<?php

if(!isset($_SESSION)){
    session_name('user_session');
    session_start();
}

include('./conexao/conexao.php');

if(isset($_SESSION['user'])) {
    if(isset($_GET['adicionar'])) {
        $idUsuario = $_SESSION['idUsuario'];
        $idProduto = intval($_GET['adicionar']);
        $observacao = $_POST['obs'];
        $quantidade = $_POST['qtd'];
        

        // Insere o produto no carrinho junto com a observação
        $stmt = $mysqli->prepare("INSERT INTO carrinho (quantidade, idProdutos, obs, idUsuario) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("iisi", $quantidade, $idProduto, $observacao, $idUsuario);
        $stmt->execute();
        $stmt->close();

        header("Location: ./index.php");
        exit();
    }
}
?>