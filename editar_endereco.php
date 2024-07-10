<?php 

include('./conexao/conexao.php');

if(!isset($_SESSION)) {
    session_start();
}

$idUsuario = $_SESSION['idUsuario'];

if($_SERVER["REQUEST_METHOD"] == "POST"); {
    if(isset($_POST['nome']) && isset($_POST['cell']) && isset($_POST['cep']) && isset($_POST['estado']) && isset($_POST['cidade']) && isset($_POST['bairro']) && isset($_POST['rua']) && isset($_POST['numero']) && isset($_POST['complemento']) && isset($_POST['referencia'])) {

        $nome = $_POST['nome'];
        $cell = $_POST['cell'];
        $cep = $_POST['cep'];
        $estado = $_POST['estado'];
        $cidade = $_POST['cidade'];
        $bairro = $_POST['bairro'];
        $rua = $_POST['rua'];
        $numero = $_POST['numero'];
        $complemento = $_POST['complemento'];
        $referencia = $_POST['referencia'];

        $stmt = $mysqli->prepare("UPDATE usuarios SET nomeCliente = ?, cell = ?, estado = ?, cidade = ?, bairro = ?, rua = ?, numero = ?, complemento = ?, referencia = ? WHERE idUsuarios = ?");
        $stmt->bind_param("sssssssssi", $nome, $cell, $estado, $cidade, $bairro, $rua, $numero, $complemento, $referencia, $idUsuario);
        $stmt->execute();
        $stmt->close();

        sleep(2);

        header("Location: ./pagamento_endereco.php");
        exit;
    }

    $sql = "SELECT * FROM usuarios WHERE idUsuarios = $idUsuario";
    $result = $mysqli->query($sql) or die ($mysqli->error);
    while($row = mysqli_fetch_assoc($result)) {
        $nome = $row['nomeCliente'];
        $contato = $row['cell'];
    }
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./estilo_cadastro_usuario/style.css">
    <link rel="stylesheet" href="./estilo_cadastro_usuario/media_querie.css">
    <title>Cadastre-se</title>
</head>
<body>
    
    <main>
        <section>
        <h1>Atualize o seu endereço</h1>
            <form action="" method="post">
                <div class="inpu">
                    <input type="text" name="nome" id="nome" placeholder="nome" required value="<?php echo $nome?>">
                </div>
                <div class="inpu">
                    <input type="text" name="cell" id="cell" placeholder="Número para Contato" required value="<?php echo $contato?>">
                </div>
                <div class="inpu">
                    <input type="text" name="cep" id="cep" placeholder="CEP" required>
                </div>
                <div class="inpu">
                    <input type="text" name="estado" id="estado" placeholder="Estado" required>
                </div>
                <div class="inpu">
                    <input type="text" name="cidade" id="cidade" placeholder="Cidade" required>
                </div>
                <div class="inpu">
                    <input type="text" name="bairro" id="bairro" placeholder="Bairro" required>
                </div>
                <div class="inpu">
                    <input type="text" name="rua" id="rua" placeholder="Logradouro" required>
                </div>
                <div class="inpu">
                    <input type="text" name="numero" id="numero" placeholder="Número" required>
                </div>
                <div class="inpu">
                    <input type="text" name="complemento" id="complemento" placeholder="Complemento(Opcional)">
                </div>
                <div class="inpu">
                    <input type="text" name="referencia" id="referencia" placeholder="Ponto de Referência" required>
                </div>
                <div class="btn">
                    <button type="submit">Cadastrar</button>
                    <button type="reset">Limpar</button>
                </div>
            </form>
        </section>

        <div class="img">
            <img src="./imagens/imagens_cadastro_usuario/culinaria-mineira-cpt.jpg" alt="imagem de comida">
        </div>
    </main>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.js"></script>
    <script src="./js_cadastro_usuario/cep.js"></script>
    <script src="./js_cadastro_usuario/formatacao.js"></script>
    <script src="./js_cadastro_usuario/funcoes.js"></script>
</body>
</html>