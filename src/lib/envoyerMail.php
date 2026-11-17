<?php

// ===== Outil commun pour envoyer un mail (avec PHPMailer) =====

// 1) On charge les identifiants Mailtrap
require_once __DIR__ . '/../bdd/configMail.php';

// 2) On charge les 3 fichiers de PHPMailer
require_once __DIR__ . '/PHPMailer/Exception.php';
require_once __DIR__ . '/PHPMailer/PHPMailer.php';
require_once __DIR__ . '/PHPMailer/SMTP.php';

// 3) On dit à PHP qu'on utilise la classe PHPMailer
use PHPMailer\PHPMailer\PHPMailer;


// La fonction : on lui donne le destinataire, le sujet et le message
function envoyerMail($destinataire, $sujet, $message)
{
    $mail = new PHPMailer(true);

    try {

        // --- Connexion au serveur Mailtrap ---
        $mail->isSMTP();
        $mail->Host = MAIL_HOST;
        $mail->Port = MAIL_PORT;
        $mail->SMTPAuth = true;
        $mail->Username = MAIL_USER;
        $mail->Password = MAIL_PASS;

        // --- Pour que les accents s'affichent bien (é, è, à...) ---
        $mail->CharSet = 'UTF-8';

        // --- Qui envoie, et à qui ---
        $mail->setFrom('noreply@hsp.fr', 'HSP');
        $mail->addAddress($destinataire);

        // --- Le contenu du mail ---
        $mail->Subject = $sujet;
        $mail->Body = $message;

        $mail->send();
        return true;     //mail est parti
    } catch (Exception $e) {
        return false;    // il y a eu un problème
    }

}