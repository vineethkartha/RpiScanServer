<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Prineter Pi Scanner Form</title>
    <style>
      /* Style for the loading overlay */
      #loading-overlay {
          display: none;
          position: fixed;
          top: 0;
          left: 0;
          width: 100%;
          height: 100%;
          background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent black background */
          z-index: 9999; /* Ensure the overlay appears above other content */
          color: white;
          text-align: center;
          padding-top: 20%;
      }
    </style>
    <script>
      function showLoading() {
          document.getElementById("loading-overlay").style.display = "block";
      }

      function hideLoading() {
          document.getElementById("loading-overlay").style.display = "none";
      }
    </script>
  </head>

  <body>

    <h1>Scan Documents</h1>

    <?php include 'check_scanner_status.php'; ?>
    
    <?php
     $imageWidth=25;
     $imageHeight=25;
     // Check scanner status
     echo "The scanner is : ";
     if (!isScannerPoweredOn()) {
     echo "<img src='assets/offline.png' alt='Scanner is not powered on' width='$imageWidth' height='$imageHeight'>";
		return;
		} 
		echo "<img src='assets/online.png' alt='Scanner is powered on' width='$imageWidth' height='$imageHeight'>";
		?>
    
    <form action="doc_scan_script.php" method="post" onsubmit="showLoading()">

      <label for="doc">Enter document name:</label>
      <input type="text" id="doc" name="doc" required><br><br>
      
      <label for="format">Select format:</label>
      <select name="format" id="format">
	<option value="jpeg">jpeg</option>
	<option value="png">png</option>
      </select>
      <br><br>
      
      <input type="submit" value="scan">
    </form>
    
    <h1>Select files to send</h1>

    <form action="process_selected_file.php" method="post" onsubmit="showLoading()">
      <label for="files">Select a file:</label>
      <select name="files[]" id="files" multiple>
        <?php
         // Directory path containing the files
         $directory = 'scanned_docs/';
	 
         // Retrieve a list of files from the directory
         $files = scandir($directory);
	 
         // Iterate through each file and create an option for the select element
         foreach ($files as $file) {
         // Exclude "." and ".." directories
         if ($file != '.' && $file != '..') {
         echo "<option value='$file'>$file</option>";
}
}
?>
      </select>
      <br><br>
      
      <label for="format">Select format to send:</label>
      <select name="format" id="format">
	<option value="zip">compress selected files to zip</option>
	<option value="pdf">create pdf from selected files</option>
      </select>
      <br><br>

      <label for="finalDoc">Enter document name:</label>
      <input type="text" id="finalDoc" name="finalDoc" required><br><br>

      <label for="email">Email:</label>
      <input type="email" id="email" name="email" required><br><br>
      
      <input type="submit" value="Submit">
    </form>

    <div id="loading-overlay">
      <img src="assets/wip.gif" alt="Loading..." width=100 height=70>
      <p>Please wait while the process executes...</p>
    </div>

  </body>
</html>
