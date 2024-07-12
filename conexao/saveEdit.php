<?php 

    include('./conexao.php');

    if(isset($_POST['update'])) {

        $protocolo = $_POST['protocolo'];
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
            $sqlUpdate = "UPDATE produtos SET nome= ?, descricao= ?, preco= ?, imagem= ? WHERE idProdutos = ?";
            $stmt = $mysqli->prepare($sqlUpdate);
            $stmt->bind_param("ssdsi", $nomeDoProduto, $descricao, $preco, $path, $protocolo);
            $stmt->execute();
            $stmt->close();
        }
    }

    header('Location: adicionar_produto.php');

?>