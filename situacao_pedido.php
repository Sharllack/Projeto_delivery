<?php 

include('./conexao/conexao.php');

if(!isset($_SESSION)) {
    session_start();
};

$idUsuario = $_SESSION['idUsuario'];

$sql_query = "SELECT * FROM pedidos WHERE idUsuario = $idUsuario";
$result = $mysqli->query($sql_query) or die ($mysqli->error);

while($row = mysqli_fetch_assoc($result)) {
    $situacao = $row['situacao'];
    $idCarrinho = $row['idCarrinho'];
    $idPedido = $row['idPedido'];
}

if (!isset($_SESSION['pedido_finalizado'][$idCarrinho]) && $_SESSION['pedido_finalizado'][$idCarrinho] !== true) {
    // Redireciona para a página de acompanhamento do pedido
    header('Location: ./index.php');
    exit;
};

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./estilo_situacao_produto/index.css">
    <link rel="stylesheet" href="./estilo_situacao_produto/media_querie.css">
    <title>Document</title>
</head>
<body>

    <div class="home">
        <a href="./index.php" class="inicio">
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
        <h1 class="titleSit">Situação do Pedido</h1>
        <p class="situProd"><?php echo $situacao; ?></p>
        <div class="load">
            <div id="btn1"></div>
            <div id="btn2"></div>
            <div id="btn3"></div>
        </div>
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
    <script src="./js_situacao_produto/funcoes.js"></script>
    <script>
        const link = document.querySelector('.inicio');
        const situ = document.querySelector('.situProd');
        const load = document.querySelector('.load');
        const idPedido = <?php echo $idPedido; ?>; // Aqui está a correção

        if(situ.textContent == 'O seu pedido não foi aceito!' || situ.textContent == 'O seu pedido já saiu para a entrega!' || situ.textContent == 'Pedido aguardando retirada!'){
            link.href = './refresh_carrinho.php?idPedido=' + idPedido;
            load.style.display = 'none';
        }
    </script>
</body>
</html>
