<?php
include('./protect.php');
require_once './conexao.php';

$idAdmin = $_SESSION['idUsuario'];

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

        if (!in_array($extensao, ['jpg', 'jpeg', 'png', 'webp'])) {
            die('Tipo de arquivo não aceito!');
        }

        $path = $pasta . $novoNomeDoArquivo . "." . $extensao;
        $deuCerto = move_uploaded_file($imgPrin['tmp_name'], $path);

        if ($deuCerto) {
            $stmt = $mysqli->prepare("INSERT INTO produtos (nome, descricao, preco, imagem, categoria, idAdmin) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssdssi", $nomeDoProduto, $descricao, $preco, $path, $categoria, $_SESSION['idUsuario']);
            $stmt->execute();
            $stmt->close();

            // Fetch updated product list
            $sql_query = "SELECT * FROM produtos WHERE categoria = 'prato'";
            $stmt = $mysqli->prepare($sql_query);
            $stmt->execute();
            $result = $stmt->get_result();

            $sql = "SELECT * FROM produtos WHERE categoria = 'bebida'";
            $stmt = $mysqli->prepare($sql);
            $stmt->execute();
            $result_bebida = $stmt->get_result();

            // Output the updated list
            include 'listar_produtos.php';
        } else {
            echo "Erro ao fazer o upload do arquivo.";
            if (file_exists($path)) {
                unlink($path);
            }
        }
    } else {
        echo "Por favor, preencha todos os campos.";
    }
}

if (isset($_GET['aberto'])) {
    $ativo = isset($_GET['aberto']) ? 1 : 0;
    $_SESSION['situacao'] = 1;

    $stmt = $mysqli->prepare('UPDATE host SET situacao = ?');
    $stmt->bind_param('i', $ativo);
    $stmt->execute();
    $stmt->close();
}

if (isset($_GET['fechado'])) {
    $ativo = isset($_GET['fechado']) ? 0 : 1;
    $_SESSION['situacao'] = 0;

    $stmt = $mysqli->prepare('UPDATE host SET situacao = ?');
    $stmt->bind_param('i', $ativo);
    $stmt->execute();
    $stmt->close();
}
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
    <div style="position: absolute; left: 15px; margin: 15px 15px 0 0; font-size:1.2em;">
        <a href="./receber_pedido.php" style="color: white;">Meus pedidos</a>
    </div>
    <div style="position: absolute; left: 15px; margin: 120px 140px 0 0; font-size:1.2em;">
        <?php if ($_SESSION['user'] == "Lucas"): ?>
            <a href="./tela_de_logs.php" style="color: white;">Últimas atividades</a> <br>
            <a href="./adicionar_funcionario.php" style="color: white;">Adicionar Funcionário</a>
        <?php else: ?>
        <?php endif; ?>
    </div>
    <div class="logout" style="position: absolute; right: 0; margin: 15px 15px 0 0; font-size:1.2em;">
        <a href="./logout.php" style="color: white;">Sair</a>
    </div>
    <div class="openClose">
        <a href="./adicionar_produto.php?aberto=<?php echo $_SESSION['situacao'] ?>" class="abrir">Abrir</a>
        <a href="./adicionar_produto.php?fechado=<?php echo $_SESSION['situacao'] ?>" class="fechar">Fechar</a>
    </div>
    <div class="situacao">
        <span class="abertoFechado">
            <?php if ($_SESSION['situacao'] == 1): ?>
                <span class="aberto">Aberto</span>
            <?php else : ?>
                <span class="fechado">Fechado</span>
            <?php endif; ?>
        </span>
    </div>
    <header>
        <h1>Adicionar Produtos</h1>
        <p>Olá, <?= $_SESSION['user'] ?>! Vamos adicionar novos produtos!</p>
    </header>
    <main>
        <form action="" method="post" enctype="multipart/form-data" class="formu">
            <h1>Cadastre</h1>
            <select name="opcoes" id="opcoes"
                style="padding: 15px; margin-bottom: 15px; border-radius: 25px; border: none; box-shadow: inset 2px 2px 10px lightgray; outline: none; font-weight: bold;">
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

        <div id="produtos">
            <!-- Lista de produtos será carregada aqui via AJAX -->
        </div>
    </main>
    <script src="../js_adicionar/jquery.js"></script>
    <script src="../js_adicionar/script.js"></script>
</body>

</html>