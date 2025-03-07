<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/PHPMailer/src/Exception.php';
require __DIR__ . '/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/PHPMailer/src/SMTP.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Coletar dados do formulário
    $nome = $_POST['nome'];
    $endereco = $_POST['endereco'];
    $telefone = $_POST['telefone'];
    $email = $_POST['email'];
    $servico = $_POST['servico']; // Nome completo do serviço
    $data = $_POST['data'];

    // Formatar a data e o horário
    $dataFormatada = date('d/m/Y H:i', strtotime($data)); // Converte para o formato desejado
    $horario = date('H:i', strtotime($data)); // Extrai o horário

    $mail = new PHPMailer(true);

    try {
        // Configuração SMTP (Gmail ou Mailtrap)
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Ou smtp.mailtrap.io
        $mail->SMTPAuth = true;
        $mail->Username = 'casabarbeiro67@gmail.com'; // Seu e-mail
        $mail->Password = 'cvglggbkabicmolq'; // Senha de 16 dígitos
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587; // Ou 2525 para Mailtrap

        // Remetente/Destinatário (para você)
        $mail->setFrom('casabarbeiro67@gmail.com', 'Moraes Assistencia Tecnica'); // Remetente fixo
        $mail->addAddress('casabarbeiro67@gmail.com'); // E-mail que receberá os agendamentos

        // Conteúdo do e-mail para você
        $mail->isHTML(true);
        $mail->Subject = "Agendamento: $servico"; // Nome completo do serviço
        $mail->Body = "
            <div style='font-family: Arial, sans-serif; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 10px;'>
                <h2 style='color: #fdbc19; text-align: center;'>Novo Agendamento</h2>
                <p><strong>Nome:</strong> $nome</p>
                <p><strong>Endereço:</strong> $endereco</p>
                <p><strong>Telefone:</strong> $telefone</p>
                <p><strong>E-mail:</strong> $email</p>
                <p><strong>Serviço:</strong> $servico</p> <!-- Nome completo do serviço -->
                <p><strong>Data:</strong> $dataFormatada</p>
            </div>
        ";

        $mail->send(); // Envia o e-mail para você

        // Enviar uma cópia para o cliente
        $mail->clearAddresses(); // Limpa os destinatários anteriores
        $mail->addAddress($email); // Adiciona o e-mail do cliente como destinatário
        $mail->setFrom('casabarbeiro67@gmail.com', 'Moraes Assistencia Tecnica'); // Remetente fixo
        $mail->Subject = "Confirmacao de Agendamento - Moraes Assistencia Tecnica";
        $mail->Body = "
            <div style='font-family: Arial, sans-serif; color: #333; max-width: 600px; margin: 0 auto; padding: 20px; border: 1px solid #ddd; border-radius: 10px;'>
                <h2 style='color: #fdbc19; text-align: center;'>Olá, $nome!</h2>
                <p>Seu agendamento foi recebido com sucesso. Abaixo estão os detalhes:</p>
                <p><strong>Serviço:</strong> $servico</p> <!-- Nome completo do serviço -->
                <p><strong>Data:</strong> $dataFormatada</p>
                <p><strong>Endereço:</strong> $endereco</p>
                <p><strong>Telefone:</strong> $telefone</p>
                <p style='text-align: center; margin-top: 20px;'>
                    <strong>Obrigado por escolher a Moraes Assistência Técnica!</strong>
                </p>
            </div>
        ";

        $mail->send(); // Envia o e-mail para o cliente

        // Mensagem de sucesso
        echo "
        <!DOCTYPE html>
        <html lang='pt-BR'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Agendamento Realizado</title>
            <style>
                body {
                    background-color: #1f1f1f;
                    color: #ffffff;
                    font-family: Arial, sans-serif;
                    margin: 0;
                    padding: 0;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    height: 100vh;
                    text-align: center;
                }
                .container {
                    background-color: #2c2c2c;
                    padding: 30px;
                    border-radius: 10px;
                    box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
                    max-width: 600px;
                    width: 100%;
                }
                .logo {
                    max-width: 200px;
                    margin-bottom: 20px;
                }
                h1 {
                    color: #fdbc19;
                    margin-bottom: 20px;
                }
                p {
                    font-size: 18px;
                    color: #ffffff;
                }
                .success-message {
                    color: #27ae60;
                    font-size: 20px;
                    font-weight: bold;
                    margin-top: 20px;
                }
            </style>
            <link rel='icon' type='image/x-icon' href='images/moraesassistenciatecnica.png'>
        </head>
        <body>
            <div class='container'>
                <img src='images/check_symbol.png' alt='Enviado' class='logo'>
                <h1>Agendamento Realizado!</h1>
                <p>Obrigado, $nome! Seu agendamento foi recebido com sucesso.</p>
                <p>Entraremos em contato para confirmar os detalhes.</p>
                <div class='success-message'>E-mail enviado com sucesso!</div>
            </div>
        </body>
        </html>
        ";
    } catch (Exception $e) {
        // Mensagem de erro
        echo "
        <!DOCTYPE html>
        <html lang='pt-BR'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Erro no Agendamento</title>
            <style>
                body {
                    background-color: #1f1f1f;
                    color: #ffffff;
                    font-family: Arial, sans-serif;
                    margin: 0;
                    padding: 0;
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    height: 100vh;
                    text-align: center;
                }
                .container {
                    background-color: #2c2c2c;
                    padding: 30px;
                    border-radius: 10px;
                    box-shadow: 0 0 10px rgba(0, 0, 0, 0.5);
                    max-width: 600px;
                    width: 100%;
                }
                .logo {
                    max-width: 200px;
                    margin-bottom: 20px;
                }
                h1 {
                    color: #c0392b;
                    margin-bottom: 20px;
                }
                p {
                    font-size: 18px;
                    color: #ffffff;
                }
                .error-message {
                    color: #c0392b;
                    font-size: 20px;
                    font-weight: bold;
                    margin-top: 20px;
                }
            </style>
            <link rel='icon' type='image/x-icon' href='images/moraesassistenciatecnica.png'>
        </head>
        <body>
            <div class='container'>
                <img src='images/cancel_symbol.png' alt='Erro' class='logo'>
                <h1>Erro no Agendamento</h1>
                <p>Desculpe, ocorreu um erro ao enviar o agendamento.</p>
                <div class='error-message'>Erro: {$mail->ErrorInfo}</div>
            </div>
        </body>
        </html>
        ";
    }
}
?>