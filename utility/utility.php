<?php

include('/var/www/file_upload/utility/constants.php');
require 'PHPMailerAutoload.php';

function send_email($from, $to, $subject, $text) {
	/*$output = "";
    exec("curl -s --user '" . "api:key-2cf78ac777f8bbb038017d7dd35104b3"  . "' \ " . " https://api.mailgun.net/v3/sandbox2904d7336bb443559e446612dbc8f616.mailgun.org/messages "  . " \ -F from='" . $from . "' \ -F to='" . $to . "' \ -F subject='" . $subject . "' \ -F text='" . $text . "'", $output);
    var_dump($output);*/

    $mail = new PHPMailer(); // create a new object
	$mail->IsSMTP(); // enable SMTP
	$mail->SMTPDebug = 1; // debugging: 1 = errors and messages, 2 = messages only
	$mail->SMTPAuth = true; // authentication enabled
	$mail->SMTPSecure = 'ssl'; // secure transfer enabled REQUIRED for Gmail
	$mail->Host = "smtp.gmail.com";
	$mail->Port = 465; // or 587
	$mail->IsHTML(true);
	$mail->Username = $EMAIL;
	$mail->Password = $MAIL_PW;
	$mail->SetFrom($from);
	$mail->Subject = $subject;
	$mail->Body = $text;
	$mail->AddAddress($to);

	 if(!$mail->Send()) {
	    echo "Mailer Error: " . $mail->ErrorInfo;
	 } else {
	    echo "Message has been sent";
	 }
}

?>
