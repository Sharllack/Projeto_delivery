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

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./estilo_situacao_produto/index.css">
    <link rel="stylesheet" href="./estilo_situacao_produto/media_querie.css">
    <link rel="shortcut icon" href="./imagens/favicon.ico" type="image/x-icon">
    <title>Acompanhe o Seu Pedido</title>
</head>
<body>

    <div class="home">
        <a href="./index.php" class="inicio">
            <img src="./imagens/imagens_pincipal/home_24dp_E8EAED_FILL0_wght400_GRAD0_opsz24.png" height="30px" width="30px" alt="Home">
        </a>
    </div>
    <div class="darkMode">
        <label id="toggle-button">
            <input type="checkbox" id="toggle">
            <span class="slider1 round"></span>
        </label>
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
        <div class="wpp">
            <a href="https://wa.me/5521990420932?text=Olá! Eu gostaria de tirar uma dúvida!" target="_blank"><img src="./imagens/whats_logo.png" alt="whatsapp" id="wpp"></a>
        </div>
    </div>
    <div class="borda1"></div>
    <div class="borda2"></div>
    <div class="borda3"></div>
    <script src="./js_situacao_produto/funcoes.js"></script>
    <script>
        const link = document.querySelector('.inicio');
        const situ = document.querySelector('.situProd');
        const load = document.querySelector('.load');
        const idPedido = <?php echo $idPedido; ?>;

        if(situ.textContent != 'Pedido aguardando retirada!' & situ.textContent != 'O seu pedido está sendo preparado!' & situ.textContent != 'O seu pedido já saiu para a entrega!' & situ.textContent != 'Aguardando a confirmação do restaurante!'){
            link.href = './refresh_carrinho.php?idPedido=' + idPedido;
            load.style.display = 'none';

            function liberar(){
                window.location.href = './refresh_carrinho.php?idPedido=' + idPedido;
            }

            const time = 10000;

            window.onload = () => {
                setTimeout(liberar, time);
            }
        }
    </script>
</body>
</html>
