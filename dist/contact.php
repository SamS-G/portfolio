<?php

    /**
     * PHPMailer simple contact form example.
     * If you want to accept and send uploads in your form, look at the send_file_upload example.
     */

//Import the PHPMailer class into the global namespace
    use PHPMailer\PHPMailer\PHPMailer;

    require '../vendor/autoload.php';

    if (array_key_exists('subject', $_POST)) {

        $err = false;
        $msg = '';
        $email = '';
        $to = 'samuel.guichardon@webconceptions.fr';
        //Apply some basic validation and filtering to the subject
        if (array_key_exists('subject', $_POST)) {
            $subject = substr(strip_tags($_POST['subject']), 0, 255);
        } else {
            $subject = 'Pas de sujet renseigner';
        }
        //Apply some basic validation and filtering to the query
        if (array_key_exists('query', $_POST)) {
            //Limit length and strip HTML tags
            $query = substr(strip_tags($_POST['query']), 0, 16384);
        } else {
            $query = '';
            $msg = 'Pas de message contenu !';
            $err = true;
        }
        //Apply some basic validation and filtering to the name
        if (array_key_exists('name', $_POST)) {
            //Limit length and strip HTML tags
            $name = substr(strip_tags($_POST['name']), 0, 255);
        } else {
            $name = '';
        }
        //Validate to address
        //Never allow arbitrary input for the 'to' address as it will turn your form into a spam gateway!
        //Substitute appropriate addresses from your own domain, or simply use a single, fixed address
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
            $mail->Password = 'WorkPlace66!*)';
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