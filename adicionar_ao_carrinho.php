<?php

include('./conexao/conexao.php');

if(!isset($_SESSION)) {
    session_name("user_session");
    session_start();
}

$idUsuario = $_SESSION['idUsuario'];

if(isset($_GET['adicionar'])) {
    // Lógica para inserir ou atualizar o produto no carrinho
    $idProduto = intval($_GET['adicionar']);
    $observacao = $_POST['obs'];
    $valor = $_POST['valor'];

    // Verifica se já existe um carrinho ativo para o usuário
    $stmt = $mysqli->prepare("SELECT idCarrinho FROM carrinho WHERE idUsuario = ?");
    $stmt->bind_param("i", $idUsuario);
    $stmt->execute();
    $stmt->bind_result($idCarrinho);
    $stmt->fetch();
    $stmt->close();

    // Se não existe, cria um novo carrinho
    if(!$idCarrinho) {
        $stmt = $mysqli->prepare("INSERT INTO carrinho (idUsuario) VALUES (?)");
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        $idCarrinho = $stmt->insert_id;  // Obtém o ID do carrinho recém-criado
        $stmt->close();
    }

    $stmt = $mysqli->prepare("INSERT INTO itenscarrinho (idProduto, obs, idUsuario, precouni, idCarrinho) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("isids", $idProduto, $observacao, $idUsuario, $valor, $idCarrinho,);
    $stmt->execute();
    $stmt->close();

    header("Location: ./index.php");
    exit();
}



?>