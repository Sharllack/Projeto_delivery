<?php

include('./conexao/conexao.php');

if (!isset($_SESSION)) {
    session_start();
}

if (isset($_GET['finalizar'])) {
    $idCarrinho = intval($_GET['finalizar']);
    $pagamento = $_POST['opcoes'];
    $entrega = $_POST['opcEntrega'];
    $troco = $_POST['troco'];
    $total = $_POST['button'];

    $usuario = $_SESSION['idUsuario'];

    // Consultar os itens do carrinho
    $sql_query = "SELECT * FROM itenscarrinho
                  JOIN carrinho ON itenscarrinho.idCarrinho = carrinho.idCarrinho
                  JOIN produtos ON itenscarrinho.idProduto = produtos.idProdutos
                  JOIN usuarios ON itenscarrinho.idUsuario = usuarios.idUsuarios
                  WHERE idUsuarios = ?";

    $stmt = $mysqli->prepare($sql_query);
    $stmt->bind_param('i', $usuario);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $idProduto = $row['idProdutos'];
        $idItens = $row['idItens'];
        $idCarrinho = $row['idCarrinho'];
        $idUsuario = $row['idUsuarios'];
    }

    $situacao = 'Aguardando a confirmação do restaurante!';
    $situ = 'Aguardando!';

    // Atualizar o valor total do carrinho
    $stmt = $mysqli->prepare("UPDATE carrinho SET valorTotal = ? WHERE idCarrinho = ?");
    $stmt->bind_param('di', $total, $idCarrinho);
    $stmt->execute();
    $stmt->close();

    // Inserir pedido
    $stmt = $mysqli->prepare("INSERT INTO pedidos (idProduto, idUsuario, idCarrinho, idItens, pagamento, troco, situacao, situ, entrega) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iiiisdsss", $idProduto, $idUsuario, $idCarrinho, $idItens, $pagamento, $troco, $situacao, $situ, $entrega);
    $stmt->execute();
    $idPedido = $stmt->insert_id;
    $stmt->close();

    // Recuperar IDs dos produtos concatenados
    $stmt = $mysqli->prepare("SELECT GROUP_CONCAT(idProduto SEPARATOR ', ') AS produtos, GROUP_CONCAT(qtd SEPARATOR ', ') AS quantidade FROM itenscarrinho WHERE idUsuario = ?");
    $stmt->bind_param('i', $usuario);
    $stmt->execute();
    $stmt->bind_result($idconc, $qtd_conca);
    $stmt->fetch(); // Importante para obter o resultado
    $stmt->close();

    // Inserir no histórico de pedidos
    $stmt = $mysqli->prepare("INSERT INTO historicopedidos (idPedido, idUsuario, idProdutos, valorTotal, pagamento, qtd) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iisdss", $idPedido, $usuario, $idconc, $total, $pagamento, $qtd_conca);
    $stmt->execute();
    $stmt->close();

    $_SESSION['pedido_finalizado'][$idCarrinho] = true;

    header('Location: ./situacao_pedido.php?idUsuario=' . $usuario);
    exit();
}

?>
