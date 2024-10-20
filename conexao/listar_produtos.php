<?php
include('./protect.php');
require_once './conexao.php';

$sql_query = "SELECT * FROM produtos WHERE categoria = 'prato'";
$stmt = $mysqli->prepare($sql_query);
$stmt->execute();
$result = $stmt->get_result();

$sql = "SELECT * FROM produtos WHERE categoria = 'bebida'";
$stmt = $mysqli->prepare($sql);
$stmt->execute();
$result_bebida = $stmt->get_result();

?>

<div id="produtos">
    <h1 style="text-align: center; margin-top: 15px;">Pratos</h1>
    <?php while ($row = $result->fetch_assoc()) { ?>
        <table>
            <thead>
                <th>Disponibilidade</th>
                <th>id</th>
                <th>Nome</th>
                <th>Descrição</th>
                <th>Preço</th>
                <th>Imagem</th>
                <th>Editar</th>
                <th>Deletar</th>
                <th>Disponível</th>
                <th>Indisponível</th>
            </thead>
            <tbody>
                <tr>
                    <td><input type="checkbox" name="ativo" value="1" <?php echo ($row['ativo'] == 1) ? 'checked' : ''; ?>>
                    </td>
                    <td><?php echo $row['idProdutos']; ?></td>
                    <td><?php echo $row['nome']; ?></td>
                    <td><?php echo $row['descricao']; ?></td>
                    <td id="desc"><?php echo "R$" . number_format($row['preco'], 2, ",", ".") ?></td>
                    <td><img height="50px" src="<?php echo $row['imagem']; ?>" alt=""></td>
                    <td><a href="./editar_produto.php?editar=<?php echo $row['idProdutos']; ?>" class="editar">Editar</a>
                    </td>
                    <td><a href="./deletar_produto.php?deletar=<?php echo $row['idProdutos']; ?>" class="deletar">Deletar</a>
                    </td>
                    <td><a href="./ativar_produto.php?atualizar=<?php echo $row['idProdutos']; ?>"
                            class="editar">Disponível</a></td>
                    <td><a href="./desativar_produto.php?atualizar=<?php echo $row['idProdutos']; ?>"
                            class="deletar">Indisponível</a></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
        <h1 style="text-align: center;">Bebidas</h1>
        <?php while ($row = $result_bebida->fetch_assoc()) { ?>
            <table>
                <thead>
                    <th>Disponibilidade</th>
                    <th>id</th>
                    <th>Nome</th>
                    <th>Descrição</th>
                    <th>Preço</th>
                    <th>Imagem</th>
                    <th>Editar</th>
                    <th>Deletar</th>
                    <th>Disponível</th>
                    <th>Indisponível</th>
                </thead>
                <tbody>
                    <tr>
                        <td><input type="checkbox" name="ativo" value="1" <?php echo ($row['ativo'] == 1) ? 'checked' : ''; ?>>
                        </td>
                        <td><?php echo $row['idProdutos']; ?></td>
                        <td><?php echo $row['nome']; ?></td>
                        <td><?php echo $row['descricao']; ?></td>
                        <td id="desc"><?php echo "R$" . number_format($row['preco'], 2, ",", ".") ?></td>
                        <td><img height="50px" src="<?php echo $row['imagem']; ?>" alt=""></td>
                        <td><a href="./editar_produto.php?editar=<?php echo $row['idProdutos']; ?>" class="editar">Editar</a>
                        </td>
                        <td><a href="./deletar_produto.php?deletar=<?php echo $row['idProdutos']; ?>" class="deletar">Deletar</a>
                        </td>
                        <td><a href="./ativar_produto.php?atualizar=<?php echo $row['idProdutos']; ?>"
                                class="editar">Disponível</a></td>
                        <td><a href="./desativar_produto.php?atualizar=<?php echo $row['idProdutos']; ?>"
                                class="deletar">Indisponível</a></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
</div>