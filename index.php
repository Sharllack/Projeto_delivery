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
        <a href="./index.php" style="padding: 10px 20px;">
            <img src="./imagens/imagens_pincipal/home_24dp_E8EAED_FILL0_wght400_GRAD0_opsz24.png" height="30px" width="30px" alt="Home">
        </a>
    </div>
    <div class="shopCart">
        <a href="./carrinho.php" style="padding: 10px 20px;">
            <img src="./imagens/imagens_pincipal/shopping_cart_24dp_E8EAED_FILL0_wght400_GRAD0_opsz24.png" height="30px" width="30px" alt="Carrinho">
        </a>
    </div>
    <div style="position: fixed; right:15px; top:20px; z-index: 9999;">
        <span class="openOrClosed">fechado</span>
    </div>
    <?php if(!isset($_SESSION['user'])):?>
        <div class="logout" style="display: none;">
            <a href="./logout_usuario.php" style="color: white; padding: 10px 20px; font-weight: bold; text-decoration: none;">Sair</a>
        </div>
    <?php else: ?>
        <div class="logout" style=" position: fixed; z-index: 9999;">
            <a href="./logout_usuario.php" style="color: white; padding: 10px 20px; font-weight: bold; text-decoration: none;">Sair</a>
        </div>
    <?php endif; ?>

    <header>
        <h1>Quentinhas da Vanessa</h1>
    </header>

    <section class="agarra">

    </section>
    <main>
        <div style="left: 45%; top: 20%; margin: auto; position: absolute;">
            <img src="./imagens/imagens_pincipal/comida-criolla-peru-peruvian-food-260nw-2191344515.webp" style="border-radius: 50%; height: 150px; width: 150px; border: 1px solid white; box-shadow: 2px 2px 8px black;" alt="Logo do Restaurante">
            <p style="transform: translate(-50px, 0);">Restaurante • 20-90 min • <span class="sit"></span></p>
            <p style="transform: translate(-40px, 25px);">Horário de funcionamento hoje:</p>
            <p style="transform: translate(20px, 30px);"><strong>11:00 às 15:00</strong></p>
            <hr style="transform: translate(-80px, 30px);">
            <?php if(!isset($_SESSION['user'])):?>
                <a href="./login_usuario.php" class="logBtn">FAÇA LOGIN</a>
            <?php else: ?>
                <p style="transform: translate(35px, 35px); font-weight: bold;"> Olá, <?php echo $_SESSION['nome']?>!</p>
            <?php endif; ?>
        </div>
        <section style="padding: 15px;">
            <h1 style="padding-top: 250px; margin-bottom: 15px; color: darkslategray; margin-left: 15px">ESCOLHA O SEU PRATO</h1>
            <div class="pratosContainer">
                <?php while($row = $result->fetch_assoc()) { ?>
                    <a href="./complemento_produto.php?comprar=<?php echo $row['id'];?>" class="linkPratos">
                        <div class="pratos">
                            <div class="imgPrato">
                                <img height="80px" width="80px" src="./conexao/<?php echo $row['imagem'];?>" alt="">
                            </div>
                            <div class="info">
                                <h1><?php echo $row['nome']?></h1>
                                <p style="color: rgb(119, 119, 119);">(<?php echo $row['descricao']?>)</p>
                            </div>
                            <div class="preco" style="display: block; width: 100%; font-weight: bold; color: #27A143; font-size: 1.3em;">
                                <p style="float: right;"><?php echo "R$" . number_format($row['preco'], 2, "," , ".")?></p>
                            </div>
                        </div>
                    </a>
                <?php } ?>
            </div>
        </section>
        <section style="padding: 15px;">
            <h1 style="margin-bottom: 15px; color: darkslategray; margin-left: 15px">BEBIDAS</h1>
                <div class="pratosContainer">
                <?php while($row = $result_bebibas->fetch_assoc()) { ?>
                    <a href="./complemento_produto.php?comprar=<?php echo $row['id'];?>" class="linkPratos">
                        <div class="pratos">
                            <div class="imgPrato">
                                <img height="80px" width="80px" src="./conexao/<?php echo $row['imagem'];?>" alt="">
                            </div>
                            <div class="info">
                                <h1><?php echo $row['nome']?></h1>
                                <p style="color: rgb(119, 119, 119);">(<?php echo $row['descricao']?>)</p>
                            </div>
                            <div class="preco" style="display: block; width: 100%; font-weight: bold; color: #27A143; font-size: 1.3em;">
                                <p style="float: right;"><?php echo "R$" . number_format($row['preco'], 2, "," , ".")?></p>
                            </div>
                        </div>
                    </a>
                <?php } ?>
                </div>
        </section>
    </main>
    <script src="./js_pincipal/funcoes.js"></script>
</body>
</html>