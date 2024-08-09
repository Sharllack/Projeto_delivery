<?php

include('./conexao/conexao.php');

if (!isset($_SESSION)) {
    session_start();
}

$usuario = $_SESSION['idUsuario'];

// Usando consultas preparadas para evitar SQL Injection
$sql_query = "SELECT * FROM itenscarrinho
              JOIN carrinho ON itenscarrinho.idCarrinho = carrinho.idCarrinho
              JOIN produtos ON itenscarrinho.idProduto = produtos.idProdutos
              JOIN usuarios ON itenscarrinho.idUsuario = usuarios.idUsuarios
              WHERE idUsuarios = ?";

$stmt = $mysqli->prepare($sql_query);
$stmt->bind_param("i", $usuario);
$stmt->execute();
$result = $stmt->get_result();

$rows = [];
$total = 0;
$rua = $numero = $bairro = $idUsuario = $idCarrinho = $taxa = '';

while ($row = $result->fetch_assoc()) {
    $rows[] = $row;
    // Remove o símbolo da moeda e troca a vírgula por ponto para conversão
    $valorTotal = str_replace(['R$', '.'], ['', ','], $row['valorTotal']); // Remove 'R$', troca '.' por ',' se necessário
    $total = (float) str_replace(',', '.', $valorTotal); // Converte o valor para float
    $rua = $row['rua'];
    $numero = $row['numero'];
    $bairro = $row['bairro'];
    $idUsuario = $row['idUsuarios'];
    $idCarrinho = $row['idCarrinho'];
    $taxa = $row['taxa']; // Obtém a taxa, mas não adicione ainda
    if (isset($_SESSION['pedido_finalizado'][$idCarrinho]) && $_SESSION['pedido_finalizado'][$idCarrinho] === true) {
        // Redireciona para a página de acompanhamento do pedido
        header('Location: ./situacao_pedido.php');
        exit;
    };

}
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./estilo_pagamento_endereco/index.css">
    <link rel="stylesheet" href="./estilo_pagamento_endereco/media_querie.css">
    <link rel="shortcut icon" href="./imagens/favicon.ico" type="image/x-icon">
    <title>Forma de Pagamento</title>
</head>
<body>

    <div class="home">
        <a href="./index.php">
            <img src="./imagens/imagens_pincipal/home_24dp_E8EAED_FILL0_wght400_GRAD0_opsz24.png" height="30px" width="30px" alt="Home">
        </a>
    </div>
    <header>
        <h1 class="nomeLoja">Quentinhas do Lucas</h1>
        <div class="darkMode">
            <label id="toggle-button">
                <input type="checkbox" id="toggle">
                <span class="slider1 round"></span>
            </label>
        </div>
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
            <div class="contTroco">
                <p class="ask">Vai precisar de troco?</p>
                <input type="radio" name="opcTroco" class="askTroco" value="sim">Sim
                <input type="radio" name="opcTroco" class="askTroco" value="nao">Não
            </div>
            <input type="number" name="troco" class="troco" placeholder="Troco para quanto?">
            <p class="error">O troco deve ser maior que o valor total</p>
            <p class="selectError">Selecione uma forma de pagamento</p>
            <h2 class="entTitle">Entrega</h2>
            <select name="opcEntrega" id="opcEntrega" style="font-weight: bold;">
                <option value="#" style="text-align: center;">-----Selecione uma Opção-----</option>
                <option value="entrega">ENTREGA</option>
                <option value="retirada">RETIRAR NA LOJA</option>
            </select>
            <p class="selectErro">Selecione o Tipo de Entrega</p>
            <h2 class="enderecoTitle">Endereço Para Entrega</h2>
            <section class="enderecoCont">
                <div class="end">
                    <img src="./imagens/location_on_24dp_00000_FILL0_wght400_GRAD0_opsz24.png" alt="localização" class="location">
                    <div class="endereco">
                        <p class="logra"><strong><?php echo $rua?>, <?php echo $numero?></strong></p>
                        <p class="bai"><?php echo $bairro?></p>
                    </div>
                </div>
                <a href="./editar_endereco.php?editar=<?php echo $idUsuario ?>" type="button" class="trocar">Trocar</a>
            </section>
            <h2 class="pedidoTitle">Pedido</h2>
            <section class="pedido">
                <?php
                foreach($rows as $row): ?>
                    <p><strong><?php echo $row['qtd'] . "x " . $row['nome'] . "<br>" . "Obs.:" . "<br>" . $row['obs'] . "<br> <br>"?></strong></p>
                <?php endforeach; ?>
            </section>
            <h2 class="taxaTitle">Taxa de Entrega</h2>
            <p style="margin-left: 15px; margin-bottom: 15px" class="valorTaxa"><strong><?php echo $taxa ?></strong></p>
            <?php
                    // Adicione a taxa ao total, se a taxa não estiver vazia
                if (!empty($taxa)) {
                    $taxa = str_replace(['R$', '.'], ['', ','], $taxa); // Remove 'R$', troca '.' por ',' se necessário
                    $total += (float) str_replace(',', '.', $taxa); // Converte a taxa para float e adiciona ao total
                }
            ?>
            <button type="submit" class="button" name="button" value="<?php echo $total?>">Finalizar Pedido <span class="valorTotal">R$ <?php echo number_format($total, 2, ',', '.');?></span></button>
        </form>
    </main>
    <div class="btns">
        <div class="wpp">
            <a href="https://wa.me/5521990420932?text=Olá! Eu gostaria de tirar uma dúvida!" target="_blank"><img src="./imagens/whats_logo.png" alt="whatsapp" id="wpp"></a>
        </div>
    </div>
    <div class="borda1"></div>
    <div class="borda2"></div>
    <div class="borda3"></div>
    <script src="./js_pagamento_endereço/script.js"></script>
</body>
</html>
