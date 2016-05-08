<?php
$cdOutput;
$cdRetVal;
$cdCommand = "cd /home/eldar/Desktop/MatlabInstall/bin";
exec($cdCommand, $cdOutput, $cdRetVal);
$scriptCommand = '"printString("x"); quit;"';
$command = "./matlab -r -nodisplay " . $scriptCommand; 
$output; $retval; $errors="";
exec ($command ,  $output, $retval);
echo $output[0] ."\n";
unset($output);
?>
