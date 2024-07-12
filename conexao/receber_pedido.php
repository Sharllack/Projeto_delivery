<?php
include('./protect.php');
include('./conexao.php');

if(!isset($_SESSION)){
    session_name('admin_session');
    session_start();
}

if(isset($_GET['preparando'])) {
    $idPedido = intval($_GET['preparando']);
    $situacao = 'O seu pedido está sendo preparado!';
    $situ = 'Aceito!';
    $stmt = $mysqli->prepare("UPDATE pedidos SET situacao = ?, situ = ? WHERE idPedido = ?");
    $stmt->bind_param("ssi", $situacao, $situ, $idPedido);
    $stmt->execute();
    $stmt->close();
}

if(isset($_GET['rota'])) {
    $idPedido = intval($_GET['rota']);
    $situacao = 'O seu pedido já saiu para a entrega!';
    $situ = 'Em Rota!';
    $stmt = $mysqli->prepare("UPDATE pedidos SET situacao = ?, situ = ? WHERE idPedido = ?");
    $stmt->bind_param("ssi", $situacao, $situ, $idPedido);
    $stmt->execute();
    $stmt->close();
}

if(isset($_GET['finalizar'])) {
    $idPedido = intval($_GET['finalizar']);
    $situacao = 'O seu pedido foi finalizado!';
    $stmt = $mysqli->prepare("UPDATE pedidos SET situacao = ? WHERE idPedido = ?");
    $stmt->bind_param("si", $situacao, $idPedido);
    $stmt->execute();
    $stmt->close();

    $stmt = $mysqli->prepare("SELECT idUsuario FROM pedidos WHERE idPedido = ?");
    $stmt->bind_param("i", $idPedido);
    $stmt->execute();
    $stmt->bind_result($idUsuario);
    $stmt->fetch();
    $stmt->close();

    $stmt = $mysqli->prepare("DELETE FROM pedidos WHERE idPedido = ?");
    $stmt->bind_param("i", $idPedido);
    $stmt->execute();
    $stmt->close();

    $stmt = $mysqli->prepare("DELETE FROM itenscarrinho WHERE idUsuario = ?");
    $stmt->bind_param("i", $idUsuario);
    $stmt->execute();
    $stmt->close();

    $stmt = $mysqli->prepare("DELETE FROM carrinho WHERE idUsuario = ?");
    $stmt->bind_param("i", $idUsuario);
    $stmt->execute();
    $stmt->close();

    header('Location: ../unset.php?idPedido=' . $idPedido);
}

if(isset($_GET['recusar'])) {
    $idPedido = intval($_GET['recusar']);
    $situacao = 'O seu pedido não foi aceito!';
    $stmt = $mysqli->prepare("UPDATE pedidos SET situacao = ? WHERE idPedido = ?");
    $stmt->bind_param("si", $situacao, $idPedido);
    $stmt->execute();
    $stmt->close();

    $stmt = $mysqli->prepare("SELECT idUsuario FROM pedidos WHERE idPedido = ?");
    $stmt->bind_param("i", $idPedido);
    $stmt->execute();
    $stmt->bind_result($idUsuario);
    $stmt->fetch();
    $stmt->close();

    $stmt = $mysqli->prepare("DELETE FROM pedidos WHERE idPedido = ?");
    $stmt->bind_param("i", $idPedido);
    $stmt->execute();
    $stmt->close();

    $stmt = $mysqli->prepare("DELETE FROM itenscarrinho WHERE idUsuario = ?");
    $stmt->bind_param("i", $idUsuario);
    $stmt->execute();
    $stmt->close();

    $stmt = $mysqli->prepare("DELETE FROM carrinho WHERE idUsuario = ?");
    $stmt->bind_param("i", $idUsuario);
    $stmt->execute();
    $stmt->close();

    header('Location: ../unset.php?idPedido=' . $idPedido);
}

$sql_query = "SELECT * FROM pedidos
            JOIN carrinho ON pedidos.idCarrinho = carrinho.idCarrinho
            JOIN produtos ON pedidos.idProduto = produtos.idProdutos
            JOIN usuarios ON pedidos.idUsuario = usuarios.idUsuarios
            JOIN itenscarrinho ON pedidos.idItens = itenscarrinho.idItens";
$result = $mysqli->query($sql_query) or die ($mysqli->error);

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../estilo_receber/style.css">
    <link rel="stylesheet" href="../estilo_receber/media_query.css">
    <title>Adicionar Produtos</title>
</head>
<body>
    <div style="position: absolute; left: 15px; margin: 15px 15px 0 0; font-size:1.2em;"><a href="./adicionar_produto.php" style="color: white;">Voltar</a></div>
    <div class="logout" style="position: absolute; right: 0; margin: 15px 15px 0 0; font-size:1.2em;"><a href="./logout.php" style="color: white;">Sair</a></div>
    <header>
        <h1>Pedidos</h1>
        <p>Olá, <?= $_SESSION['user']?>! Vamos Acompanhar os Pedidos!</p>
    </header>
    <main>
        <table>
            <h1 style="text-align: center;" class="titlePedidos">Pedidos</h1>
            <thead>
                <th>Situacao</th>
                <th>Nº Pedido</th>
                <th>Data/Hora</th>
                <th>Pedido</th>
                <th>Observação</th>
                <th>Pagamento</th>
                <th>Total</th>
                <th>Cliente</th>
                <th>Contato</th>
                <th>Endereço</th>
                <th>Referência</th>
                <th>Aceitar</th>
                <th>Rota</th>
                <th>Finalizar</th>
                <th>Recusar</th>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><?php echo $row['situ']; ?></td>
                        <td><?php echo $row['idPedido'];?></td>
                        <td><?php echo date("d/m/Y H:i", strtotime($row['dataHora']))?></td>
                        <td>
                            <?php 
                            // Recuperar os IDs dos produtos
                            $ids = explode(',', $row['produtos_concatenados']);
                            
                            // Exibir os nomes dos produtos
                            foreach ($ids as $id) {
                                $stmt = $mysqli->prepare("SELECT qtd FROM itenscarrinho WHERE idProduto = ?");
                                $stmt->bind_param("i", $id);
                                $stmt->execute();
                                $stmt->bind_result($qtd);
                                $stmt->fetch();
                                echo $qtd . "X";
                                $stmt->close();

                                $stmt = $mysqli->prepare("SELECT nome FROM produtos WHERE idProdutos = ?");
                                $stmt->bind_param("i", $id);
                                $stmt->execute();
                                $stmt->bind_result($nomeProduto);
                                $stmt->fetch();
                                echo $nomeProduto . "<br>";
                                $stmt->close();
                            }
                            ?>
                        </td>
                        <td><?php echo $row['obs'];?></td>
                        <td><?php echo $row['pagamento']?></td>
                        <td><?php echo "R$" . number_format($row['valorTotal'], 2, "," , "." . "<br>")?> <?php echo "Troco:" . "R$" . number_format($row['troco'], 2, "," , ".")?></td>
                        <td><?php echo $row['nomeCliente']?></td>
                        <td><?php echo $row['cell']?></td>
                        <td><?php echo $row['rua'] . ',' . $row['numero']?></td>
                        <td><?php echo $row['referencia']; ?></td>
                        <td><a href="./receber_pedido.php?preparando=<?php echo $row['idPedido'] ?>" class="verde">Aceitar</a></td>
                        <td><a href="./receber_pedido.php?rota=<?php echo $row['idPedido'] ?>" class="verde">Rota</a></td>
                        <td><a href="./receber_pedido.php?finalizar=<?php echo $row['idPedido'] ?>" class="vermelho">Finalizar</a></td>
                        <td><a href="./receber_pedido.php?recusar=<?php echo $row['idPedido'] ?>" class="vermelho">Recusar</a></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </main>
    <script>
        setInterval(function() {
            location.reload();
        }, 10000);
    </script>
</body>
</html>
