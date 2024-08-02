<?php 

include('./conexao/conexao.php');

if(!isset($_SESSION)) {
    session_start();
}

if(isset($_GET['finalizar'])) {
    $idCarrinho = intval($_GET['finalizar']);
    $pagamento = $_POST['opcoes'];
    $entrega = $_POST['opcEntrega'];
    $troco = $_POST['troco'];
    $total = $_POST['button'];

    $usuario = $_SESSION['idUsuario'];

    $sql_query = "SELECT * FROM itenscarrinho
            JOIN carrinho ON itenscarrinho.idCarrinho = carrinho.idCarrinho
            JOIN produtos ON itenscarrinho.idProduto = produtos.idProdutos
            JOIN usuarios ON itenscarrinho.idUsuario = usuarios.idUsuarios
            WHERE idUsuarios = $usuario";
    $result = $mysqli->query($sql_query) or die ($mysqli->error);

    while($row = mysqli_fetch_assoc($result)) {
        $idProduto = $row['idProdutos'];
        $idItens = $row['idItens'];
        $idCarrinho = $row['idCarrinho'];
        $idUsuario = $row['idUsuarios'];
    };

    $situacao = 'Aguardando a confirmação do restaurante!';
    $situ = 'Aguardando!';

    $stmt = $mysqli->prepare("UPDATE carrinho SET valorTotal = ? WHERE idCarrinho = ?");
    $stmt->bind_param('di', $total, $idCarrinho);
    $stmt->execute();
    $stmt->close();

    $stmt = $mysqli->prepare("INSERT INTO pedidos (idProduto, idUsuario, idCarrinho, idItens, pagamento, troco, situacao, situ, entrega) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iiiisdsss", $idProduto, $idUsuario, $idCarrinho, $idItens, $pagamento, $troco, $situacao, $situ, $entrega);
    $stmt->execute();
    $stmt->close();

    $_SESSION['pedido_finalizado'][$idCarrinho] = true;

    header('Location: ./situacao_pedido.php?idUsuario=' . $idUsuario);
    exit();

}

?>