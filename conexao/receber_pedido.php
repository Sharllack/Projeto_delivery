<?php
include('./protect.php');
require_once './conexao.php';

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

    $stmt = $mysqli->prepare("SELECT entrega FROM pedidos WHERE idPedido = ?");
    $stmt->bind_param("i", $idPedido);
    $stmt->execute();
    $stmt->bind_result($entrega);
    $stmt->fetch();
    $stmt->close();

    if($entrega == 'retirada') {
        $situacao = 'Pedido aguardando retirada!';
    } else {
        $situacao = 'O seu pedido já saiu para a entrega!';
    }
    $situ = 'Em Rota!';
    $stmt = $mysqli->prepare("UPDATE pedidos SET situacao = ?, situ = ? WHERE idPedido = ?");
    $stmt->bind_param("ssi", $situacao, $situ, $idPedido);
    $stmt->execute();
    $stmt->close();
}

if(isset($_GET['finalizar'])) {
    $idPedido = intval($_GET['finalizar']);
    $situacao = 'O seu pedido foi finalizado!';
    $situ = 'Finalizado!';
    $stmt = $mysqli->prepare("UPDATE pedidos SET situacao = ?, situ = ? WHERE idPedido = ?");
    $stmt->bind_param("ssi", $situacao, $situ, $idPedido);
    $stmt->execute();
    $stmt->close();
}

if(isset($_GET['recusar'])) {
    $idPedido = intval($_GET['recusar']);
    $motivo = $_GET['motivo'];
    $situacao = 'O seu pedido não foi aceito!' . '<br> <br>' . ' MOTIVO: ' . $motivo;
    $situ = 'Recusado!';
    $stmt = $mysqli->prepare("UPDATE pedidos SET situacao = ?, situ = ? WHERE idPedido = ?");
    $stmt->bind_param("ssi", $situacao, $situ, $idPedido);
    $stmt->execute();
    $stmt->close();
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
    <title>Receber Pedidos</title>
</head>
<body>
    <div class="filter"></div>
    <div style="position: absolute; left: 15px; margin: 15px 15px 0 0; font-size:1.2em;"><a href="./adicionar_produto.php" style="color: white;">Voltar</a></div>
    <div class="logout" style="position: absolute; right: 0; margin: 15px 15px 0 0; font-size:1.2em;"><a href="./logout.php" style="color: white;">Sair</a></div>
    <header>
        <h1>Pedidos</h1>
        <p>Olá, <?= $_SESSION['user']?>! Vamos Acompanhar os Pedidos!</p>
    </header>
    <main>
    <h1 style="text-align: center;" class="titlePedidos">Pedidos</h1>
    <?php while($row = $result->fetch_assoc()) { ?>
        <table>
            <thead>
                <th>Situacao</th>
                <th>Nº Pedido</th>
                <th>Data/Hora</th>
                <th>Pedido</th>
                <th>Entrega</th>
                <th>Pagamento</th>
                <th>Total</th>
                <th>Cliente</th>
                <th>Contato</th>
                <th>Endereço</th>
                <th>Referência</th>
                <th>Aceitar</th>
                <th>Rota</th>
                <th>Recusar</th>
                <th>Finalizar</th>
            </thead>
            <tbody>
                    <?php

                    $total = $row['valorTotal'];

                    ?>
                    <tr>
                        <td>
                            <?php echo $row['situ']; ?>
                        </td>
                        <td>
                            <?php echo $row['idPedido'];?>
                        </td>
                        <td>
                            <?php echo date("d/m/Y H:i", strtotime($row['dataHora']))?>
                        </td>
                        <td>
                            <?php
                            // Recuperar os IDs dos produtos
                            $ids = explode(',', $row['produtos_concatenados']);

                            // Exibir os nomes dos produtos
                            foreach ($ids as $id) {
                                $stmt = $mysqli->prepare("SELECT qtd, obs FROM itenscarrinho WHERE idProduto = ?");
                                $stmt->bind_param("i", $id);
                                $stmt->execute();
                                $stmt->bind_result($qtd, $observacoes);
                                $stmt->fetch();
                                echo $qtd . "X";
                                echo $observacoes ? "(" . $observacoes . ")" : "";
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
                        <td>
                            <?php echo $row['entrega'];?>
                        </td>
                        <td>
                            <?php echo $row['pagamento']?>
                        </td>
                        <td>
                            <?php echo "R$" . number_format($total, 2, "," , "." . "<br>")?> <?php echo "Troco:" . "R$" . number_format($row['troco'], 2, "," , ".")?>
                        </td>
                        <td>
                            <?php echo $row['pnome']?> <?php echo $row[ 'sobrenome']?>
                        </td>
                        <td>
                            <?php echo $row['cell']?>
                        </td>
                        <td>
                            <?php echo $row['rua'] . ', ' . $row['numero'] . ', ' . $row['complemento']?>
                        </td>
                        <td>
                            <?php echo $row['referencia']; ?>
                        </td>
                        <td>
                            <a href="./receber_pedido.php?preparando=<?php echo $row['idPedido'] ?>" class="verde">Aceitar</a>
                        </td>
                        <td>
                            <a href="./receber_pedido.php?rota=<?php echo $row['idPedido'] ?>" class="verde">Rota</a>
                        </td>
                        <td>
                            <button class="recusar vermelho" data-id="<?php echo $row['idPedido'] ?>">Recusar</button>
                        </td>
                        <td>
                            <a href="./receber_pedido.php?finalizar=<?php echo $row['idPedido'] ?>" class="vermelho">Finalizar</a>
                        </td>
                    </tr>
            </tbody>
        </table><br>
        <?php } ?>
    </main>
    <div class="motivo" style="display:none;">
        <h1 style="text-align: center;">Motivo</h1>
        <input type="text" name="motivo" id="motivo" placeholder="motivo">
        <button onclick="enviarMotivo()" style="width: 100%;">Enviar</button>
    </div>
    <script>
        setInterval(function() {
            location.reload();
        }, 30000);

        document.querySelectorAll('.recusar').forEach(button => {
            button.addEventListener('click', function() {
                const filter = document.querySelector('.filter');
                const motivoDiv = document.querySelector('.motivo');
                motivoDiv.style.display = 'block';
                filter.style.display = 'block';
                motivoDiv.dataset.idPedido = this.dataset.id;
                motivoDiv.classList.add('animated');
            });
        });

        document.querySelector('.filter').addEventListener('click', function(){
            const filter = document.querySelector('.filter');
            const motivoDiv = document.querySelector('.motivo');
            motivoDiv.style.display = 'none';
            filter.style.display = 'none';
        })

        function enviarMotivo() {
            const motivo = document.querySelector('#motivo').value;
            const idPedido = document.querySelector('.motivo').dataset.idPedido;
            window.location.href = './receber_pedido.php?recusar=' + idPedido + '&motivo=' + encodeURIComponent(motivo);
        }
    </script>
</body>
</html>
