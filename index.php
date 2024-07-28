<?php 

include('./conexao/conexao.php');

if(!isset($_SESSION)) {
    session_start();
};

$stmt = $mysqli->prepare("SELECT situacao FROM host");
$stmt->execute();
$stmt->bind_result($situacao);
$stmt->fetch();
$stmt->close();

$_SESSION['taxa'] = true;

if(!isset($_SESSION['user'])) {
    $bairro = '';
} else {
    $bairro = $_SESSION['bairro'];
}

$sql_query = "SELECT * FROM produtos WHERE ativo = 1 AND categoria = 'prato'";
$result = $mysqli->query($sql_query);

$sql = "SELECT * FROM produtos WHERE ativo = 1 AND categoria = 'bebida'";
$result_bebibas = $mysqli->query($sql);

if($bairro == 'Vila Centenário') {
    $_SESSION['taxa'] = '3';
} else if(!isset($_SESSION['user'])){
    $_SESSION['taxa'] = '3,00 - R$4';
} else {
    $_SESSION['taxa'] = '4';
}

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
    <div class="openOrClosedDiv">
        <span class="openOrClosed">

            <?php if($situacao == 1): ?>
                <span class="aberto">Aberto</span>
            <?php else :?>
                <span class="fechado">Fechado</span>
            <?php endif; ?>

        </span>
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
        <h1 class="nomeLoja">Quentinhas do Lucas</h1>
    </header>

    <section class="agarra">

    </section>
    <main>
        <div class="infoPrincipal">
            <img src="./imagens/imagens_pincipal/comida-criolla-peru-peruvian-food-260nw-2191344515.webp" alt="Logo do Restaurante" class="logoRestaurante">
            <p class="tempoDeEntrega">Restaurante • 20-90 min • 
                <span class="sit">
                    <?php if($situacao == 1): ?>
                        <span class="situ">Aberto</span>
                    <?php else :?>
                        <span class="situ">Fechado</span>
                    <?php endif; ?>
                </span>
            </p>
            <p style="margin-top: 20px;">Taxa de entrega: <strong>R$<?php echo $_SESSION['taxa']?>,00</strong></p>
            <p class="horarioDeFuncionamento"><strong>Horário de funcionamento hoje:</strong></p>
            <p class="hora"><strong>11:00 às 15:00</strong></p>
            <hr class="hr">
            <?php if(!isset($_SESSION['user'])):?>
                <a href="./login_usuario.php" class="logBtn">FAÇA LOGIN</a>
            <?php else: ?>
                <p class="nomeDoCliente"> Seja Bem-Vindo(a), <strong><?php echo $_SESSION['nome']?></strong>!</p>
            <?php endif; ?>
        </div>
        <section style="padding: 15px;" class="secPratos">
            <h1 class="pratosTitle">ESCOLHA O SEU PRATO</h1>
            <div class="pratosContainer">
                <?php while($row = $result->fetch_assoc()) { ?>
                    <?php 
                    
                    if(!isset($_SESSION['idUsuario'])) {
                        $off = './login_usuario.php';
                    } else {
                        $off = './complemento_produto.php?comprar=' . $row["idProdutos"];
                    }
                    
                    ?>
                    <a href="<?php echo $off; ?>" class="linkPratos">
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
                    <?php 
                    
                    if(!isset($_SESSION['idUsuario'])) {
                        $off = './login_usuario.php';
                    } else {
                        $off = './complemento_produto.php?comprar=' . $row["idProdutos"];
                    }
                    
                    ?>
                    <a href="<?php echo $off; ?>" class="linkPratos">
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
    <?php if(isset($_SESSION['user'])):?>
    <div class="shopCart">
        <a href="./carrinho.php" class="carrinho">
            <img src="./imagens/imagens_pincipal/shopping_cart_24dp_E8EAED_FILL0_wght400_GRAD0_opsz24.png" height="30px" width="30px" alt="Carrinho">
        </a>
    </div>
    <?php else: ?>
        <div class="shopCart">
        <a href="./login_usuario.php" class="carrinho">
            <img src="./imagens/imagens_pincipal/shopping_cart_24dp_E8EAED_FILL0_wght400_GRAD0_opsz24.png" height="30px" width="30px" alt="Carrinho">
        </a>
    </div>
    <?php endif; ?>
    <div class="wpp">
        <div class="wppBallon">
            <p>Mande Mensagem ou nos ligue: (21)99042-0932</p>
        </div>
        <a href="https://wa.me/5521990420932?text=Olá! Eu gostaria de tirar uma dúvida!" target="_blank"><img src="./imagens/whats_logo.png" alt="whatsapp" id="wpp"></a>
    </div>
    <script src="./js_pincipal/funcoes.js"></script>
</body>
</html>