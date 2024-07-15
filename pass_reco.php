<?php 

include('./conexao/conexao.php');

use 

if(!isset($_SESSION)){
    session_start();
}

if($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $mysqli->real_escape_string($_POST['email']);

    $sql = "SELECT * FROM usuarios WHERE email = '$email'";
    $result = $mysqli->query($sql);

    if ($result->num_rows > 0) {
        // Gerar um token único seguro usando hash
        $token = bin2hex(random_bytes(32)); // Gera 32 bytes aleatórios e converte para hexadecimal

        
    } else {
        echo "Este email não está cadastrado.";
    }

    $mysqli->close();
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./estilo_pass_reco/style.css">
    <link rel="stylesheet" href="./estilo_pass_reco/media_query.css">
    <title>Document</title>
</head>
<body>
    <div class="voltar">
        <a href="./login_usuario.php">Voltar</a>
    </div>
    <form action="" method="post">
        <h1>Recuperação</h1>
        <input type="email" name="email" id="eamil" placeholder="Preencha o seu E-mail">
        <div class="btn">
            <button type="submit">Enviar</button>
            <button type="reset">Limpar</button>
        </div>
    </form>
</body>
</html>