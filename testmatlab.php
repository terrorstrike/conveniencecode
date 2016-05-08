<?php
$scriptCommand = '"x = 1; printString(x); quit;"';
$command = "/home/eldar/Desktop/MatlabInstall/bin/glnxa64/MATLAB -r -nodisplay " . $scriptCommand; 
$output; $retval; $errors="";
exec ($command ,  $output, $retval);
echo $output[0] ."\n";
unset($output);
?>
