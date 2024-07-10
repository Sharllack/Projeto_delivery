<?php 

include('./conexao/conexao.php');

if(!isset($_SESSION)) {
    session_name('user_session');
    session_start();
}

if(isset($_GET['finalizar'])) {
    $idCarrinho = intval($_GET['finalizar']);
    $pagamento = $_POST['opcoes'];

    $sql_query = "SELECT * FROM itenscarrinho
            JOIN carrinho ON itenscarrinho.idCarrinho = carrinho.idCarrinho
            JOIN produtos ON itenscarrinho.idProduto = produtos.idProdutos
            JOIN usuarios ON itenscarrinho.idUsuario = usuarios.idUsuarios";
    $result = $mysqli->query($sql_query) or die ($mysqli->error);

    while($row = mysqli_fetch_assoc($result)) {
        $idProduto = $row['idProdutos'];
        $idItens = $row['idItens'];
        $idCarrinho = $row['idCarrinho'];
        $idUsuario = $row['idUsuarios'];
    }

    $stmt = $mysqli->prepare("INSERT INTO pedidos (idProduto, idUsuario, idCarrinho, idItens, pagamento) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iiiis", $idProduto, $idUsuario, $idCarrinho, $idItens, $pagamento);
    $stmt->execute();
    $stmt->close();

    $_SESSION['pedido_finalizado'][$_SESSION['idUsuario']] = true;

    header('Location: ./situacao_pedido.php');
    exit();

}

?>