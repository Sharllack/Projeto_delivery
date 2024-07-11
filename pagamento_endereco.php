<?php 

include('./conexao/conexao.php');

if(!isset($_SESSION)) {
    session_start();
};

$usuario = $_SESSION['idUsuario'];

$sql_query = "SELECT * FROM itenscarrinho 
            JOIN carrinho ON itenscarrinho.idCarrinho = carrinho.idCarrinho
            JOIN produtos ON itenscarrinho.idProduto = produtos.idProdutos
            JOIN usuarios ON itenscarrinho.idUsuario = usuarios.idUsuarios
            WHERE idUsuarios = $usuario";
$result = $mysqli->query($sql_query) or die ($mysqli->error);

while($row = mysqli_fetch_assoc($result)) {
    $rows[] = $row;
    $total = $row['valorTotal'];
    $rua = $row['rua'];
    $numero = $row['numero'];
    $bairro = $row['bairro'];
    $idUsuario = $row['idUsuarios'];
    $idCarrinho = $row['idCarrinho'];
}

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
        <h1 class="nomeLoja">Quentinhas do Lucas</h1>
    </header>
    <main>
    <h1 class="pratosTitle">Finalize seu Pedido</h1>
        <form action="./cadastrar_pedido.php?finalizar=<?php echo $idCarrinho;?>" method="post">
            <h2 class="formaTitle">Formas de Pagamento</h2>
            <select name="opcoes" id="opcoes" style="font-weight: bold;">
                <option value="#" style="text-align: center;">-----Selecione uma Opção-----</option>
                <option value="cartao">CARTÃO</option>
                <option value="dinheiro">DINHEIRO</option>
                <option value="pix">PIX</option>
            </select>
            <p class="selectError">Selecione uma forma de pagamento</p>
            <h2 class="enderecoTitle">Endereço</h2>
            <section class="enderecoCont">
                <div class="end">
                    <img src="./imagens/location_on_24dp_00000_FILL0_wght400_GRAD0_opsz24.png" alt="localização" class="location">
                    <div class="endereco">
                        <p><strong><?php echo $rua?>, <?php echo $numero?></strong></p>
                        <p><?php echo $bairro?></p>
                    </div>
                </div>
                <a href="./editar_endereco.php?editar=<?php echo $idUsuario ?>" type="button" class="trocar">Trocar</a>
            </section>
            <h2 class="pedidoTitle">Pedido</h2>
            <section class="pedido">
                <?php foreach($rows as $row): ?>
                    <p><strong><?php echo $row['qtd'] . "x " . $row['nome'] . "<br>" . "Obs.:" . $row['obs']?></strong></p>
                <?php endforeach; ?>
            </section>
            <button type="submit" class="button">Finalizar Pedido <span class="valorTotal">R$<?php echo number_format($total, 2, ',', '.');?></span></button>
        </form>
    </main>
    <div class="shopCart">
        <a href="#">
            <img src="./imagens/imagens_pincipal/shopping_cart_24dp_E8EAED_FILL0_wght400_GRAD0_opsz24.png" height="30px" width="30px" alt="Carrinho">
        </a>
    </div>
    <div class="wpp">
        <a href="https://wa.me/5521990420932?text=Olá! Eu gostaria de tirar uma dúvida!"><img src="./imagens/whats_logo.png" alt="whatsapp"></a>
    </div>
    <script src="./js_pagamento_endereço/script.js"></script>
</body>
</html>
