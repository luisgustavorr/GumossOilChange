<?php


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

class sendNFEMail
{
    public static function enviar($email_cliente,$nome_cliente,$dh_emi)
    {

        $mail = new PHPMailer(true);
        $empresa = "AutoLub";
        $nome_nota = "Nota Fiscal $empresa";
        $data = explode("-", $dh_emi);
        $data_formatada = $data[0] . "/" . $data[1] . "/" . $data[2] . "/" . $data[3];
        $horario = $data[3] . ":" . $data[4] . ":" . $data[5];
        try {
            //Server settings
            //$mail->SMTPDebug = SMTP::DEBUG_SERVER;                      //Enable verbose debug output
            $mail->isSMTP();                                            //Send using SMTP
            $mail->Host       = 'smtp.gmail.com';                     //Set the SMTP server to send through
            $mail->SMTPAuth   = true;                                   //Enable SMTP authentication
            $mail->Username   = 'ahristocrat4@gmail.com';                     //SMTP username
            $mail->Password   = $_ENV["EMAIL_PASS"];                               //SMTP password
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;            //Enable implicit TLS encryption
            $mail->Port       = 465;                                    //TCP port to connect to; use 587 if you have set `SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS`
            //Recipients
            $mail->setFrom('ahristocrat4@gmail.com', 'AutoLub');
            $mail->addAddress($email_cliente, $nome_cliente);     //Add a recipient
            //Attachments
            $mail->addAttachment("./temp/nota.pdf", $nome_nota . '.pdf');    //Optional name
            //Content
            $mail->isHTML(true);                                  //Set email format to HTML
            $mail->Subject = "Nota Fiscal $data_formatada";
            $mail->Body    = "Nota fiscal do dia : $data_formatada as $horario.";
            $mail->AltBody = "Nota fiscal do dia : $data_formatada as $horario.";

            $mail->send();
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}
