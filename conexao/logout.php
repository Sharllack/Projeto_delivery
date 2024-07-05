<?php 

session_name('admin_session');
session_start(); // Inicia a sessão PHP
session_destroy(); // Destrói a sessão
header("Location: ./login_adm.php"); // Redireciona para a tela principal
exit();

?>