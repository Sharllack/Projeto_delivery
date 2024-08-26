<?php
include('./protect.php');
include('./conexao.php');

$mesSelecionado = isset($_GET['mes']) ? $_GET['mes'] : 'todos';
$pagamentoSelecionado = isset($_GET['pagamento']) ? $_GET['pagamento'] : 'todos';

$valorTotal = 0;

// Mapeamento dos meses para número de mês
$meses = [
    "janeiro" => "01",
    "fevereiro" => "02",
    "marco" => "03",
    "abril" => "04",
    "maio" => "05",
    "junho" => "06",
    "julho" => "07",
    "agosto" => "08",
    "setembro" => "09",
    "outubro" => "10",
    "novembro" => "11",
    "dezembro" => "12"
];

$result = null; // Inicialize a variável $result para evitar erros
$valorTotal = 0; // Inicializa o valor total das vendas

// Defina a consulta SQL com base na seleção do mês e forma de pagamento
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $query = "SELECT * FROM historicopedidos WHERE 1=1"; // Inicializa a consulta

    if ($mesSelecionado !== "todos") {
        if (!array_key_exists($mesSelecionado, $meses)) {
            echo "Mês selecionado é inválido.";
            exit();
        }

        $mesNumero = $meses[$mesSelecionado];
        $query .= " AND DATE_FORMAT(dataHora, '%m') = ?";
    }

    if ($pagamentoSelecionado !== "todos") {
        $query .= " AND pagamento = ?";
    }

    // Preparar e executar a consulta
    if ($stmt = $mysqli->prepare($query)) {
        $types = '';
        $params = [];

        if ($mesSelecionado !== "todos") {
            $types .= 's';
            $params[] = $mesNumero;
        }

        if ($pagamentoSelecionado !== "todos") {
            $types .= 's';
            $params[] = $pagamentoSelecionado;
        }

        if ($types) {
            $stmt->bind_param($types, ...$params);
        }

        $stmt->execute();
        $result = $stmt->get_result();
        $stmt->close();
    } else {
        echo "Erro ao preparar a consulta: " . $mysqli->error;
    }
}

if(isset($_GET['deletar'])) {
    $idPedido = intval($_GET['deletar']);
    $stmt = $mysqli->prepare("DELETE FROM historicopedidos WHERE idPedido = ?");
    $stmt->bind_param('i', $idPedido);
    $stmt->execute();
    $stmt->close();

    header("Location: ./historico.php");
}

if(isset($_GET['limpar'])) {
    $stmt = $mysqli->prepare("DELETE FROM historicopedidos");
    $stmt->execute();
    $stmt->close();

    header("Location: ./historico.php");
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../estilo_historico/style.css">
    <link rel="stylesheet" href="../estilo_historico/media_query.css">
    <title>Histórico</title>
</head>
<body>
    <div class="voltar">
        <a href="./receber_pedido.php">Voltar</a>
    </div>
    <div class="limparhist">
        <a href="./historico.php?limpar" class="del">Limpar Histórico</a>
    </div>
    <header>
        <h1>Histórico de Pedidos</h1>
        <div class="periodo">
            <form action="" method="get">
                <select name="mes" id="mes">
                    <option value="todos" <?= ($mesSelecionado === 'todos') ? 'selected' : '' ?>>Todos</option>
                    <?php
                    // Gerar opções com base no mapeamento dos meses
                    foreach ($meses as $nomeMes => $numeroMes) {
                        $selected = ($mesSelecionado === $nomeMes) ? 'selected' : '';
                        echo "<option value=\"$nomeMes\" $selected>" . ucfirst($nomeMes) . "</option>";
                    }
                    ?>
                </select>

                <select name="pagamento" id="pagamento">
                    <option value="todos" <?= ($pagamentoSelecionado === 'todos') ? 'selected' : '' ?>>Todas as formas</option>
                    <option value="cartão" <?= ($pagamentoSelecionado === 'cartão') ? 'selected' : '' ?>>Cartão</option>
                    <option value="pix" <?= ($pagamentoSelecionado === 'pix') ? 'selected' : '' ?>>Pix</option>
                    <option value="dinheiro" <?= ($pagamentoSelecionado === 'dinheiro') ? 'selected' : '' ?>>Dinheiro</option>
                </select>

                <button type="submit">Filtrar</button>
            </form>
        </div>
    </header>

    <main>
        <section>
            <h1 class="ped">Pedidos</h1>
            <?php
            if ($result && $result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
            ?>
            <div class="table-container">
                <table>
                    <thead>
                        <tr>
                            <th>Nº Pedido</th>
                            <th>Cliente</th>
                            <th>Data/Hora</th>
                            <th>Pedido</th>
                            <th>Pagamento</th>
                            <th>Total</th>
                            <th>Excluir</th>
                        </tr>
                    </thead>
                    <tbody>
                            <?php
                                $total = $row['valorTotal'];
                                $nomeUsu = $row['idUsuario'];
                                $idProdutos = $row['idProdutos'];
                                $quantidades = $row['qtd'];

                                // Atualiza o valor total das vendas
                                $valorTotal += $total;

                                // Separar IDs e quantidades
                                $ids = explode(',', $idProdutos);
                                $qtds = explode(',', $quantidades);
                            ?>
                            <tr>
                                <td>
                                    <?php echo $row['idPedido']; ?>
                                </td>
                                <td>
                                    <?php
                                        // Obter o nome completo do cliente
                                        $stmt = $mysqli->prepare("SELECT pnome, sobrenome FROM usuarios WHERE idUsuarios = ?");
                                        $stmt->bind_param('i', $nomeUsu);
                                        $stmt->execute();
                                        $stmt->bind_result($nome, $sobrenome);
                                        $stmt->fetch();
                                        $stmt->close();
                                        echo $nome . ' ' . $sobrenome;
                                    ?>
                                </td>
                                <td>
                                    <?php echo date("d/m/Y H:i", strtotime($row['dataHora']))?>
                                </td>
                                <td>
                                    <?php
                                        // Exibir os nomes dos produtos e suas quantidades
                                        for ($i = 0; $i < count($ids); $i++) {
                                            $id = $ids[$i];
                                            $qtd = $qtds[$i];

                                            $stmt = $mysqli->prepare("SELECT nome FROM produtos WHERE idProdutos = ?");
                                            $stmt->bind_param("i", $id);
                                            $stmt->execute();
                                            $stmt->bind_result($nomeProduto);
                                            $stmt->fetch();
                                            echo $qtd . " X " . $nomeProduto . "<br>";
                                            $stmt->close();
                                        }
                                    ?>
                                </td>
                                <td>
                                    <?php echo $row['pagamento']?>
                                </td>
                                <td>
                                    <?php echo "R$" . number_format($total, 2, "," , ".") . "<br>"?>
                                </td>
                                <td>
                                    <a href="./historico.php?deletar=<?php echo $row['idPedido']?>" class="del">Deletar</a>
                                </td>
                            </tr>
                    </tbody>
                </table>
            </div><br>
            <?php
                }
            } else {
                echo "<p>Nenhum pedido encontrado.</p>";
            }
            ?>
        </section>

        <section>
            <h1>Financeiro</h1>
            <table>
                <thead>
                    <tr>
                        <th>Quantidade</th>
                        <th>Produtos</th>
                        <th>Valor Total</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Obter informações dos produtos para exibição
                    $produtosQuantidades = [];
                    
                    if ($result && $result->num_rows > 0) {
                        // Resetar o ponteiro do resultado para percorrer novamente
                        $result->data_seek(0);

                        while ($row = $result->fetch_assoc()) {
                            $idProdutos = $row['idProdutos'];
                            $quantidades = $row['qtd']; // Assumindo que o campo 'qtd' contém as quantidades

                            // Separar IDs e quantidades
                            $ids = explode(',', $idProdutos);
                            $qtds = explode(',', $quantidades);

                            // Agregar quantidades de produtos
                            for ($i = 0; $i < count($ids); $i++) {
                                $id = $ids[$i];
                                $qtd = $qtds[$i];

                                if (!isset($produtosQuantidades[$id])) {
                                    $produtosQuantidades[$id] = 0;
                                }

                                $produtosQuantidades[$id] += $qtd;
                            }
                        }
                    }

                    // Exibir quantidade de cada produto e o nome do produto
                    foreach ($produtosQuantidades as $id => $quantidade) {
                        $stmt = $mysqli->prepare("SELECT nome FROM produtos WHERE idProdutos = ?");
                        $stmt->bind_param("i", $id);
                        $stmt->execute();
                        $stmt->bind_result($nomeProduto);
                        $stmt->fetch();
                        $stmt->close();
                        
                        echo "<tr>";
                        echo "<td>" . $quantidade . "</td>";
                        echo "<td>" . $nomeProduto . "</td>";
                        echo "<td></td>"; // Este valor pode ser calculado separadamente se necessário
                        echo "</tr>";
                    }
                    ?>
                    <tr>
                        <td colspan="2">Total de Vendas</td>
                        <td><?php echo "R$ " . number_format($valorTotal, 2, ",", "."); ?></td>
                    </tr>
                </tbody>
            </table>
        </section>
    </main>
</body>
</html>
