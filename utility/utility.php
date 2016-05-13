<?php

include('/var/www/file_upload/utility/constants.php');
require 'PHPMailerAutoload.php';

function send_email($from, $to, $subject, $text) {
	/*$output = "";
    exec("curl -s --user '" . "api:key-2cf78ac777f8bbb038017d7dd35104b3"  . "' \ " . " https://api.mailgun.net/v3/sandbox2904d7336bb443559e446612dbc8f616.mailgun.org/messages "  . " \ -F from='" . $from . "' \ -F to='" . $to . "' \ -F subject='" . $subject . "' \ -F text='" . $text . "'", $output);
    var_dump($output);*/

  /*  $mail = new PHPMailer;

	$mail->isSMTP();                                      // Set mailer to use SMTP
	$mail->Host = 'smtp.mailgun.org';                     // Specify main and backup SMTP servers
	$mail->SMTPAuth = true;                               // Enable SMTP authentication
	$mail->Username = 'postmaster@sandbox2904d7336bb443559e446612dbc8f616.mailgun.org';   // SMTP username
	$mail->Password = '6d71484f2c739e7eb64f314efab6ce09';                           // SMTP password
	$mail->SMTPSecure = 'tls';                            // Enable encryption, only 'tls' is accepted

	$mail->From = $from;
	$mail->FromName = 'Mailer';
	$mail->addAddress($to);                 // Add a recipient

	$mail->WordWrap = 50;                                 // Set word wrap to 50 characters

	$mail->Subject = $subject;
	$mail->Body    = $text;

	if(!$mail->send()) {
	    echo 'Message could not be sent.';
	    echo 'Mailer Error: ' . $mail->ErrorInfo;
	} else {
	    echo 'Message has been sent';
	}*/
	$headers = 'From: spirometry@spirometry.ba' . "\r\n" .
    'Reply-To: eldar32@gmail.com' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();

	mail($to, $subject, $text, $headers);
}

?>
