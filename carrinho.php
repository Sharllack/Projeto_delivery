<?php 

include('./conexao/conexao.php');

if(!isset($_SESSION)) {
    session_start(); 
};

$usuario = $_SESSION['idUsuario'];

if (isset($_SESSION['pedido_finalizado']) && $_SESSION['pedido_finalizado'] === true) {
    // Redireciona para a página de acompanhamento do pedido
    header('Location: ./situacao_pedido.php');
    exit;
};

$sql_query = "SELECT * FROM itenscarrinho 
              JOIN produtos ON itenscarrinho.idProduto = produtos.idProdutos
              WHERE idUsuario = $usuario";
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
    <title>Document</title>
</head>
<body>

    <div class="home">
        <a href="./index.php">
            <img src="./imagens/imagens_pincipal/home_24dp_E8EAED_FILL0_wght400_GRAD0_opsz24.png" height="30px" width="30px" alt="Home">
        </a>
    </div>
    <div class="openOrClosedDiv">
        <span class="openOrClosed">fechado</span>
    </div>
    <header>
        <h1 class="nomeLoja">Quentinhas do Lucas</h1>
    </header>
    <main>
    <h1 class="pratosTitle">FINALIZE O SEU PEDIDO</h1>
    <?php while($row = $result->fetch_assoc()) { ?>
        <form action="./adicionar_total.php?finalizar=<?php echo $row['idProduto']; ?>" method="post">
            <section class="secPratos">
                <div class="pratosContainer">
                        <section href="./complemento_produto.php?comprar=<?php echo $row['idProduto'];?>" class="linkPratos">
                            <div class="pratos">
                                <div class="imgPrato">
                                    <img height="80px" width="80px" src="./conexao/<?php echo $row['imagem'];?>" alt="">
                                </div>
                                <div class="infoGeral">
                                    <div class="info">
                                        <h1 class="nomeDoPrato"><?php echo $row['nome']?></h1>
                                        <p style="color: rgb(119, 119, 119);" class="descricaoDoPrato">(<?php echo $row['descricao']?>)</p>
                                    </div>
                                    <div class="preco">
                                        <div class="qtd">
                                            <div class="qtd">
                                                <div class="valor">
                                                    <span class="valorProduto">R$<?php echo number_format($row['preco'], 2, ',', '.'); ?></span>
                                                <div class="soma">
                                                    <button type="button">+</button>
                                                </div>
                                                    <input type="number" name="quantidade[<?php echo $row['idProduto']; ?>]" readonly class="quantidade" value="<?php echo $row['qtd']; ?>" data-preco="<?php echo $row['preco']?>" style="font-weight: bold; text-align:center;">
                                                </div>
                                                <div class="subtrai">
                                                    <button type="button" >-</button>
                                                </div>
                                                <div>
                                                    <a href="./remover_produto.php?remover=<?php echo $row['idProduto']; ?>" style="display:none;" class="btnRemove">REMOVER</a>
                                                </div>
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
            <button type="submit" class="button">Forma de Pagamento <span class="valorTotal">R$<?php echo number_format($total, 2, ',', '.');?></span></button>
        </form>
    </main>
    <div class="shopCart">
        <a href="./carrinho.php">
            <img src="./imagens/imagens_pincipal/shopping_cart_24dp_E8EAED_FILL0_wght400_GRAD0_opsz24.png" height="30px" width="30px" alt="Carrinho">
        </a>
    </div>
    <div class="wpp">
        <a href="https://wa.me/5521990420932?text=Olá! Eu gostaria de tirar uma dúvida!"><img src="./imagens/whats_logo.png" alt="whatsapp"></a>
    </div>
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
