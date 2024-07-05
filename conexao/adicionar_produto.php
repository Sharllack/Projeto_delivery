<?php

include('./protect.php');
include('./conexao.php');

if(isset($_GET['deletar'])) {
    
    $protocolo = intval($_GET['deletar']);
    $sql_query = $mysqli->query("SELECT * FROM produtos WHERE id = '$protocolo'") or die($mysqli->error);
    $arquivo = $sql_query->fetch_assoc();

    if(unlink($arquivo['imagem'])) {
        $deu_certo = $mysqli->query("DELETE FROM produtos WHERE id = '$protocolo'") or die($mysqli->error);
    }
}

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (isset($_FILES['imagem']) && isset($_POST['nome']) && isset($_POST['preco']) && isset($_POST['descricao'])) {
            $nomeDoProduto = $_POST['nome'];
            $preco = $_POST['preco'];
            $descricao = $_POST['descricao'];
            $imgPrin = $_FILES['imagem'];

            $pasta = "arquivos/";
            $nomeDoArquivo = $imgPrin['name'];
            $novoNomeDoArquivo = uniqid();
            $extensao = strtolower(pathinfo($nomeDoArquivo, PATHINFO_EXTENSION));

            if (!in_array($extensao, array("jpg", "jpeg", "png", "webp"))) {
                die('Tipo de arquivo não aceito!');
            }

            $path = $pasta . $novoNomeDoArquivo . "." . $extensao;
            $deuCerto = move_uploaded_file($imgPrin['tmp_name'], $path);

            if($deuCerto) {
                $mysqli->query("INSERT INTO produtos (nome, descricao, preco, imagem) VALUES('$nomeDoProduto', '$descricao', '$preco', '$path')") or die($mysqli->error);

            } else {
                echo "Erro ao fazer o upload do arquivo.";
            }
        } else {
            echo "Por favor, preencha todos os campos.";
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
    <title>Adicionar Produtos</title>
</head>
<body>
    <div class="logout" style="position: absolute; right: 0; margin: 15px 15px 0 0; font-size:1.2em;"><a href="./logout.php" style="color: white;">Sair</a></div>
    <header>
            <h1>Adicionar Produtos</h1>
            <p>Olá, <?= $_SESSION['user']?>! Vamos adicionar novos produtos!</p>
    </header>
    <main>
        <form action="" method="post" enctype="multipart/form-data">
            <h1>Cadastre</h1>
            <input type="text" name="nome" id="nome" placeholder="Nome do Produto" required>
            <input type="text" name="descricao" id="descricao" placeholder="Descrição do Produto" required>
            <input type="number" name="preco" id="preco" placeholder="Valor do Produto" step="0.01" required>
            <label for="imagem" class="file">
                <span class="span1">Selecione a Imagem</span>
                <span class="span">SELECIONAR</span>
            </label>
            <input type="file" name="imagem" id="imagem" required>
            <div class="botoes">
                <button type="submit">CADASTRAR</button>
                <p id="res"></p>
                <button type="reset">LIMPAR</button>
            </div>
        </form>

        <table>
            <thead>
                <th>id</th>
                <th>Nome</th>
                <th>Descrição</th>
                <th>Preço</th>
                <th>Imagem</th>
                <th>Editar</th>
                <th>Deletar</th>
            </thead>
            <tbody>
            <?php 
                while($row = $result->fetch_assoc()) {
                ?>
                <tr>
                    <td><?php echo $row['id'];?></td>
                    <td><?php echo $row['nome'];?></td>
                    <td><?php echo $row['descricao'];?></td>
                    <td id="desc"><?php echo "R$" . number_format($row['preco'], 2, "," , ".")?></td>
                    <td><img height="50px" src="<?php echo $row['imagem'];?>" alt=""></img></td>
                    <th><a href="./editar_produto.php?editar=<?php echo $row['id'];?>" class="editar">Editar</a></th>
                    <th><a href="./deletar_produto.php?deletar=<?php echo $row['id'];?>" class="deletar">Deletar</a></th>
                </tr>
                <?php 
                }
                ?>
            </tbody>
        </table>
    </main>
    <script src="../js_adicionar/script.js"></script>
</body>
</html>
