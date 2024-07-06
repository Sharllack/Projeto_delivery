<?php 

if(!isset($_SESSION)) {
    session_name('admin_session');
    session_start();
}

if(!isset($_SESSION['user'])) {
    die("Você não tem acesso à essa Página! <p><a href='login_adm.php'>Entrar</a></p>");
}

?>