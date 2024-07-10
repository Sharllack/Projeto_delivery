<?php


include('./conexao/conexao.php');

if(!isset($_SESSION)) {
    session_start();
}

if(isset($_GET['finalizar'])) {
    $idProdutos = intval($_POST['idProduto']);
    $idUsuario = $_SESSION['idUsuario'];
    $quantidade = intval($_POST['qtd_' . $idProdutos]);

    // Prepare para atualizar as quantidades
    $stmt = $mysqli->prepare("UPDATE itenscarrinho SET qtd = ? WHERE idProduto = ? AND idUsuario = ?");

    foreach($_POST['quantidade'] as $idProduto => $quantidade) {
        // Certifique-se de que os valores são inteiros
        $idProduto = intval($idProduto);
        $quantidade = intval($quantidade);

        // Atualize a quantidade no banco de dados
        $stmt->bind_param("iii", $quantidade, $idProduto, $idUsuario);
        $stmt->execute();
    }

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

    $stmt = $mysqli->prepare("SELECT GROUP_CONCAT(idProduto SEPARATOR ', ') AS produtos, GROUP_CONCAT(qtd SEPARATOR ', ') AS quantidade, GROUP_CONCAT(precouni SEPARATOR ', ') AS precouni  FROM itenscarrinho WHERE idUsuario = ?");
    $stmt->bind_param("i", $idUsuario);
    $stmt->execute();
    $stmt->bind_result($produtos_conca, $qtd_conca, $preco_conca,);
    $stmt->fetch();
    $stmt->close();

    // Verifica se o produto já está no carrinho
    $stmt = $mysqli->prepare("SELECT produtos_concatenados FROM carrinho WHERE idUsuario = ?");
    $stmt->bind_param("i", $idUsuario);
    $stmt->execute();
    $stmt->bind_result($produtos_concatenados);
    $stmt->fetch();
    $stmt->close();

    $stmt = $mysqli->prepare("UPDATE carrinho SET produtos_concatenados = ?, preco_conc = ?, qtd_cont = ? WHERE idUsuario = ?");
    $stmt->bind_param("sssi", $produtos_conca, $preco_conca, $qtd_conca, $idUsuario);
    $stmt->execute();
    $stmt->close();

    header("Location: ./pagamento_endereco.php");
    exit();
}


?>