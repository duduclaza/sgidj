<?php
require_once __DIR__ . '/app/Services/PHPMailer/Exception.php';
require_once __DIR__ . '/app/Services/PHPMailer/PHPMailer.php';
require_once __DIR__ . '/app/Services/PHPMailer/SMTP.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

try {
    $mail->SMTPDebug = 2; // Habilita debug verboso
    $mail->isSMTP();
    $mail->Host       = 'smtp.hostinger.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'suportetiuaisgi@tiuai.com.br';
    $mail->Password   = 'Pandora@1989';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    $mail->Port       = 465;
    $mail->CharSet    = 'UTF-8';

    $mail->setFrom('suportetiuaisgi@tiuai.com.br', 'SGI ATLAS');
    $mail->addAddress('du.claza@gmail.com');

    $mail->isHTML(true);
    $mail->Subject = 'Teste SMTP - SGI ATLAS';
    $mail->Body    = '<h2>Teste de Envio</h2><p>Se você recebeu este e-mail, o SMTP está funcionando!</p><p>Seu código seria: <strong>123456</strong></p>';

    $mail->send();
    echo "E-mail enviado com sucesso!\n";
} catch (Exception $e) {
    echo "Falha ao enviar: {$mail->ErrorInfo}\n";
}
