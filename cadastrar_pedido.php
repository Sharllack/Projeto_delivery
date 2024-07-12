<?php 

include('./conexao/conexao.php');

if(!isset($_SESSION)) {
    session_start();
}

if(isset($_GET['finalizar'])) {
    $idCarrinho = intval($_GET['finalizar']);
    $pagamento = $_POST['opcoes'];
    $troco = $_POST['troco'];

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

    $stmt = $mysqli->prepare("INSERT INTO pedidos (idProduto, idUsuario, idCarrinho, idItens, pagamento, troco, situacao, situ, taxa) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("iiiisdssd", $idProduto, $idUsuario, $idCarrinho, $idItens, $pagamento, $troco, $situacao, $situ, $_SESSION['taxa']);
    $stmt->execute();
    $stmt->close();

    $_SESSION['pedido_finalizado'][$idCarrinho] = true;

    header('Location: ./situacao_pedido.php?idUsuario=' . $idUsuario);
    exit();

}

?>