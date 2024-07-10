<?php 

include('./conexao/conexao.php');

if(!isset($_SESSION)) {
    session_start();
};

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
    <div class="shopCart">
        <a href="./carrinho.php">
            <img src="./imagens/imagens_pincipal/shopping_cart_24dp_E8EAED_FILL0_wght400_GRAD0_opsz24.png" height="30px" width="30px" alt="Carrinho">
        </a>
    </div>
    <script src="./js_complemento_produto/funcoes.js"></script>
</body>
</html>