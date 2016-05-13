<?php

include('/var/www/file_upload/utility/constants.php');

function send_email($from, $to, $subject, $text) {
    exec("curl -s --user '" . "api:key-2cf78ac777f8bbb038017d7dd35104b3"  . "' \ " . " https://api.mailgun.net/v3/sandbox2904d7336bb443559e446612dbc8f616.mailgun.org/messages "  . " \ -F from='" . $from . "' \ -F to='" . $to . "' \ -F subject='" . $subject . "' \ -F text='" . $text . "'");
}

?>
