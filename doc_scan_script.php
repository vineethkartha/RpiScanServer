<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $docName = $_POST['doc'];
    $format  = $_POST['format'];

    //echo "$email\n";
    //echo "$docName\n";
    
    $command = "sudo scanimage > scanned_docs/$docName.$format --format $format --mode=color --resolution=300 -p";
    //echo "$command\n";
    
    // Execute the command
    exec($command, $output, $retval);
    
    //echo $output;

    // Redirect to scanned file	
    header("Location: /scanned_docs/$docName.$format");

}
?>
