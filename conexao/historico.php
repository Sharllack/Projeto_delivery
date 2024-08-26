<?php
include('./protect.php');
include('./conexao.php');

// Variáveis de filtro
$mesSelecionado = isset($_GET['mes']) ? $_GET['mes'] : 'todos';
$pagamentoSelecionado = isset($_GET['pagamento']) ? $_GET['pagamento'] : 'todos';
$classificacaoSelecionada = isset($_GET['classificacao']) ? $_GET['classificacao'] : 'todos';

// Mapeamento dos meses
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

// Consultar pedidos
$queryPedidos = "SELECT * FROM historicopedidos WHERE 1=1";
$paramsPedidos = [];
$typesPedidos = '';

// Condições para pedidos
if ($mesSelecionado !== "todos") {
    if (!array_key_exists($mesSelecionado, $meses)) {
        echo "Mês selecionado é inválido.";
        exit();
    }
    $mesNumero = $meses[$mesSelecionado];
    $queryPedidos .= " AND DATE_FORMAT(dataHora, '%m') = ?";
    $paramsPedidos[] = $mesNumero;
    $typesPedidos .= 's';
}

if ($pagamentoSelecionado !== "todos") {
    $queryPedidos .= " AND pagamento = ?";
    $paramsPedidos[] = $pagamentoSelecionado;
    $typesPedidos .= 's';
}

// Preparar e executar a consulta para pedidos
if ($stmt = $mysqli->prepare($queryPedidos)) {
    if ($typesPedidos) {
        $stmt->bind_param($typesPedidos, ...$paramsPedidos);
    }
    $stmt->execute();
    $resultPedidos = $stmt->get_result();
    $stmt->close();
} else {
    echo "Erro ao preparar a consulta de pedidos: " . $mysqli->error;
}

// Consultar financeiro
$queryFinanceiro = "SELECT idProdutos, qtd FROM historicopedidos WHERE 1=1";
$paramsFinanceiro = [];
$typesFinanceiro = '';

// Condições para financeiro
if ($mesSelecionado !== "todos") {
    $queryFinanceiro .= " AND DATE_FORMAT(dataHora, '%m') = ?";
    $paramsFinanceiro[] = $mesNumero;
    $typesFinanceiro .= 's';
}

if ($pagamentoSelecionado !== "todos") {
    $queryFinanceiro .= " AND pagamento = ?";
    $paramsFinanceiro[] = $pagamentoSelecionado;
    $typesFinanceiro .= 's';
}

// Preparar e executar a consulta para financeiro
if ($stmt = $mysqli->prepare($queryFinanceiro)) {
    if ($typesFinanceiro) {
        $stmt->bind_param($typesFinanceiro, ...$paramsFinanceiro);
    }
    $stmt->execute();
    $resultFinanceiro = $stmt->get_result();
    $stmt->close();
} else {
    echo "Erro ao preparar a consulta financeira: " . $mysqli->error;
}

if (isset($_GET['deletar'])) {
    $idPedido = intval($_GET['deletar']);
    $stmt = $mysqli->prepare("DELETE FROM historicopedidos WHERE idPedido = ?");
    $stmt->bind_param('i', $idPedido);
    $stmt->execute();
    $stmt->close();
    header("Location: ./historico.php");
    exit();
}

if (isset($_GET['limpar'])) {
    $stmt = $mysqli->prepare("DELETE FROM historicopedidos");
    $stmt->execute();
    $stmt->close();
    header("Location: ./historico.php");
    exit();
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
                    foreach ($meses as $nomeMes => $numeroMes) {
                        $selected = ($mesSelecionado === $nomeMes) ? 'selected' : '';
                        echo "<option value=\"$nomeMes\" $selected>" . ucfirst($nomeMes) . "</option>";
                    }
                    ?>
                </select>

                <select name="pagamento" id="pagamento">
                    <option value="todos" <?= ($pagamentoSelecionado === 'todos') ? 'selected' : '' ?>>Todas as formas
                    </option>
                    <option value="cartão" <?= ($pagamentoSelecionado === 'cartão') ? 'selected' : '' ?>>Cartão</option>
                    <option value="pix" <?= ($pagamentoSelecionado === 'pix') ? 'selected' : '' ?>>Pix</option>
                    <option value="dinheiro" <?= ($pagamentoSelecionado === 'dinheiro') ? 'selected' : '' ?>>Dinheiro
                    </option>
                </select>

                <button type="submit">Filtrar</button>
            </form>
        </div>
    </header>

    <main>
        <section>
            <h1 class="ped">Pedidos</h1>
            <?php
            if ($resultPedidos && $resultPedidos->num_rows > 0) {
                while ($row = $resultPedidos->fetch_assoc()) {
            ?>
            <div class="table-container">
                <table class="tab">
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
                        <tr>
                            <td><?php echo $row['idPedido']; ?></td>
                            <td>
                                <?php
                                    $nomeUsu = $row['idUsuario'];
                                    $stmt = $mysqli->prepare("SELECT pnome, sobrenome FROM usuarios WHERE idUsuarios = ?");
                                    $stmt->bind_param('i', $nomeUsu);
                                    $stmt->execute();
                                    $stmt->bind_result($nome, $sobrenome);
                                    $stmt->fetch();
                                    $stmt->close();
                                    echo $nome . ' ' . $sobrenome;
                                ?>
                            </td>
                            <td><?php echo date("d/m/Y H:i", strtotime($row['dataHora']))?></td>
                            <td>
                                <?php
                                    $idProdutos = $row['idProdutos'];
                                    $quantidades = $row['qtd'];
                                    $ids = explode(',', $idProdutos);
                                    $qtds = explode(',', $quantidades);
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
                            <td><?php echo $row['pagamento']?></td>
                            <td><?php echo "R$ " . number_format($row['valorTotal'], 2, "," , ".") . "<br>"?></td>
                            <td><a href="./historico.php?deletar=<?php echo $row['idPedido']?>" class="del">Deletar</a>
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
            <div class="classificacao">
                <form action="" method="get">
                    <label for="classificacao">Classificar por:</label>
                    <select name="classificacao" id="classificacao">
                        <option value="todos"
                            <?= (!isset($_GET['classificacao']) || $_GET['classificacao'] === 'todos') ? 'selected' : '' ?>>
                            Todos</option>
                        <option value="mais_vendidos"
                            <?= (isset($_GET['classificacao']) && $_GET['classificacao'] === 'mais_vendidos') ? 'selected' : '' ?>>
                            Mais Vendidos</option>
                        <option value="menos_vendidos"
                            <?= (isset($_GET['classificacao']) && $_GET['classificacao'] === 'menos_vendidos') ? 'selected' : '' ?>>
                            Menos Vendidos</option>
                    </select>
                    <button type="submit">Filtrar</button>
                </form>
            </div>
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
                    $produtosQuantidades = [];
                    $valorTotal = 0; // Inicializar a variável valorTotal

                    if ($resultFinanceiro && $resultFinanceiro->num_rows > 0) {
                        $resultFinanceiro->data_seek(0);
                        while ($row = $resultFinanceiro->fetch_assoc()) {
                            $idProdutos = $row['idProdutos'];
                            $quantidades = $row['qtd'];
                            $ids = explode(',', $idProdutos);
                            $qtds = explode(',', $quantidades);
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

                    // Ordenar produtos conforme a classificação
                    if ($classificacaoSelecionada === "mais_vendidos") {
                        arsort($produtosQuantidades);
                    } elseif ($classificacaoSelecionada === "menos_vendidos") {
                        asort($produtosQuantidades);
                    }

                    foreach ($produtosQuantidades as $id => $quantidade) {
                        $stmt = $mysqli->prepare("SELECT nome FROM produtos WHERE idProdutos = ?");
                        $stmt->bind_param("i", $id);
                        $stmt->execute();
                        $stmt->bind_result($nomeProduto);
                        $stmt->fetch();
                        $stmt->close();
                        
                        echo "<tr>";
                        echo "<tr>";
                        echo "<td>" . $quantidade . "</td>";
                        echo "<td>" . $nomeProduto . "</td>";

                        // Calcular o valor total para este produto
                        $stmt = $mysqli->prepare("SELECT preco FROM produtos WHERE idProdutos = ?");
                        $stmt->bind_param("i", $id);
                        $stmt->execute();
                        $stmt->bind_result($precoProduto);
                        $stmt->fetch();
                        $stmt->close();

                        $valorTotalProduto = $quantidade * $precoProduto;
                        echo "<td>" . "R$ " . number_format($valorTotalProduto, 2, ",", ".") . "</td>";
                        echo "</tr>";

                        // Atualiza o valor total das vendas
                        $valorTotal += $valorTotalProduto;
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