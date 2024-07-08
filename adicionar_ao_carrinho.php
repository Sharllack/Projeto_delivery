<?php
if(!isset($_SESSION)){
    session_name('user_session');
    session_start();
}

include('./conexao/conexao.php');

if(isset($_SESSION['user'])) {
    $idUsuario = $_SESSION['idUsuario'];

    if(isset($_GET['adicionar'])) {
        $idProduto = intval($_GET['adicionar']);
        $observacao = $_POST['obs'];
        $quantidade = $_POST['qtd'];
        $valor = $_POST['valor'];

        // Verifica se já existe um carrinho para o usuário
        $sql_carrinho = "SELECT idCarrinho FROM carrinho WHERE idUsuario = ?";
        $stmt_carrinho = $mysqli->prepare($sql_carrinho);
        $stmt_carrinho->bind_param("i", $idUsuario);
        $stmt_carrinho->execute();
        $stmt_carrinho->bind_result($idCarrinho);
        $stmt_carrinho->fetch();
        $stmt_carrinho->close();

        // Se não existe, cria um novo carrinho
        if(!$idCarrinho) {
            $stmt_novo_carrinho = $mysqli->prepare("INSERT INTO carrinho (idUsuario) VALUES (?)");
            $stmt_novo_carrinho->bind_param("i", $idUsuario);
            $stmt_novo_carrinho->execute();
            $idCarrinho = $stmt_novo_carrinho->insert_id;
            $stmt_novo_carrinho->close();
        }

        // Insere o produto no carrinho junto com a observação
        $stmt = $mysqli->prepare("INSERT INTO itenscarrinho (qtd, idProduto, obs, idUsuario, idCarrinho, precouni) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("iisiid", $quantidade, $idProduto, $observacao, $idUsuario, $idCarrinho, $valor);
        $stmt->execute();
        $stmt->close();

        header("Location: ./index.php");
        exit();
    }
}
?>
