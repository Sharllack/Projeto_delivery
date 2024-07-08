<?php 

include('./conexao.php');

if(isset($_GET['deletar'])) {
    
    $protocolo = intval($_GET['deletar']);
    $sql_select = "SELECT * FROM produtos WHERE idProdutos = '$protocolo'" or die($mysqli->error);
    $arquivo = $mysqli->query($sql_select);

    if($arquivo->num_rows > 0) {

        $sql_delete = "DELETE FROM produtos WHERE idProdutos ='$protocolo'";
        $arquivoDelete = $mysqli->query($sql_delete);
    }
}

header('Location: adicionar_produto.php');

?>