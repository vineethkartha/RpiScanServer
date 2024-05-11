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

// Retrieve selected file names
$selectedFiles = $_POST['files'];
$format = $_POST['format'];
$finalDocName = $_POST['finalDoc'];

$folderName  = "scanned_docs/";
$finalDocNameWithExt =  $folderName . $finalDocName . "." .$format;

// Construct shell command using selected file names
if ($format === "pdf") {
     $command = "sudo convert ";
   } else {
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
   $to = $_POST['email'];
   $subject = "Scanned Docs from PrinterPi: $finalDocName ";
   $attachmentFilePath = $finalDocNameWithExt;
   $attachmentFileName = $finalDocName . $format;

   if (sendEmailWithAttachment($to, $subject, $attachmentFilePath, $attachmentFileName)) {
       echo "Email sent successfully.";
   } else {
      echo "Failed to send email.";
   }
    // Redirect to scanned file	
    header("Location: /$finaldocNameWithExt");
} else {
  echo "Error executing command.";
}
?>
