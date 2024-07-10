<?php 

include('./conexao/conexao.php');

if(!isset($_SESSION)) {
    session_name('user_session');
    session_start();
};

$idUsuario = $_SESSION['idUsuario'];

$sql_query = "SELECT * FROM pedidos WHERE idUsuario = $idUsuario";
$result = $mysqli->query($sql_query) or die ($mysqli->error);

while($row = mysqli_fetch_assoc($result)) {
    $situacao = $row['situacao'];
}

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
        <h1 class="titleSit">Situação do Pedido</h1>
        <p class="situProd"><?php echo $situacao; ?></p>

    </main>
    <div class="shopCart">
        <a href="#">
            <img src="./imagens/imagens_pincipal/shopping_cart_24dp_E8EAED_FILL0_wght400_GRAD0_opsz24.png" height="30px" width="30px" alt="Carrinho">
        </a>
    </div>
    <script src="./js_situacao_produto/funcoes.js"></script>
</body>
</html>