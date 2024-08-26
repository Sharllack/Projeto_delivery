<?php

include('./conexao/conexao.php');

if(!isset($_SESSION)) {
    session_start();
};

$idUser = '';

$distanceKm = 0;
$cost = 0;

if(!isset($_SESSION['user'])) {
    $bairro = '';
} else {
    $idUser = $_SESSION['idUsuario'];
    $bairro = $_SESSION['bairro'];
    $idusuario = $_SESSION['user'];

    // Sua chave de API do Google Maps
    $apiKey = $_ENV['API_KEY'];

    // Endereços de origem e destino
    $origin = 'Rua João Ribeiro, 40, Vila Centenário';
    $destination = $_SESSION['rua'] . ", " . $_SESSION['numero'] . ", " . $_SESSION['bairro'];

    // Codifica os endereços para uso na URL
    $originEncoded = urlencode($origin);
    $destinationEncoded = urlencode($destination);

    // Monta a URL da requisição
    $url = "https://maps.googleapis.com/maps/api/distancematrix/json?units=metric&origins=$originEncoded&destinations=$destinationEncoded&key=$apiKey";

    // Inicializa o cURL
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    // Executa a requisição e obtém a resposta
    $response = curl_exec($ch);

    // Fecha o cURL
    curl_close($ch);

    // Decodifica a resposta JSON
    $data = json_decode($response, true);

    // Verifica se a requisição foi bem-sucedida
    if ($data['status'] === 'OK') {
        // Obtém a distância
        $distance = $data['rows'][0]['elements'][0]['distance']['text'];
        $distanceKm = $data['rows'][0]['elements'][0]['distance']['value']/1000;

        // Define a taxa por quilômetro
        $ratePerKm = 1.00; // $1.00 por quilômetro

        // Calcula o custo
        $cost = $distanceKm * $ratePerKm;
    }
}

$stmt = $mysqli->prepare("SELECT situacao FROM host");
$stmt->execute();
$stmt->bind_result($situacao);
$stmt->fetch();
$stmt->close();

$_SESSION['taxa'] = true;

$sql_query = "SELECT * FROM produtos WHERE ativo = 1 AND categoria = 'prato'";
$result = $mysqli->query($sql_query);

$sql = "SELECT * FROM produtos WHERE ativo = 1 AND categoria = 'bebida'";
$result_bebibas = $mysqli->query($sql);

if (empty($bairro)) {
    $taxaAtual = 'Pendente de Verificação!';

    $stmt = $mysqli->prepare("UPDATE usuarios SET taxa = ? WHERE idUsuarios = ?");
    $stmt->bind_param("si", $taxaAtual, $idUser);
    $stmt->execute();
    $stmt->close();
} elseif ($bairro != 'Vila Centenário') {
    if ($distanceKm > 5) {
        $_SESSION['taxa'] = 'Endereço não atendido!';

        $stmt = $mysqli->prepare("UPDATE usuarios SET taxa = ? WHERE idUsuarios = ?");
        $stmt->bind_param("si", $_SESSION['taxa'], $idUser);
        $stmt->execute();
        $stmt->close();
    } else {
        $fixedRate = 3.00; // Taxa fixa de R$3,00
        $totalCost = $fixedRate + $cost; // Total em valor numérico
        $_SESSION['taxa'] = "R$ " . number_format($totalCost, 2, ",", ".");

        $stmt = $mysqli->prepare("UPDATE usuarios SET taxa = ? WHERE idUsuarios = ?");
        $stmt->bind_param("si", $_SESSION['taxa'], $idUser);
        $stmt->execute();
        $stmt->close();
    }
} elseif ($bairro == 'Vila Centenário') {
    $_SESSION['taxa'] = 'R$ 3,00';

    $stmt = $mysqli->prepare("UPDATE usuarios SET taxa = ? WHERE idUsuarios = ?");
    $stmt->bind_param("si", $_SESSION['taxa'], $idUser);
    $stmt->execute();
    $stmt->close();
}
if (!empty($idUser)) {
    $stmt = $mysqli->prepare("SELECT taxa FROM usuarios WHERE idUsuarios = ?");
    $stmt->bind_param("i", $idUser);
    $stmt->execute();
    $stmt->bind_result($taxaAtual);
    $stmt->fetch();
    $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./estilo_principal/index.css">
    <link rel="stylesheet" href="./estilo_principal/media_querie.css">
    <script src="https://unpkg.com/scrollreveal"></script>
    <link rel="stylesheet" href="./estilo_principal/swiper-bundle.min.css">
    <link rel="shortcut icon" href="./imagens/favicon.ico" type="image/x-icon">
    <title>Página Inicial</title>
</head>

<body>
    <div class="loading">
        <div class="load">

        </div>
    </div>

    <?php if(!isset($_SESSION['user'])):?>
    <div class="logout" style="display: none;">
    </div>
    <?php else: ?>
    <div class="logout" style=" position: fixed; z-index: 9998;">
        <a href="./logout_usuario.php">Sair</a>
    </div>
    <?php endif; ?>

    <header>
        <h1 class="nomeLoja">Quentinhas do Lucas</h1>
        <div class="darkMode">
            <label id="toggle-button">
                <input type="checkbox" id="toggle">
                <span class="slider1 round"></span>
            </label>
        </div>
        <div class="home">
            <a href="./index.php">
                <img src="./imagens/imagens_pincipal/home_24dp_E8EAED_FILL0_wght400_GRAD0_opsz24.png" height="30px"
                    width="30px" alt="Home">
            </a>
        </div>
    </header>

    <section class="agarra">

    </section>
    <main>
        <div class="infoPrincipal">
            <img src="./imagens/imagens_pincipal/comida-criolla-peru-peruvian-food-260nw-2191344515.webp"
                alt="Logo do Restaurante" class="logoRestaurante">
            <p class="tempoDeEntrega">Restaurante • 20-90 min •
                <span class="sit">
                    <?php if($situacao == 1): ?>
                    <span class="situ">Aberto</span>
                    <?php else :?>
                    <span class="situ">Fechado</span>
                    <?php endif; ?>
                </span>
            </p>
            <p class="taxa">Taxa de entrega: <strong id="taxa"><?php
             echo $taxaAtual?></strong></p>
            <p class="horarioDeFuncionamento"><strong>Horário de funcionamento hoje:</strong></p>
            <p class="hora"><strong>11:00 às 15:00</strong></p>
            <hr class="hr">
            <?php if(!isset($_SESSION['user'])):?>
            <a href="./login_usuario.php" class="logBtn">FAÇA LOGIN</a>
            <?php else: ?>
            <div class="saudacao">
                <p class="nomeDoCliente">Seja Bem-Vindo(a), <strong><?php echo $_SESSION['nome']?></strong>!</p>
                <a href="./perfil.php" class="perfil"><img
                        src="./imagens/person_24dp_E8EAED_FILL0_wght400_GRAD0_opsz24.png" alt="Perfil"></a>
            </div>
            <?php endif; ?>
        </div>
        <section style="padding: 15px;" class="secPratos">
            <h1 class="pratosTitle">ESCOLHA O SEU PRATO</h1>
            <div class="pratosContainer">
                <?php while($row = $result->fetch_assoc()) { ?>
                <?php

                    if(!isset($_SESSION['idUsuario'])) {
                        $off = './login_usuario.php';
                    } else {
                        $off = './complemento_produto.php?comprar=' . $row["idProdutos"];
                    }

                    ?>
                <a href="<?php echo $off; ?>" class="linkPratos">
                    <div class="pratos">
                        <div class="imgPrato">
                            <img height="80px" width="80px" src="./conexao/<?php echo $row['imagem'];?>" alt="">
                        </div>
                        <div class="infoGeral">
                            <div class="info">
                                <h1 class="nomeDoPrato"><?php echo $row['nome']?></h1>
                                <p style="color: rgb(119, 119, 119);" class="descricaoDoPrato">
                                    (<?php echo $row['descricao']?>)</p>
                            </div>
                            <div class="preco">
                                <p><?php echo "R$" . number_format($row['preco'], 2, "," , ".")?></p>
                            </div>
                        </div>
                    </div>
                </a>
                <?php } ?>
            </div>
        </section>
        <section style="padding: 15px;" class="secBebidas">
            <h1 class="titleBebidas">ESCOLHA A SUA BEBIDA</h1>
            <div class="pratosContainer">
                <?php while($row = $result_bebibas->fetch_assoc()) { ?>
                <?php

                    if(!isset($_SESSION['idUsuario'])) {
                        $off = './login_usuario.php';
                    } else {
                        $off = './complemento_produto.php?comprar=' . $row["idProdutos"];
                    }

                    ?>
                <a href="<?php echo $off; ?>" class="linkPratos">
                    <div class="pratos">
                        <div class="imgPrato">
                            <img height="80px" width="80px" src="./conexao/<?php echo $row['imagem'];?>" alt="">
                        </div>
                        <div class="infoGeral">
                            <div class="info">
                                <h1 class="nomeDoPrato"><?php echo $row['nome']?></h1>
                                <p style="color: rgb(119, 119, 119);" class="descricaoDoPrato">
                                    (<?php echo $row['descricao']?>)</p>
                            </div>
                            <div class="preco">
                                <p><?php echo "R$" . number_format($row['preco'], 2, "," , ".")?></p>
                            </div>
                        </div>
                    </div>
                </a>
                <?php } ?>
            </div>
        </section>
    </main>
    <div class="btns">
        <?php if(isset($_SESSION['user'])):?>
        <div class="shopCart">
            <a href="./carrinho.php" class="carrinho">
                <img src="./imagens/imagens_pincipal/shopping_cart_24dp_E8EAED_FILL0_wght400_GRAD0_opsz24.png"
                    height="30px" width="30px" alt="Carrinho">
                <div class="qtdCar">
                    <input type="number" readonly name="qtdCar" id="qtdCar" value="<?php

                    // Prepare a consulta SQL
                    $stmt = $mysqli->prepare('SELECT SUM(qtd) FROM itenscarrinho WHERE idUsuario = ?');

                    if ($stmt) {
                        // Bind o parâmetro
                        $stmt->bind_param('i', $idUser);

                        // Execute a consulta
                        $stmt->execute();

                        // Bind o resultado
                        $stmt->bind_result($qtdCar);

                        // Obtenha o valor
                        $stmt->fetch();

                        // Feche a declaração
                        $stmt->close();

                        // Se $qtdCar for NULL, define como 0
                        $qtdCar = $qtdCar ?? 0;

                        // Exiba o valor
                        echo htmlspecialchars($qtdCar);
                    } else {
                        // Em caso de erro na preparação da consulta, exiba 0
                        echo '0';
                    }
                    ?>">
                </div>
            </a>
        </div>
        <?php else: ?>
        <div class="shopCart">
            <a href="./login_usuario.php" class="carrinho">
                <img src="./imagens/imagens_pincipal/shopping_cart_24dp_E8EAED_FILL0_wght400_GRAD0_opsz24.png"
                    height="30px" width="30px" alt="Carrinho">
            </a>
        </div>
        <?php endif; ?>
        <div class="wpp">
            <a href="https://wa.me/5521990420932?text=Olá! Eu gostaria de tirar uma dúvida!" target="_blank"><img
                    src="./imagens/whats_logo.png" alt="whatsapp" id="wpp"></a>
        </div>
    </div>
    <footer>
        <div class="infosFooter">
            <div class="outrasInfos">
                <h1>Contatos</h1>
                <div class="redes">
                    <a href="https://wa.me/5521990420932?text=Olá! Eu gostaria de tirar uma dúvida!"
                        target="_blank"><img src="./imagens/logos/sm_5b321c98efaa6.png" alt="logo WhatsApp"
                            class="logos" id="logoWpp"></img>+55(21)99042-0932</a>
                </div>
                <div class="redes">
                    <a href="https://www.instagram.com/lucas_mnzs_/" target="_blank"><img
                            src="./imagens/logos/images-removebg-preview.png" alt="Logo instagram" class="logos"
                            id="logoInsta"></img>Instagram</a>
                </div>
            </div>
            <div class="endereco">
                <h1>Endereço</h1>
                <p>Nos visite em nossa loja física no endereço:</p>
                <p>Rua Dr. Furquim Mendes, 990/Vila Centenário, Duque de Caxias/RJ</p>
            </div>
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3678.8321513755886!2d-43.316062925162875!3d-22.771609533053063!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x997aa3bcff72fb%3A0x77d5c3defa357629!2sR.%20Dr.%20Furquim%20Mendes%2C%20990%20-%20Vila%20Centenario%2C%20Duque%20de%20Caxias%20-%20RJ%2C%2025030-170!5e0!3m2!1spt-BR!2sbr!4v1722418653219!5m2!1spt-BR!2sbr"
                width="400" height="300" style="border:0;" allowfullscreen="" loading="lazy"
                referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
        <div class="devInfo">
            <p>Site desenvolvido por <a href="https://www.linkedin.com/in/lucas-menezes-962075314/"
                    target="_blank">Lucas M.</a></p>
        </div>
    </footer>
    <div class="borda1"></div>
    <div class="borda2"></div>
    <div class="borda3"></div>
    
    <script src="./js_pincipal/swiper-bundle.min.js"></script>
    <script src="./js_pincipal/animacao.js"></script>
    <script src="./js_pincipal/funcoes.js"></script>
</body>

</html>