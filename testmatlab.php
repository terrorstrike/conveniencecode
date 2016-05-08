<?php
$scriptCommand = '"printString("x"); quit;"';
$command = "./home/eldar/Desktop/MatlabInstall/matlab -r -nodisplay " . $scriptCommand; 
$output; $retval; $errors="";
exec ($command ,  $output, $retval);
echo $output[0] ."\n";
unset($output);
?>
