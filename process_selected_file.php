<?php

include 'mailscript.php';

// Check if form is submitted
if ($_SERVER["REQUEST_METHOD"] != "POST") {
echo "Invalid request method.";
return;
}

// Check if files are selected
if (!isset($_POST['files']) || !is_array($_POST['files']) || empty($_POST['files'])) {
echo "No files selected.";
return;
}

// Retrieve fields from the form
$selectedFiles = $_POST['files'];
$format = $_POST['formatToSend'];
$to = $_POST['email'];

// Initialize some variables
$folderName  = "scanned_docs/";
$subject = "Scanned Docs from PrinterPi ";
$attachments = [];

// Processing if no compression is requested
if($format === "nocompress") {
  foreach ($selectedFiles as $file) {
  	  // Sanitize file name to prevent shell injection
	  $sanitizedFileName = trim(escapeshellarg($file), "'\"");
	  $attachments[] = ['path' => $folderName.$sanitizedFileName, 'name' => $sanitizedFileName];
  }


  if (sendEmailWithAttachments($to, $subject, $attachments)) {
     echo "Email sent successfully.";
  } else {
     echo "Failed to send email.";
  }
  return;
}


$finalDocName = $_POST['finalDoc'];
$finalDocNameWithExt =  $folderName . $finalDocName . "." .$format;

$subject .= $finalDocName;
$attachments[] = ['path' => $finalDocNameWithExt, 'name' => $finalDocName.".".$format];


// Construct shell command using selected file names
if ($format === "pdf") {
     $command = "sudo convert ";
   } else if($format === "zip"){
     $command = "sudo zip $finalDocNameWithExt ";
}

// Add each selected file name to the command
foreach ($selectedFiles as $file) {
	// Sanitize file name to prevent shell injection
	$sanitizedFileName = escapeshellarg($file);
	// Append the sanitized file name to the command
	$command .= $folderName . $sanitizedFileName . " ";
}

if ($format === "pdf") {
   $command .= $finalDocNameWithExt;
}

//echo $command;

// Execute the shell command
exec($command, $output, $status);

// Check if command execution was successful
if ($status === 0) {
   if (sendEmailWithAttachments($to, $subject, $attachments)) {
       echo "Email sent successfully.";
   } else {
      echo "Failed to send email.";
   }
    // Redirect to scanned file	
   header("Location: /$finalDocNameWithExt");
} else {
  echo "Error executing command.";
}
?>
