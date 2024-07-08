<?php 

include('./conexao/conexao.php');

if(isset($_GET['remover'])) {
    $protocolo = intval($_GET['remover']);
    $sql_select = "SELECT * FROM carrinho WHERE idCarrinho = '$protocolo'" or die($mysqli->error);
    $arquivo = $mysqli->query($sql_select);

    if($arquivo->num_rows > 0) {

        $sql_delete = "DELETE FROM carrinho WHERE idCarrinho ='$protocolo'";
        $arquivoDelete = $mysqli->query($sql_delete);
    }
}

header('Location: ./carrinho.php');

?>