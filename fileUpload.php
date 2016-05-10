<?php

// include utility functions
// and constants
include('/var/www/file_upload/utility/parse_pdf_lib.php');
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
        for($j = $index + 1; $j <= $offset; $j++){
             array_push($parts_array, $partsTable[$j]);
        }

        // $parts_array now contains measurement table which should now be parsed
        $result = []; 
        for($k = 0; $k < count($parts_array); $k++) {
             // replace everything with 1+ space with one space and explode into array
             $tmp = explode(' ', preg_replace('/ +/', ' ', $parts_array[$k]));            
             $measurement = $tmp[0];
             $count = count($tmp);
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
        
        $result['FVC'] = (floatval($result['FVC']) / 10.0) > 100 ? 100 : floatval($result['FVC']) / 10.0;
        $result['Fev1'] = (floatval($result['Fev1']) / 10.0) > 100 ? 100 : floatval($result['Fev1']) / 10.0;
        $result['Fev1FVC'] = (floatval($result['Fev1']) / floatval($result['FVC'])) > 1 ? 1 : (floatval($result['Fev1']) / floatval($result['FVC']));
        $result['PEF'] = (floatval($result['PEF']) / 10.0) > 100 ? 100 : (floatval($result['PEF']) / 10.0);
        
        $inputVector = "[" . $result['FVC'] . "," . $result['Fev1'] . "," . $result['Fev1FVC'] . "," . $result['PEF'] . "]";
        $matlabCommand = "matlab -r -nodisplay 'SPIR_Fuzzy=readfis(" . '"SPIR-Fuzzy.fis"); value=evalfis(' . $inputVector . ', SPIR_Fuzzy); disp(value);' . "'";
        $cmdOutput = "";
        exec($matlabCommand, $cmdOutput);
        var_dump($cmdOutput);
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
