<?php

include('./conexao/conexao.php');

if(!isset($_SESSION)) {
    session_start();
};

$usuario = $_SESSION['idUsuario'];

if(isset($_GET['comprar'])) {

    $protocolo = intval($_GET['comprar']);
    $sql_query = "SELECT * FROM produtos WHERE idProdutos = '$protocolo'";
    $arquivo = $mysqli->query($sql_query) or die ($mysqli->error);

    while($user_data = mysqli_fetch_assoc($arquivo)) {
        $nomeDoProduto = $user_data['nome'];
        $preco = $user_data['preco'];
        $descricao = $user_data['descricao'];
        $imgPrin = $user_data['imagem'];
    }
}

$sql_que = "SELECT * FROM itenscarrinho
              JOIN produtos ON itenscarrinho.idProduto = produtos.idProdutos
              JOIN carrinho ON itenscarrinho.idCarrinho = carrinho.idCarrinho
              JOIN usuarios ON itenscarrinho.idUsuario = usuarios.idUsuarios
              WHERE idUsuarios = $usuario";
$resulta = $mysqli->query($sql_que);

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
    <link rel="stylesheet" href="./estilo_complemento_produto/index.css">
    <link rel="stylesheet" href="./estilo_complemento_produto/media_querie.css">
    <link rel="shortcut icon" href="./imagens/favicon.ico" type="image/x-icon">
    <title>Complemento</title>
</head>
<body>

    <?php while($row = $resulta->fetch_assoc()) { ?>
            <?php

                $idCarrinho = $row['idCarrinho'];

                if (isset($_SESSION['pedido_finalizado'][$idCarrinho]) && $_SESSION['pedido_finalizado'][$idCarrinho] === true) {
                    // Redireciona para a página de acompanhamento do pedido
                    header('Location: ./situacao_pedido.php');
                    exit;
                };

            ?>
    <?php } ?>

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
        <section style="padding: 15px;" class="produtoContainer">
            <div class="produtoInfo">
                <img src="./conexao/<?php echo $imgPrin; ?>" alt="imagem do produto">
                <h1 class="nome"><?php echo $nomeDoProduto; ?></h1>
                <p class="descricao"><?php echo $descricao; ?></p>
                <p class="preco"><?php echo "R$" . number_format($preco, 2, "," , "."); ?></p>
            </div>
        </section>
        <form action="./adicionar_ao_carrinho.php?adicionar=<?php echo $protocolo; ?>" method="POST">
            <input type="hidden" name="valor" value="<?php echo $preco; ?>">
            <div class="titleText">
                <h2 class="text">Alguma observação?</h2>
            </div>
            <textarea name="obs" id="obs" placeholder="Ex.: Ponto da carne, mudar complemento, etc.."></textarea>
            <button type="submit" class="btn">ADICIONAR</button>
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
    <script src="./js_complemento_produto/funcoes.js"></script>
</body>
</html>
