<?php

include('./conexao/conexao.php');

if(!isset($_SESSION)) {
    session_start();
};

$usuario = $_SESSION['idUsuario'];

$sql_query = "SELECT * FROM itenscarrinho
              JOIN produtos ON itenscarrinho.idProduto = produtos.idProdutos
              JOIN carrinho ON itenscarrinho.idCarrinho = carrinho.idCarrinho
              JOIN usuarios ON itenscarrinho.idUsuario = usuarios.idUsuarios
              WHERE idUsuarios = $usuario";
$result = $mysqli->query($sql_query);

$total = 0;

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./estilo_carrinho/index.css">
    <link rel="stylesheet" href="./estilo_carrinho/media_querie.css">
    <link rel="shortcut icon" href="./imagens/favicon.ico" type="image/x-icon">
    <title>Carrinho</title>
</head>

<body>

    <div class="loading">
        <div class="load">

        </div>
    </div>

    <div class="home">
        <a href="./index.php">
            <img src="./imagens/imagens_pincipal/home_24dp_E8EAED_FILL0_wght400_GRAD0_opsz24.png" height="30px"
                width="30px" alt="Home">
        </a>
    </div>
    <header>
        <h1 class="nomeLoja">Quentinhas do Lucas</h1>
        <div class="darkMode">
            <label id="toggle-button">
                <input type="checkbox" id="toggle">
                <span class="slider1 round"></span>
            </label>
        </div>
    </header>
    <main>
        <h1 class="pratosTitle">FINALIZE O SEU PEDIDO</h1>
        <?php while($row = $result->fetch_assoc()) { ?>
        <?php

            $idCarrinho = $row['idCarrinho'];

            if (isset($_SESSION['pedido_finalizado'][$idCarrinho]) && $_SESSION['pedido_finalizado'][$idCarrinho] === true) {
                // Redireciona para a página de acompanhamento do pedido
                header('Location: ./situacao_pedido.php');
                exit;
            };

        ?>
        <form action="./adicionar_total.php?finalizar=<?php echo $row['idProduto']; ?>" method="post">
            <section class="secPratos">
                <div class="pratosContainer">
                    <section href="./complemento_produto.php?comprar=<?php echo $row['idProduto'];?>"
                        class="linkPratos">
                        <div class="pratos">
                            <div class="imgPrato">
                                <img src="./conexao/<?php echo $row['imagem'];?>" alt="">
                            </div>
                            <div class="infoGeral">
                                <div class="info">
                                    <h1 class="nomeDoPrato"><?php echo $row['nome']?></h1>
                                    <p style="color: rgb(119, 119, 119);" class="descricaoDoPrato">
                                        (<?php echo $row['descricao']?>)</p>
                                </div>
                                <div class="preco">
                                    <div class="qtd">
                                        <div class="valor">
                                            <span
                                                class="valorProduto">R$<?php echo number_format($row['preco'], 2, ',', '.'); ?></span>
                                        </div>
                                        <div class="oper">
                                            <div class="soma">
                                                <button type="button">+</button>
                                            </div>
                                            <div>
                                                <input type="number" name="quantidade[<?php echo $row['idProduto']; ?>]"
                                                    readonly class="quantidade" value="<?php echo $row['qtd']; ?>"
                                                    data-preco="<?php echo $row['preco']?>"
                                                    style="font-weight: bold; text-align:center;">
                                            </div>
                                            <div class="subtrai">
                                                <button type="button">-</button>
                                            </div>
                                        </div>
                                        <div>
                                            <a href="./remover_produto.php?remover=<?php echo $row['idProduto']; ?>"
                                                style="display:none;" class="btnRemove">REMOVER</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                    <?php

                    $total += $row['preco'];

                    ?>
                    <?php

                    $produtos = $row['idProduto'];

                    ?>
                    <?php } ?>
                </div>
            </section>
            <input type="hidden" name="idProduto" value="<?php echo $produtos ?>">
            <input type="hidden" name="total" value="<?php echo $total ?>">
            <div class="btn">
                <button type="submit" class="button">Forma de Pagamento <span
                        class="valorTotal">R$<?php echo number_format($total, 2, ',', '.');?></span></button>
            </div>
        </form>
    </main>
    <script>
    document.querySelector('form').addEventListener('submit', function(event) {
        let inputs = document.querySelectorAll('.qtd input[type="number"]');
        let allowSubmit = true;

        inputs.forEach(function(input) {
            if (parseInt(input.value) < 1) {
                allowSubmit = false;
            }
        });

        if (!allowSubmit) {
            event.preventDefault();
            alert('Por favor, ajuste a quantidade dos produtos antes de finalizar o pedido.');
        }
    });
    </script>
    <script src="./js_carrinho/script.js"></script>
</body>

</html>