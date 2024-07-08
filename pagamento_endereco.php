<?php 

include('./conexao/conexao.php');

if(!isset($_SESSION)) {
    session_name('user_session');
    session_start();
};

$sql_query = "SELECT * FROM usuarios JOIN carrinho on usuarios.idUsuarios = carrinho.idUsuario JOIN produtos on produtos.idProdutos = carrinho.idProdutos";
$result = $mysqli->query($sql_query);



?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./estilo_pagamento_endereco/index.css">
    <link rel="stylesheet" href="./estilo_pagamento_endereco/media_querie.css">
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
    <h1 class="pratosTitle">Forma de Pagamento</h1>
        <form action="./situacao_pedido.php?finalizar=<?php echo $row['idCarrinho']; ?>" method="post">
            <select name="opcoes" id="opcoes">
                <option value="cartao">CARTÃO</option>
                <option value="dinheiro">DINHEIRO</option>
                <option value="pix">PIX</option>
            </select>
            <button type="submit" class="button">Finalizar Pedido <span class="valorTotal">R$<?php echo number_format($total, 2, ',', '.');?></span></button>
        </form>
    </main>
    <div class="shopCart">
        <a href="./carrinho.php">
            <img src="./imagens/imagens_pincipal/shopping_cart_24dp_E8EAED_FILL0_wght400_GRAD0_opsz24.png" height="30px" width="30px" alt="Carrinho">
        </a>
    </div>
    <script src="./js_pagamento_endereco/script.js"></script>
</body>
</html>
