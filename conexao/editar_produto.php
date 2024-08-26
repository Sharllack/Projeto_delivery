<?php 

include('./protect.php');
require_once './conexao.php';

if(isset($_GET['editar'])) {
    
    $protocolo = intval($_GET['editar']);
    $sql_query = "SELECT * FROM produtos WHERE idProdutos = '$protocolo'" or die($mysqli->error);
    $arquivo = $mysqli->query($sql_query);

    while($user_data = mysqli_fetch_assoc($arquivo)) {
        $nomeDoProduto = $user_data['nome'];
        $preco = $user_data['preco'];
        $descricao = $user_data['descricao'];
        $imgPrin = $user_data['imagem'];
    }
}

$sql_query = "SELECT * FROM produtos" or die($mysqli->error);
$result = $mysqli->query($sql_query);

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../estilo_adicionar/index.css">
    <link rel="stylesheet" href="../estilo_adicionar/media_querie.css">
    <title>Editar Produtos</title>
</head>

<body>
    <header>
        <h1>Editar Produtos</h1>
    </header>
    <main>
        <form action="saveEdit.php" method="post" enctype="multipart/form-data">
            <h1>Faça a Edição</h1>
            <input type="text" name="nome" id="nome" placeholder="Nome do Produto" value="<?php echo $nomeDoProduto ?>"
                required>
            <input type="text" name="descricao" id="descricao" placeholder="Descrição do Produto"
                value="<?php echo $descricao ?>">
            <input type="number" name="preco" id="preco" placeholder="Valor do Produto" step="0.01"
                value="<?php echo $preco ?>" required>
            <label for="imagem" class="file">
                <span class="span1">Selecione a Imagem</span>
                <span class="span">SELECIONAR</span>
            </label>
            <input type="file" name="imagem" id="imagem">
            <input type="hidden" name="protocolo" value="<?php echo $protocolo ?>">
            <div class="botoes">
                <button type="submit" name="update" id="update">CADASTRAR</button>
                <p id="res"></p>
                <button type="reset">LIMPAR</button>
            </div>
        </form>
    </main>
    <script src="../js_adicionar/script.js"></script>
</body>

</html>