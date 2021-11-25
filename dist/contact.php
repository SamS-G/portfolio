<?php

//Import the PHPMailer class into the global namespace
    use PHPMailer\PHPMailer\PHPMailer;

    require '../vendor/autoload.php';

    if (array_key_exists('subject', $_POST)) {

        $err = false;
        $msg = '';
        $email = '';
        $to = 'samuel.guichardon@webconceptions.fr';
        if (array_key_exists('subject', $_POST)) {
            $subject = substr(strip_tags($_POST['subject']), 0, 255);
        } else {
            $subject = 'Pas de sujet renseigné';
        }
        //Apply some basic validation and filtering to the query
        if (array_key_exists('query', $_POST)) {
            //Limit length and strip HTML tags
            $query = substr(strip_tags($_POST['query']), 0, 16384);
        } else {
            $query = '';
            $msg = 'Pas de message contenu !';
            $err = true;
        if (array_key_exists('name', $_POST)) {
            //Limit length and strip HTML tags
            $name = substr(strip_tags($_POST['name']), 0, 255);
        } else {
            $name = '';
        }
        if (array_key_exists('type', $_POST) && in_array($_POST['type'], ['commercial', 'après-vente', 'autres'], true)) {
            $questionType = $_POST['type'];
        } else {
            $questionType = 'non defini';
        }

        //Make sure the address they provided is valid before trying to use it
        if (array_key_exists('email', $_POST) && PHPMailer::validateAddress($_POST['email'])) {
            $email = $_POST['email'];
        } else {
            $msg .= 'Erreur: adresse mail fournie invalide';
            $err = true;
        }
        if (!$err) {
            $mail = new PHPMailer(true);
            $mail->isSMTP();

            $mail->SMTPDebug = 0;
            $mail->Host = 'mail.webconceptions.fr';
            $mail->SMTPAuth = true;
            $mail->Username = 'samuel.guichardon@webconceptions.fr';
            $mail->Password = '';
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port = 465;


            $mail->setFrom('samuel.guichardon@webconceptions.fr', (empty($name) ? 'Formulaire de contact' : $name));
            $mail->addAddress($to);
            $mail->addReplyTo($email, $name);
            $mail->Subject = 'Formulaire de demande de contact concernant :  ' . $questionType;
            $mail->Body = "Sujet :\n\n" . $subject . "\n\n" . 'Question : ' . "\n\n" . $query;
            if (!$mail->send()) {
                $msg .= 'Mailer Error: ' . $mail->ErrorInfo;
                header('Location: https://webconceptions.fr?error=error');
            } else {
                header('Location: https://webconceptions.fr?success=success');
            }
        }

    } else {

        echo 'Pas de données dans le formulaire';

    }
