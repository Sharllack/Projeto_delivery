<?php

include('./conexao.php');

$usu_error = '';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (
        isset($_POST['user'])
        && isset($_POST['senha'])
        && isset($_POST['cSenha'])
    ) {

        $usuario = $_POST['user'];
        $senha = password_hash($_POST['senha'], PASSWORD_DEFAULT);

        $sql_user = "SELECT * FROM host WHERE user = '$usuario' LIMIT 1";
        $result_user = $mysqli->query($sql_user);

        if ($result_user->num_rows > 0) {
            $usu_error = "Usuario já cadastrado.";
        }

        if (empty($usu_error) && empty($cell_error) && empty($email_error)) {
            $stmt = $mysqli->prepare("INSERT INTO host (user, pass) VALUES(?, ?)");
            $stmt->bind_param("ss", $usuario, $senha);
            $stmt->execute();
            $stmt->close();

            sleep(2);

            header("Location: ./login_adm.php");
            exit;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../estilo_adicionar_funcionario/style.css">
    <link rel="shortcut icon" href="../imagens/favicon.ico" type="image/x-icon">
    <title>Cadastre-se</title>
</head>

<body>

    <div class="loading">
        <div class="load">

        </div>
    </div>

    <div class="voltar">
        <a href="./adicionar_produto.php">Voltar</a>
    </div>
    <main>
        <section>
            <h1 class="titleForm">Cadastre-se</h1>
            <form action="" method="post">
                <div class="inpu" style="margin-bottom: 15px;">
                    <input type="text" name="user" id="user" placeholder="Usuário" required
                        value="<?php echo isset($_POST['user']) ? $_POST['user'] : ''; ?>">
                    <p style="font-size: .8em; color: red; margin-left: 15px;"><?php echo $usu_error ?></p>
                </div>
                <div class="inpu" id="bgSenha" style="margin-bottom: 15px;">
                    <input type="password" name="senha" id="senha" placeholder="Senha" required>
                    <span class="resPas"></span>
                </div>
                <div class="inpu" id="bgCsenha">
                    <input type="password" name="cSenha" id="cSenha" placeholder="Confirme a Senha" required>
                    <span class="resPass"></span>
                </div>
                <p class="resposta" style="color: white;"></p>
                <div class="btn" style="margin-top: 15px;">
                    <button type="submit" class="cadas">Cadastrar</button>
                    <button type="reset">Limpar</button>
                </div>
            </form>
        </section>
        <div class="img">
            <img src="../imagens/imagens_cadastro_usuario/comida-brasileira-simples-com-espaco-de-copia_23-2148739186.avif"
                alt="">
        </div>
    </main>
    <script src="../js_cadastro_usuario/funcoes.js"></script>
</body>

</html>