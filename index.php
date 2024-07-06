<?php 

include('./conexao/conexao.php');

if(!isset($_SESSION)) {
    session_name('user_session');
    session_start();
};

$sql_query = "SELECT * FROM produtos WHERE ativo = 1 AND categoria = 'prato'";
$result = $mysqli->query($sql_query);

$sql = "SELECT * FROM produtos WHERE ativo = 1 AND categoria = 'bebida'";
$result_bebibas = $mysqli->query($sql);
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./estilo_principal/index.css">
    <link rel="stylesheet" href="./estilo_principal/media_querie.css">
    <title>Document</title>
</head>
<body>

    <div class="home">
        <a href="./index.php">
            <img src="./imagens/imagens_pincipal/home_24dp_E8EAED_FILL0_wght400_GRAD0_opsz24.png" height="30px" width="30px" alt="Home">
        </a>
    </div>
    <div style="position: fixed; right:15px; top:20px; z-index: 9999;" class="openOrClosedDiv">
        <span class="openOrClosed">fechado</span>
    </div>
    <?php if(!isset($_SESSION['user'])):?>
        <div class="logout" style="display: none;">
        </div>
    <?php else: ?>
        <div class="logout" style=" position: fixed; z-index: 9999;">
            <a href="./logout_usuario.php">Sair</a>
        </div>
    <?php endif; ?>

    <header>
        <h1 class="nomeLoja">Quentinhas da Vanessa</h1>
    </header>

    <section class="agarra">

    </section>
    <main>
        <div class="infoPrincipal">
            <img src="./imagens/imagens_pincipal/comida-criolla-peru-peruvian-food-260nw-2191344515.webp" alt="Logo do Restaurante" class="logoRestaurante">
            <p class="tempoDeEntrega">Restaurante • 20-90 min • <span class="sit"></span></p>
            <p class="horarioDeFuncionamento">Horário de funcionamento hoje:</p>
            <p class="hora"><strong>11:00 às 15:00</strong></p>
            <hr class="hr">
            <?php if(!isset($_SESSION['user'])):?>
                <a href="./login_usuario.php" class="logBtn">FAÇA LOGIN</a>
            <?php else: ?>
                <p class="nomeDoCliente"> Olá, <?php echo $_SESSION['nome']?>!</p>
            <?php endif; ?>
        </div>
        <section style="padding: 15px;" class="secPratos">
            <h1 class="pratosTitle">ESCOLHA O SEU PRATO</h1>
            <div class="pratosContainer">
                <?php while($row = $result->fetch_assoc()) { ?>
                    <a href="./complemento_produto.php?comprar=<?php echo $row['id'];?>" class="linkPratos">
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
                                </div>
                            </div>
                        </div>
                    </a>
                <?php } ?>
            </div>
        </section>
        <section style="padding: 15px;">
            <h1 class="titleBebidas">BEBIDAS</h1>
                <div class="pratosContainer">
                <?php while($row = $result_bebibas->fetch_assoc()) { ?>
                    <a href="./complemento_produto.php?comprar=<?php echo $row['id'];?>" class="linkPratos">
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
                                </div>
                            </div>
                        </div>
                    </a>
                <?php } ?>
                </div>
        </section>
    </main>
    <div class="shopCart">
        <a href="./carrinho.php">
            <img src="./imagens/imagens_pincipal/shopping_cart_24dp_E8EAED_FILL0_wght400_GRAD0_opsz24.png" height="30px" width="30px" alt="Carrinho">
        </a>
    </div>
    <script src="./js_pincipal/funcoes.js"></script>
</body>
</html>