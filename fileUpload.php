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
        $delimiterPattern = '/[:\R\t]/'; 
        $parts = preg_split($delimiterPattern, $contents);
        $partsTable = explode("\n", substr($contents, strpos($contents, $TABLE_BEGIN_INDEX_STRING)));
        
        // Prepare for parsing
        $parts_array = array();

        foreach ($partsTable as $part) {
             array_push($parts_array, $part);
             if (strpos($part, "Date") !== false) {
                 break;
             }
        } 

        file_put_contents($TEST_FILE_1, $parts_array);
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
