<!DOCTYPE html>
<html lang="pt">
<head>
    <title>Consulta SINTEGRA</title>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>

<?php

require __DIR__ . '/vendor/autoload.php';

use Spider\SintegraCrawler;

$captchaSecretKey = 'SEU_SECRET_KEY'; 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $captchaResponse = $_POST['g-recaptcha-response'];
    $verifyCaptchaUrl = "https://www.google.com/recaptcha/api/siteverify?secret=$captchaSecretKey&response=$captchaResponse";
    $captchaVerification = json_decode(file_get_contents($verifyCaptchaUrl));

    if ($captchaVerification->success) {
        $cnpj = $_POST['cnpj'];

        $crawler = new SintegraCrawler();
        $result = $crawler->getInscricoesEstaduais($cnpj);

        echo "<h2>Resultado da pesquisa para o CNPJ: $cnpj</h2>";

        if (isset($result['error'])) {
            echo "<p>Erro: " . $result['error'] . "</p>";
        } else {
            $inscricoes = $result;

            if (empty($inscricoes)) {
                echo "<p>Nenhuma inscrição estadual encontrada para o CNPJ: $cnpj</p>";
            } else {
                echo "<p>Inscrições Estaduais encontradas: " . implode(', ', $inscricoes) . "</p>";
            }
        }
    } else {
        echo "<p>Erro no CAPTCHA. Por favor, tente novamente.</p>";
    }
}
?>

<h2>Pesquisar CNPJ</h2>
<form method="post" action="">
    <label for="cnpj">CNPJ:</label>
    <input type="text" id="cnpj" name="cnpj" required>
    <div class="g-recaptcha" data-sitekey="SEU_SITE_KEY"></div>
    <button type="submit">Pesquisar</button>
</form>

</body>
</html>
