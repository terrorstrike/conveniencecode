<?php

include('/var/www/file_upload/utility/constants.php');

function send_email($from, $to, $subject, $text) {
    exec("curl -s --user '" . $MAILGUN_API_KEY . "' \ " . $MAILGUN_DOMAIN . " \ -F from='" . $from . "' -F to='" . $to . "' -F subject='" . $subject . "' -F text='" . $text . "'");
}

?>