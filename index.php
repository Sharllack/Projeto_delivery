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
        <div class="darkMode">
            <label id="toggle-button">
                <input type="checkbox" id="toggle">
                <span class="slider1 round"></span>
            </label>
        </div>
        <div class="home">
            <a href="./index.php">
                <img src="./imagens/imagens_pincipal/home_24dp_E8EAED_FILL0_wght400_GRAD0_opsz24.png" height="30px" width="30px" alt="Home">
            </a>
        </div>
    </header>

    <section class="agarra">

    </section>
    <main>
        <div class="infoPrincipal">
            <img src="./imagens/imagens_pincipal/comida-criolla-peru-peruvian-food-260nw-2191344515.webp" alt="Logo do Restaurante" class="logoRestaurante">
            <p class="tempoDeEntrega">Restaurante • 20-90 min • 
                <span class="sit">
                    <?php if($situacao === 1): ?>
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
        <section style="padding: 15px;" class="secBebidas">
            <h1 class="titleBebidas">ESCOLHA A SUA BEBIDA</h1>
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
    <div class="btns">
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
            <a href="https://wa.me/5521990420932?text=Olá! Eu gostaria de tirar uma dúvida!" target="_blank"><img src="./imagens/whats_logo.png" alt="whatsapp" id="wpp"></a>
        </div>
    </div>
    <footer>
        <div class="outrasInfos">
            <h1 style="font-size: 2em; color: white;">Contatos</h1>
            <div class="redes">
                <a href="https://wa.me/5521990420932?text=Olá! Eu gostaria de tirar uma dúvida!" target="_blank"><img src="./imagens/logos/sm_5b321c98efaa6.png" alt="logo WhatsApp" class="logos" id="logoWpp"></img>+55(21)99042-0932</a>
            </div>
            <div class="redes">
                <a href="https://www.instagram.com/lucas_mnzs_/" target="_blank"><img src="./imagens/logos/images-removebg-preview.png" alt="Logo instagram" class="logos" id="logoInsta"></img>Instagram</a>
            </div>
        </div>
        <div class="endereco">
            <h1 style="font-size: 2em; color: white;">Endereço</h1>
            <p>Nos visite em nossa loja física no endereço:</p>
            <p>Rua Dr. Furquim Mendes, 990/Vila Centenário, Duque de Caxias/RJ</p>
        </div>
    <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3678.8321513755886!2d-43.316062925162875!3d-22.771609533053063!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x997aa3bcff72fb%3A0x77d5c3defa357629!2sR.%20Dr.%20Furquim%20Mendes%2C%20990%20-%20Vila%20Centenario%2C%20Duque%20de%20Caxias%20-%20RJ%2C%2025030-170!5e0!3m2!1spt-BR!2sbr!4v1722418653219!5m2!1spt-BR!2sbr" width="400" height="300" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </footer>
    <script src="./js_pincipal/funcoes.js"></script>
</body>
</html>