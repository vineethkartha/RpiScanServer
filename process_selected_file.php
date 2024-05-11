<?php
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
    // Redirect to scanned file	
    header("Location: /scanned_docs/$docName.$format");
} else {
  echo "Error executing command.";
}
?>
