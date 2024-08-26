<?php

    include('./conexao/conexao.php');

    $error_tot = '';

    if($_SERVER["REQUEST_METHOD"] == "POST"){
        $email = $mysqli->real_escape_string($_POST['email']);
        $user = $mysqli->real_escape_string($_POST['user']);

        $stmt1 = $mysqli->prepare("SELECT * FROM usuarios WHERE email = ? AND usuario = ? LIMIT 1");
        $stmt1->bind_param("ss", $email, $user);
        $stmt1->execute();
        $stmt1->store_result();

        if($stmt1->num_rows > 0) {
            // Redirecionar para a página de recuperação de senha se o e-mail e usuário forem encontrados
            header("Location: ./recovery.php?usuario=" . $user);
            exit;
        } else {
            // Se nenhum registro for encontrado, definir mensagem de erro personalizada
            $error_tot = "Dados incompatíveis. Verifique o e-mail e usuário informados.";
        }

        $stmt1->close();
    }
?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./estilo_pass_reco/style.css">
    <link rel="stylesheet" href="./estilo_pass_reco/media_query.css">
    <title>Recuperação de Senha</title>
</head>

<body>
    <div class="voltar">
        <a href="./login_usuario.php">Voltar</a>
    </div>
    <form action="" method="post">
        <h1>Recuperação</h1>
        <div class="input">
            <input type="text" name="user" id="user" placeholder="Digite o seu Usuário"
                value="<?php echo isset($_POST['user']) ? $_POST['user'] : '' ?>" style="margin-bottom: 10px;">
            <input type="email" name="email" id="email" placeholder="Preencha o seu E-mail"
                value="<?php echo isset($_POST['email']) ? $_POST['email'] : '' ?>">
        </div>
        <p style="color: red; font-size: .9em;"><?php echo $error_tot?></p>
        <div class="btn">
            <button type="submit">Enviar</button>
            <button type="reset">Limpar</button>
        </div>
    </form>
</body>

</html>