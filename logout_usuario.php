<?php

session_name('user_session');
session_start(); // Inicia a sessão PHP
session_destroy(); // Destrói a sessão
header("Location: ./index.php"); // Redireciona para a tela principal
exit();

?>