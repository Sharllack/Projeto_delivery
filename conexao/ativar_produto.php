<?php

include('./conexao.php');

// Verifique se o formulário foi enviado
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Obtenha o valor do checkbox
    $ativo = isset($_GET['ativo']) ? 0 : 1;
    $protocolo = intval($_GET['atualizar']);
    $sql_query = "SELECT * FROM produtos WHERE id = '$protocolo'" or die($mysqli->error);
    $arquivo = $mysqli->query($sql_query);

    // Atualize o banco de dados com o novo valor
    $sql = "UPDATE produtos SET ativo = $ativo WHERE id = $protocolo";

    if ($mysqli->query($sql) === TRUE) {

        header("Location: adicionar_produto.php");
    } else {
        echo "Erro ao atualizar: " . $mysqli->error;
    }
}
?>