<?php 

include('./conexao/conexao.php');

if (!isset($_SESSION)) {
    session_start();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['pass'])) {
    $idUsuario = intval($_SESSION['idUsuario']);
    $password = $mysqli->real_escape_string($_POST['pass']);

    $stmt = $mysqli->prepare("SELECT senha FROM usuarios WHERE idUsuarios = ?");
    $stmt->bind_param("i", $idUsuario);
    $stmt->execute();
    $stmt->bind_result($pass);
    $stmt->fetch();
    $stmt->close();

    if (password_verify($password, $pass)) {
        $stmt = $mysqli->prepare("DELETE FROM usuarios WHERE idUsuarios = ?");
        $stmt->bind_param("i", $idUsuario);
        $stmt->execute();
        $stmt->close();

        session_destroy();
        header('Location: ./index.php');
        exit;
    } else {
        $_SESSION['errorPass'] = "Senha incorreta, tente novamente!";
        header('Location: ./perfil.php');
        exit;
    }
}

?>
