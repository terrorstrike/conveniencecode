<?php
include('PDFParser.php');
$contents = PDFParser::parseFile('/var/www/file_upload/uploads/LV_1.pdf');
file_put_contents('/var/www/file_upload/uploads/tmpTxt', $contents);
?>
