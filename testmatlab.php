<?php
$cdOutput;
$cdRetVal;
$cdCommand = "cd /home/eldar/Desktop/MatlabInstall/bin";
exec($cdCommand, $cdOutput, $cdRetVal);
$command = "matlab -wait -nodisplay -r" . "'printString(" . '"x"' . "); quit;"; 
$output; $retval; $errors="";
exec ($command ,  $output, $retval);
echo $output[0] ."\n";
unset($output);
?>
