<?php

// include utility functions
// and constants
include('/var/www/file_upload/utility/utility.php');
include('/var/www/file_upload/utility/constants.php');

// Path to move uploaded files
$target_path = "uploads/";
 
// array for final json respone
$response = array();
  
if (isset($_FILES['image']['name'])) {
    $target_path = $target_path . basename($_FILES['image']['name']);
 
    // reading other post parameters
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $website = isset($_POST['website']) ? $_POST['website'] : '';
 
    $response['file_name'] = basename($_FILES['image']['name']);
    $response['email'] = $email;
    $response['website'] = $website;
 
try {
    // Throws exception incase file is not being moved
    if (!move_uploaded_file($_FILES['image']['tmp_name'], $target_path)) {
        // make error flag true
        $response['error'] = true;
        $response['message'] = 'Could not move the file!';
    } else {
        // File successfully uploaded
        $response['message'] = 'File uploaded successfully!';
        $response['error'] = false;
        
        // Get the contents of the pdf as a string
        $output = "";
        $pdfToTextCmd = "pdftotext -layout '" . $target_path . "' testText";
        exec($pdfToTextCmd, $output);
        $contents = file_get_contents($TEST_FILE);
        
        // Split on delimiter chars
        $delimiterPattern = '/[:\n\t]/'; 
        $parts = preg_split($delimiterPattern, $contents);
        $partsTable = explode("\n", substr($contents, strpos($contents, $TABLE_BEGIN_INDEX_STRING)));
        // Prepare for parsing
        $parts_array = array();
        $index = -1;
        $offset = 8;
        for($i = 0; $i < count($partsTable); $i++) {
             if (strpos($partsTable[$i], $TABLE_BEGIN_INDEX_STRING) !== false ) {
                 $index = $i;
                 $break;
             }
        } 
        var_dump($partsTable);
        for($j = $index + 1; $j <= $offset; $j++){
             array_push($parts_array, $partsTable[$j]);
        }
        var_dump($parts_array);
        // $parts_array now contains measurement table which should now be parsed
        $result = []; 
        for($k = 0; $k < count($parts_array); $k++) {
             // replace everything with 1+ space with one space and explode into array
             $tmp = explode(' ', preg_replace('/ +/', ' ', $parts_array[$k]));
             // put found measurements in an object
             $measurement = $tmp[0];
             $count = count($tmp);
             // get part of table that has measurements
             $slice = array_slice($tmp, $count - 3);             
             if ($measurement === 'VC') {
                 $result['VcIN'] = $slice;
             } else if ($measurement === 'FEV') {
                if ($count > 7) {
                   $result['Fev1VcMax'] = $slice;
                } else {
                   $result['Fev1'] = $slice;
                }
             } else if ($measurement === 'FVC') {
                $result['FVC'] = $slice;
             } else if ($measurement === 'MEF') {
                $key = $tmp[0] . $tmp[1];                
                $result[$key] = $slice;
             } else if ($measurement === 'PEF') {
                $result['PEF'] = $slice;
             }
        }
        var_dump($result);
        // normalize values
        $result['FVC'] = (floatval($result['FVC'][2]) / 100.0) > 100 ? 100 : floatval($result['FVC'][2]) / 100.0;
        $result['Fev1'] = (floatval($result['Fev1'][2]) / 100.0) > 100 ? 100 : floatval($result['Fev1'][2]) / 100.0;
        $result['Fev1FVC'] = (floatval($result['Fev1']) / floatval($result['FVC'])) > 1 ? 1 : (floatval($result['Fev1']) / floatval($result['FVC']));
        $result['PEF'] = (floatval($result['PEF'][2]) / 100.0) > 100 ? 100 : (floatval($result['PEF'][2]) / 100.0);
        var_dump($result);
        // execute matlab commands
        $inputVector = "[" . $result['FVC'] . "," . $result['Fev1'] . "," . $result['Fev1FVC'] . "," . $result['PEF'] . "]";
        var_dump($inputVector);
        $matlabCommand = 'echo apach3T3mp | /usr/bin/sudo -S /home/eldar/Desktop/MatlabInstall/bin/glnxa64/MATLAB -r -nodisplay "SPIR_Fuzzy=readfis(' . "'SPIR-Fuzzy');value=evalfis(" . $inputVector . ",SPIR_Fuzzy);disp(value);disp(SPIR_Fuzzy);" . '"';
        $cmdOutput = "";
        exec($matlabCommand, $cmdOutput);
        var_dump($cmdOutput);
        if (isset($cmdOutput) && !empty($cmdOutput)) {
            $value = floatval(trim($cmdOutput[10]));
            var_dump($value);
            var_dump($EPS);
            $diagnose_value = 0;

            if (abs($value - 0.1) < $EPS) $diagnose_value = 1;
            if (abs($value - 0.35) < $EPS) $diagnose_value = 2;
            if (abs($value - 0.6) < $EPS) $diagnose_value = 3;
            if (abs($value - 0.85) < $EPS) $diagnose_value = 4;
            var_dump($diagnose_value);
            $state = $DIAGNOSIS_VALUES[$diagnose_value];
            // send email report
            send_email('spirometry-results@spirometry.ba', 'eldar32@gmail.com', 'Your results', 'The state of your lungs is: ' . $state );
        }
        
    }
          
} catch (Exception $e) {
        // Exception occurred. Make error flag true
        $response['error'] = true;
        $response['message'] = $e->getMessage();
    }
} else {
    // File parameter is missing
    $response['error'] = true;
    $response['message'] = 'Not received any file!F';
}
 
echo json_encode($response);
   
?>
