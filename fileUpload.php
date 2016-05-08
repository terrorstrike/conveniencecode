<?php

// include utility functions
// and constants
include('/var/www/file_upload/utility/parse_pdf_lib.php');
include('/var/www/file_upload/utility/constants.php');
include('/var/www/file_upload/model/SpirometryResult.php');

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
        for($k = 0; $k < count($parts_array); $k++) {
             // Declare result instance
             $result = new SpirometryResult();
             // replace everything with 1+ space with one space and explode into array
             $tmp = explode(' ', preg_replace('/ +/', ' ', $parts_array[$k]));
             var_dump($tmp);             
$measurement = $tmp[0];
             $count = count($tmp);
             $slice = array_slice($tmp, $count - 3);
             var_dump($slice);             
if ($measurement == 'VC') {
                 $result->setVcIN($slice);
             } else if ($measurement == 'FEV') {
                if ($count > 7) {
                   $result->setFev1VCMax($slice);
                } else {
                   $result->setFev1($slice);
                }
             } else if ($measurement == 'FVC') {
                $result->setFvc($slice);
             } else if ($measurement == 'MEF50') {
                $result->setMEF50($slice);
             } else if ($measurement == 'MEF75') {
                $result->setMEF75($slice);
             } else if ($measurement == 'MEF25') {
                $result->setMEF25($slice);
             } else if ($measurement == 'PEF') {
                $result->setPef($slice);
             }
        }
        var_dump($result->getMEF25());
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
