<?php
include('./protect.php');
include('./conexao.php');

if(!isset($_SESSION)){
    session_name('admin_session');
    session_start();
}

if(isset($_GET['aberto'])) {
    $protocolo = intval($_GET['aberto']);
    $stmt = $mysqli->prepare("SELECT situacao FROM host WHERE idAdmin = '" . $_SESSION['idUsuario'] . "'");
    $stmt->execute();
    $stmt->bind_result($situacao);
    $stmt->fetch();
    $stmt->close();

    if($situacao == 0){
        $situacao = 1;
        $_SESSION['situacao'] = $situacao;
        $sqlUpdate = "UPDATE host SET situacao= ? ";
        $stmt = $mysqli->prepare($sqlUpdate);
        $stmt->bind_param("i", $situacao);
        $stmt->execute();
        $stmt->close();
    }
}

if(isset($_GET['fechado'])) {
    $protocolo = intval($_GET['fechado']);
    $stmt = $mysqli->prepare("SELECT situacao FROM host WHERE idAdmin = '" . $_SESSION['idUsuario'] . "'");
    $stmt->execute();
    $stmt->bind_result($situacao);
    $stmt->fetch();
    $stmt->close();

    if($situacao == 1){
        $situacao = 0;
        $_SESSION['situacao'] = $situacao;
        $sqlUpdate = "UPDATE host SET situacao= ? ";
        $stmt = $mysqli->prepare($sqlUpdate);
        $stmt->bind_param("i", $situacao);
        $stmt->execute();
        $stmt->close();
    }
}

if(isset($_GET['deletar'])) {
    $protocolo = intval($_GET['deletar']);
    // Preparando a consulta com prepared statement
    $stmt = $mysqli->prepare("SELECT imagem FROM produtos WHERE idProdutos = ?");
    $stmt->bind_param("i", $protocolo);
    $stmt->execute();
    $stmt->bind_result($imagem);
    $stmt->fetch();
    $stmt->close();

    // Deletando o produto e a imagem se existir
    if(file_exists($imagem) && unlink($imagem)) {
        $stmt = $mysqli->prepare("DELETE FROM produtos WHERE idProdutos = ?");
        $stmt->bind_param("i", $protocolo);
        $stmt->execute();
        $stmt->close();
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    if (isset($_FILES['imagem']) && isset($_POST['nome']) && isset($_POST['preco']) && isset($_POST['descricao']) && isset($_POST['opcoes'])) {
        $categoria = $_POST['opcoes'];
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
            // Inserindo o produto com prepared statement
            $stmt = $mysqli->prepare("INSERT INTO produtos (nome, descricao, preco, imagem, categoria, idAdmin) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssdssi", $nomeDoProduto, $descricao, $preco, $path, $categoria, $_SESSION['idUsuario']);
            $stmt->execute();
            $stmt->close();
        } else {
            echo "Erro ao fazer o upload do arquivo.";
        }
    } else {
        echo "Por favor, preencha todos os campos.";
    }
}

// Consulta para obter todos os produtos
$sql_query = "SELECT * FROM produtos WHERE categoria = 'prato' AND idAdmin = '" . $_SESSION['idUsuario'] . "'";
$result = $mysqli->query($sql_query);

$sql = "SELECT * FROM produtos WHERE categoria = 'bebida' AND idAdmin = '" . $_SESSION['idUsuario'] . "'";
$result_bebida = $mysqli->query($sql);


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
    <div style="position: absolute; left: 15px; margin: 15px 15px 0 0; font-size:1.2em;"><a href="./receber_pedido.php" style="color: white;">Meus pedidos</a></div>
    <div class="logout" style="position: absolute; right: 0; margin: 15px 15px 0 0; font-size:1.2em;"><a href="./logout.php" style="color: white;">Sair</a></div>
    <div class="openClose">
        <a href="./adicionar_produto.php?aberto=<?php echo $_SESSION['situacao']?>" class="abrir">Abrir</a>
        <a href="./adicionar_produto.php?fechado=<?php echo $_SESSION['situacao']?>" class="fechar">Fechar</a>
    </div>
    <div class="situacao">
        <span class="abertoFechado">
            <?php if($_SESSION['situacao'] == 1): ?>
                <span class="aberto">Aberto</span>
            <?php else :?>
                <span class="fechado">Fechado</span>
            <?php endif; ?>
        </span>
    </div>
    <header>
        <h1>Adicionar Produtos</h1>
        <p>Olá, <?= $_SESSION['user']?>! Vamos adicionar novos produtos!</p>
    </header>
    <main>
        <form action="" method="post" enctype="multipart/form-data">
            <h1>Cadastre</h1>
            <select name="opcoes" id="opcoes" style="padding: 15px; margin-bottom: 15px; border-radius: 25px; border: none; box-shadow: inset 2px 2px 10px lightgray; outline: none; font-weight: bold;">
                <option value="" style="text-align: center;">Selecione a Categoria</option>
                <option value="prato">Pratos</option>
                <option value="bebida">Bebidas</option>
            </select>
            <input type="text" name="nome" id="nome" placeholder="Nome do Produto" required>
            <input type="text" name="descricao" id="descricao" placeholder="Descrição do Produto">
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
            <h1 style="text-align: center; margin-top: 15px;">Pratos</h1>
            <thead>
                <th>Disponibilidade</th>
                <th>id</th>
                <th>Nome</th>
                <th>Descrição</th>
                <th>Preço</th>
                <th>Imagem</th>
                <th>Editar</th>
                <th>Deletar</th>
                <th>Disponível</th>
                <th>Indisponível</th>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td><input type="checkbox" name="ativo" value="1" <?php echo ($row['ativo'] == 1) ? 'checked' : ''; ?>></td>
                        <td><?php echo $row['idProdutos'];?></td>
                        <td><?php echo $row['nome'];?></td>
                        <td><?php echo $row['descricao'];?></td>
                        <td id="desc"><?php echo "R$" . number_format($row['preco'], 2, "," , ".")?></td>
                        <td><img height="50px" src="<?php echo $row['imagem'];?>" alt=""></td>
                        <td><a href="./editar_produto.php?editar=<?php echo $row['idProdutos'];?>" class="editar">Editar</a></td>
                        <td><a href="./deletar_produto.php?deletar=<?php echo $row['idProdutos'];?>" class="deletar">Deletar</a></td>
                        <td><a href="./ativar_produto.php?atualizar=<?php echo $row['idProdutos'];?>" class="editar">Disponível</a></td>
                        <td><a href="./desativar_produto.php?atualizar=<?php echo $row['idProdutos'];?>" class="deletar">Indisponível</a></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
        <table>
            <h1 style="text-align: center;">Bebidas</h1>
            <thead>
                <th>Disponibilidade</th>
                <th>id</th>
                <th>Nome</th>
                <th>Descrição</th>
                <th>Preço</th>
                <th>Imagem</th>
                <th>Editar</th>
                <th>Deletar</th>
                <th>Disponível</th>
                <th>Indisponível</th>
            </thead>
            <tbody>
                <?php while($row = $result_bebida->fetch_assoc()) { ?>
                    <tr>
                        <td><input type="checkbox" name="ativo" value="1" <?php echo ($row['ativo'] == 1) ? 'checked' : ''; ?>></td>
                        <td><?php echo $row['idProdutos'];?></td>
                        <td><?php echo $row['nome'];?></td>
                        <td><?php echo $row['descricao'];?></td>
                        <td id="desc"><?php echo "R$" . number_format($row['preco'], 2, "," , ".")?></td>
                        <td><img height="50px" src="<?php echo $row['imagem'];?>" alt=""></td>
                        <td><a href="./editar_produto.php?editar=<?php echo $row['idProdutos'];?>" class="editar">Editar</a></td>
                        <td><a href="./deletar_produto.php?deletar=<?php echo $row['idProdutos'];?>" class="deletar">Deletar</a></td>
                        <td><a href="./ativar_produto.php?atualizar=<?php echo $row['idProdutos'];?>" class="editar">Disponível</a></td>
                        <td><a href="./desativar_produto.php?atualizar=<?php echo $row['idProdutos'];?>" class="deletar">Indisponível</a></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </main>
    <script src="../js_adicionar/script.js"></script>
</body>
</html>
