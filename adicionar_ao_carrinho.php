<?php

include('./conexao/conexao.php');

if(!isset($_SESSION)) {
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

    $sql_prod = "SELECT * FROM itenscarrinho WHERE idProduto = '$idProduto' AND idUsuario = '$idUsuario' LIMIT 1";
    $result_prod = $mysqli->query($sql_prod);

    if($result_prod->num_rows > 0) {
        $row = $result_prod->fetch_assoc();
        $quantidadeAtual = $row['qtd'];

        $novaQtd = $quantidadeAtual + 1;

        // Formata a observação
        $obsConc = $observacao ? "1 "  . $observacao . "<br>" . ($row['obs'] ? " " . $row['obs'] : "") : $row['obs'];

        $stmt = $mysqli->prepare("UPDATE itenscarrinho SET qtd = ?, obs = ? WHERE idProduto = ? AND idUsuario = ?");
        $stmt->bind_param("issi", $novaQtd, $obsConc, $idProduto, $idUsuario);
        $stmt->execute();
        $stmt->close();

    } else {
        // Formata a observação
        $obs = $observacao ? '1 ' . $observacao : '';

        $stmt = $mysqli->prepare("INSERT INTO itenscarrinho (idProduto, obs, idUsuario, precouni, idCarrinho) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("isidi", $idProduto, $obs, $idUsuario, $valor, $idCarrinho);
        $stmt->execute();
        $stmt->close();
    }

    header("Location: ./index.php");
    exit();
}
?>