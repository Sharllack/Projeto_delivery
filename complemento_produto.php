<?php 

include('./conexao/conexao.php');

if(!isset($_SESSION)) {
    session_name('user_session');
    session_start();
};

if(isset($_GET['comprar'])) {
    
    $protocolo = intval($_GET['comprar']);
    $sql_query = "SELECT * FROM produtos WHERE id = '$protocolo'" or die($mysqli->error);
    $arquivo = $mysqli->query($sql_query);

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
        <a href="./index.php" style="padding: 10px 20px;">
            <img src="./imagens/imagens_pincipal/home_24dp_E8EAED_FILL0_wght400_GRAD0_opsz24.png" height="30px" width="30px" alt="Home">
        </a>
    </div>
    <div class="shopCart">
        <a href="./carrinho.php" style="padding: 10px 20px;">
            <img src="./imagens/imagens_pincipal/shopping_cart_24dp_E8EAED_FILL0_wght400_GRAD0_opsz24.png" height="30px" width="30px" alt="Carrinho">
        </a>
    </div>
    <div style="position: fixed; right:15px; top:20px; z-index: 9999;">
        <span class="openOrClosed">fechado</span>
    </div>
    <?php if(!isset($_SESSION['user'])):?>
        <div class="logout" style="display: none;">
            <a href="./logout_usuario.php" style="color: white; padding: 10px 20px; font-weight: bold; text-decoration: none;">Sair</a>
        </div>
    <?php else: ?>
        <div class="logout" style=" position: fixed; z-index: 9999;">
            <a href="./logout_usuario.php" style="color: white; padding: 10px 20px; font-weight: bold; text-decoration: none;">Sair</a>
        </div>
    <?php endif; ?>

    <header>
        <h1>Quentinhas da Vanessa</h1>
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
        <section>
            <h1>Selecione 1 Complemento</h1>
            <input type="checkbox" name="fritas" id="fritas"><span class="complementoOpc">Fritas</span><br><br>
            <input type="checkbox" name="purê" id="purê"><span class="complementoOpc">Purê</span><br><br>
            <input type="checkbox" name="salada" id="salada"><span class="complementoOpc">Salada</span><br><br>
        </section>
        <h2 class="text">Alguma observação?</h2>
        <textarea name="obs" id="obs" placeholder="Ex.: Ponto da carne, retire o arroz, etc.."></textarea>
        <a href="#" class="btn">ADICIONAR</a>
    </main>
    <script src="./js_complemento_produto/funcoes.js"></script>
</body>
</html>