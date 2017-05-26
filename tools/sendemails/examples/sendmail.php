<?php

require '../PHPMailerAutoload.php';
set_time_limit(0);
for ($i = 0; $i <= 50; $i++) {
//Create a new PHPMailer instance
    $mail = new PHPMailer();
// Set PHPMailer to use the sendmail transport
    $mail->isSendmail();
//Set who the message is to be sent from
    $mail->setFrom('270230194@qq.com', 'First Last');
//Set an alternative reply-to address
    $mail->addReplyTo('270230194@qq.com', 'First Last');
//Set who the message is to be sent to
    $mail->addAddress('649037629@qq.com', 'John Doe');
//Set the subject line
    $mail->Subject = 'PHPMailer sendmail test';
//Read an HTML message body from an external file, convert referenced images to embedded,
//convert HTML into a basic plain-text alternative body
    $mail->msgHTML(file_get_contents('contents.html'), dirname(__FILE__));
//Replace the plain text body with one created manually
    $mail->AltBody = 'This is a plain-text message body';
//Attach an image file
    $mail->addAttachment('images/phpmailer_mini.gif');

//send the message, check for errors
    if (!$mail->send()) {
        echo "Mailer Error: " . $mail->ErrorInfo;
    } else {
        echo "Message sent!";
    }
    ob_get_contents();
    ob_end_clean();
    sleep(5);
    echo '.';
    flush();
}
?>