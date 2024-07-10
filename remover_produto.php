<?php

include('./conexao/conexao.php');

if(!isset($_SESSION)) {
    session_start();
}

$idUsuario = $_SESSION['idUsuario'];

if(isset($_GET['remover'])) {
    $idProduto = intval($_GET['remover']);

    $sql_select_carrinho = "SELECT idCarrinho FROM itenscarrinho WHERE idUsuario = $idUsuario";
    $select_check = $mysqli->prepare($sql_select_carrinho);
    $select_check->execute();
    $select_check->bind_result($idCarrinho);
    $select_check->fetch();
    $select_check->close();
    
    // Excluir registros da tabela itensCarrinho
    $sql_delete_itens = "DELETE FROM itenscarrinho WHERE idProduto = ?";
    $stmt_itens = $mysqli->prepare($sql_delete_itens);
    $stmt_itens->bind_param("i", $idProduto);
    $stmt_itens->execute();
    $stmt_itens->close();
    
    // Verifica se o idCarrinho associado aos itens excluídos ainda possui outros itens na tabela itensCarrinho
    $sql_check_carrinho = "SELECT COUNT(*) FROM itenscarrinho WHERE idCarrinho = ?";
    $stmt_check = $mysqli->prepare($sql_check_carrinho);
    $stmt_check->bind_param("i", $idCarrinho);
    $stmt_check->execute();
    $stmt_check->bind_result($count);
    $stmt_check->fetch();
    $stmt_check->close();
    
    // Se não há mais itens associados ao idCarrinho, exclui o registro da tabela carrinho
    if($count == 0) {
        $sql_delete_carrinho = "DELETE FROM carrinho WHERE idCarrinho = ?";
        $stmt_carrinho = $mysqli->prepare($sql_delete_carrinho);
        $stmt_carrinho->bind_param("i", $idCarrinho);
        $stmt_carrinho->execute();
        $stmt_carrinho->close();
    }
}

header('Location: ./carrinho.php');
?>
