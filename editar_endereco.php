<?php 

include('./conexao/conexao.php');

if(!isset($_SESSION)) {
    session_start();
}

$idUsuario = $_SESSION['idUsuario'];
$error = '';

if($_SERVER["REQUEST_METHOD"] == "POST") {
    if(isset($_POST['cep']) && isset($_POST['estado']) && isset($_POST['cidade']) && isset($_POST['bairro']) && isset($_POST['rua']) && isset($_POST['numero']) && isset($_POST['complemento']) && isset($_POST['referencia'])) {

        $cep = $_POST['cep'];
        $estado = $_POST['estado'];
        $cidade = $_POST['cidade'];
        $bairro = $_POST['bairro'];
        $rua = $_POST['rua'];
        $numero = $_POST['numero'];
        $complemento = $_POST['complemento'];
        $referencia = $_POST['referencia'];

        $_SESSION['cep'] = $cep;
        $_SESSION['rua'] = $rua;
        $_SESSION['numero'] = $numero;
        $_SESSION['bairro'] = $bairro;
        $_SESSION['cidade'] = $cidade;
        $_SESSION['estado'] = $estado;
        $_SESSION['complemento'] = $complemento;
        $_SESSION['referencia'] = $referencia;

        // Sua chave de API do Google Maps
        $apiKey = 'AIzaSyD-IguGuEzPE2sUOy-MB3QK_lp7udCM7Eo';
    
        // Endereços de origem e destino
        $origin = 'Rua João Ribeiro, 40, Vila Centenário';
        $destination = $rua . ", " . $numero . ", " . $bairro;
    
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

        if($bairro != 'Vila Centenário') {
            if($distanceKm > 5) {
                $error = 'Endereço não atendido!';

                $stmt = $mysqli->prepare("UPDATE usuarios SET taxa = ? WHERE idUsuarios = ?");
                $stmt->bind_param("si", $error, $idUser);
                $stmt->execute();
                $stmt->close();
            } else {
                $fixedRate = 3.00; // Taxa fixa de R$3,00
                $totalCost = $fixedRate + $cost; // Total em valor numérico
                $_SESSION['taxa'] = "R$ " . number_format($totalCost, 2, ",", ".");

                $stmt = $mysqli->prepare("UPDATE usuarios SET estado = ?, cidade = ?, bairro = ?, rua = ?, numero = ?, complemento = ?, referencia = ?, taxa = ? WHERE idUsuarios = ?");
                $stmt->bind_param("ssssssssi", $estado, $cidade, $bairro, $rua, $numero, $complemento, $referencia, $_SESSION['taxa'], $idUsuario);
                $stmt->execute();
                $stmt->close();

                sleep(2);

                header("Location: ./pagamento_endereco.php");
                exit;
            }
        } else {
            $_SESSION['taxa'] = 'R$ 3,00';

            $stmt = $mysqli->prepare("UPDATE usuarios SET estado = ?, cidade = ?, bairro = ?, rua = ?, numero = ?, complemento = ?, referencia = ?, taxa = ? WHERE idUsuarios = ?");
            $stmt->bind_param("ssssssdsi", $estado, $cidade, $bairro, $rua, $numero, $complemento, $referencia, $_SESSION['taxa'], $idUsuario);
            $stmt->execute();
            $stmt->close();

            sleep(2);

            header("Location: ./pagamento_endereco.php");
            exit;
        }
    }
}

?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./estilo_editar_end/style.css">
    <link rel="stylesheet" href="./estilo_editar_end/media_querie.css">
    <title>Editar Endereço</title>
</head>
<body>
    <div class="voltar">
        <a href="./pagamento_endereco.php">Voltar</a>
    </div>
    
    <main>
        <section>
        <h1>Atualize o seu endereço</h1>
            <form action="" method="post">
                <div class="inpu">
                    <input type="text" name="cep" id="cep" placeholder="CEP" required value="<?php echo isset($_POST['cep']) ? $_POST['cep'] : ''; ?>">
                </div>
                <div class="inpu">
                    <input type="text" name="estado" id="estado" placeholder="Estado" required>
                </div>
                <div class="inpu">
                    <input type="text" name="cidade" id="cidade" placeholder="Cidade" required>
                </div>
                <div class="inpu">
                    <input type="text" name="bairro" id="bairro" placeholder="Bairro" required>
                </div>
                <div class="inpu">
                    <input type="text" name="rua" id="rua" placeholder="Logradouro" required value="<?php echo isset($_POST['rua']) ? $_POST['rua'] : ''; ?>">
                </div>
                <div class="inpu">
                    <input type="text" name="numero" id="numero" placeholder="Número" required value="<?php echo isset($_POST['numero']) ? $_POST['numero'] : ''; ?>">
                </div>
                <div class="inpu">
                    <input type="text" name="complemento" id="complemento" placeholder="Complemento(Opcional)" value="<?php echo isset($_POST['complemento']) ? $_POST['complemento'] : ''; ?>">
                </div>
                <div class="inpu">
                    <input type="text" name="referencia" id="referencia" placeholder="Ponto de Referência" required value="<?php echo isset($_POST['referencia']) ? $_POST['referencia'] : ''; ?>">
                </div>
                <p style="color: red; font-weight: bold;"><?php echo $error?></p>
                <div class="btn">
                    <button type="submit">Editar</button>
                    <a href="./pagamento_endereco.php"><button type="reset">Cancelar</button></a>
                </div>
            </form>
        </section>
        <div class="img">
            <img src="./imagens/imagens_cadastro_usuario/culinaria-mineira-cpt.jpg" alt="imagem de comida">
        </div>
    </main>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.js"></script>
    <script src="./js_editar_end/cep.js"></script>
    <script src="./js_editar_end/formatacao.js"></script>
</body>
</html>