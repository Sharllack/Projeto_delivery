<?php

if(!isset($_SESSION)) {
    session_name("user_session");
    session_start();
}

include('./conexao/conexao.php');

if(isset($_GET['finalizar'])) {
    $idProdutos = intval($_GET['finalizar']);
    $idUsuario = $_SESSION['idUsuario'];
    $quantidade = intval($_POST['qtd']);

    $stmt = $mysqli->prepare("UPDATE itenscarrinho SET qtd = ? WHERE idProduto = ? AND idUsuario = ?");
    $stmt->bind_param("iii", $quantidade, $idProdutos, $idUsuario);
    $stmt->execute();
    $stmt->close();

    // Calcula o valor total do carrinho
    $sql = "SELECT SUM(qtd * precouni) AS total FROM itenscarrinho WHERE idUsuario = ?";
    $stmt = $mysqli->prepare($sql);
    $stmt->bind_param("i", $idUsuario);
    $stmt->execute();
    $stmt->bind_result($valorTotal);
    $stmt->fetch();
    $stmt->close();

    // Atualiza o valor total no carrinho
    $stmt = $mysqli->prepare("UPDATE carrinho SET valorTotal = ? WHERE idUsuario = ?");
    $stmt->bind_param("di", $valorTotal, $idUsuario);
    $stmt->execute();
    $stmt->close();

    $stmt = $mysqli->prepare("SELECT precouni FROM itenscarrinho WHERE idUsuario = ?");
    $stmt->bind_param("i", $idUsuario);
    $stmt->execute();
    $stmt->bind_result($valor);
    $stmt->fetch();
    $stmt->close();

    // Verifica se o produto já está no carrinho
    $stmt = $mysqli->prepare("SELECT produtos_concatenados, preco_conc, qtd_cont FROM carrinho WHERE idUsuario = ?");
    $stmt->bind_param("i", $idUsuario);
    $stmt->execute();
    $stmt->bind_result($produtos_concatenados, $preco_conc, $qtd_conc);
    $stmt->fetch();
    $stmt->close();

    if($produtos_concatenados === null) {
        // Se o produto já estiver no carrinho, atualiza apenas a coluna produtos_concatenados
        $novos_produtos_concatenados = $produtos_concatenados . ", " . $idProdutos;
        $novas_qtds_concatenados = $qtd_conc . ", " . $quantidade;
        $novos_precos_conc = $preco_conc . ", " . $valor;

        $stmt = $mysqli->prepare("UPDATE carrinho SET produtos_concatenados = ?, preco_conc = ?, qtd_cont = ? WHERE idUsuario = ?");
        $stmt->bind_param("sssi", $novos_produtos_concatenados, $novos_precos_conc, $novas_qtds_concatenados, $idUsuario);
        $stmt->execute();
        $stmt->close();
    }

    header("Location: ./pagamento_endereco.php");
    exit();
}


?>