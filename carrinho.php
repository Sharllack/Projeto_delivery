<?php 

include('./conexao/conexao.php');

if(!isset($_SESSION)) {
    session_name('user_session');
    session_start();
};

$sql_query = "SELECT * FROM usuarios JOIN carrinho on usuarios.idUsuarios = carrinho.idUsuario JOIN produtos on produtos.idProdutos = carrinho.idProdutos";
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
        <h1 class="nomeLoja">Quentinhas da Vanessa</h1>
    </header>
    <main>
        <section style="padding: 15px;" class="secPratos">
            <h1 class="pratosTitle">FINALIZE O SEU PEDIDO</h1>
            <div class="pratosContainer">
                <?php while($row = $result->fetch_assoc()) { ?>
                    <section href="./complemento_produto.php?comprar=<?php echo $row['idProdutos'];?>" class="linkPratos">
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
                                    <p><?php echo "R$" . number_format($row['preco'], 2, "," , ".")?></p>
                                    <div class="qtd">
                                        <div class="qtd">
                                            <div class="soma">
                                                <button type="button" onclick="adicionar(this)">+</button>
                                            </div>
                                            <div class="valor">
                                                <input type="number" name="qtd" id="qtd" value="<?php echo $row['quantidade']?>" style="font-weight: bold; text-align:center;">
                                            </div>
                                            <div class="subtrai">
                                                <button type="button" onclick="subtrair(this)">-</button>
                                            </div>
                                            <div>
                                                <a href="./remover_produto.php?remover=<?php echo $row['idCarrinho']; ?>" style="display:none;" class="btnRemove">REMOVER</a>
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
                <?php } ?>
            </div>
        </section>
        <a href="#" class="button">Finalizar Pedido <span class="valorTotal">R$<?php echo number_format($total, 2, ',', '.');?></span></a>
    </main>
    <div class="shopCart">
        <a href="./carrinho.php">
            <img src="./imagens/imagens_pincipal/shopping_cart_24dp_E8EAED_FILL0_wght400_GRAD0_opsz24.png" height="30px" width="30px" alt="Carrinho">
        </a>
    </div>
    <script src="./js_carrinho/script.js"></script>
</body>
</html>