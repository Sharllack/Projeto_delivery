<?php

include('./protect.php');
require_once './conexao.php';

$sql = "
SELECT * FROM logs";
$stmt = $mysqli->prepare($sql);
$stmt->execute();
$result = $stmt->get_result();

?>
<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../estilo_logs/style.css">
    <title>Atividades</title>
</head>

<body>
    <header>
        <a href="./adicionar_produto.php"
            style="color:white; position: absolute; left: 15px; top: 15px; font-size: .8em;">Voltar</a>
        <h1>
            Alterações no Sistema
        </h1>
        <p>
            Aqui estão presentes as alterações feitas por clientes e funcionários.
        </p>
    </header>
    <section class="atividades">
        <h1 id="titu">Atividades</h1>
        <input type="search" name="search" id="search" placeholder="Pesquisar...">
        <table>
            <thead>
                <th>Id</th>
                <th>Atividade</th>
                <th>Data</th>
                <th>Id Cliente</th>
                <th>Id Administrador</th>
            </thead>
            <tbody id="tbody">
                <?php while ($row = $result->fetch_assoc()) { ?>
                    <tr>
                        <td> <?php echo $row['id_logs'] ?> </td>
                        <td> <?php echo $row['acao'] ?> </td>
                        <td> <?php echo date("d/m/Y H:i", strtotime($row['data'])) ?></td>
                        <td> <?php echo $row['id_cliente'] ?></td>
                        <td> <?php echo $row['id_host'] ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </section>
    <script src="../js_logs/script.js"></script>
</body>

</html>