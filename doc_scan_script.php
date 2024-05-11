<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $docName = $_POST['doc'];
    $format  = $_POST['format'];
    $mode = $_POST['colorMode'];
    $dpi = $_POST['dpi'];

    //echo "$docName\n";
    
    $command = "sudo scanimage > scanned_docs/$docName.$format --format $format --mode=$mode --resolution=$dpi -p";
    //echo "$command\n";
    
    // Execute the command
    exec($command, $output, $retval);
    
    //echo $output;

    // Redirect to scanned file	
    header("Location: /scanned_docs/$docName.$format");
}
?>
