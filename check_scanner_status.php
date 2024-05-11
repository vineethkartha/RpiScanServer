<?php

// Function to check if scanner is powered on
function isScannerPoweredOn() {
    // Execute shell command to list USB devices
    $output = shell_exec("lsusb");

    // Check if scanner is found in the output
    if (strpos($output, "HP") !== false) {
        return true; // Scanner is powered on
    } else {
        return false; // Scanner is not powered on
    }
}

// Check scanner status
//if (isScannerPoweredOn()) {
//    echo "Scanner is powered on.";
//} else {
//    echo "Scanner is not powered on.";
//}

?>
