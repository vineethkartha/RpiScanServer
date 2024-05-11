<?php
function sendEmailWithAttachment($to, $subject, $attachmentFilePath, $attachmentFileName) {
    // Email headers
    $headers = "From: vineethsraspberry@gmail.com\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: multipart/mixed; boundary=\"boundary\"\r\n";

    // Email body
    $message = "--boundary\r\n";
    $message .= "Content-Type: text/plain; charset=\"UTF-8\"\r\n";
    $message .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
    $message .= "This is a test email with attachment.\r\n\r\n";

    // Attachment
    $attachment = chunk_split(base64_encode(file_get_contents($attachmentFilePath)));
    $message .= "--boundary\r\n";
    $message .= "Content-Type: application/pdf; name=\"$attachmentFileName\"\r\n";
    $message .= "Content-Disposition: attachment; filename=\"$attachmentFileName\"\r\n";
    $message .= "Content-Transfer-Encoding: base64\r\n\r\n";
    $message .= $attachment . "\r\n\r\n";
    $message .= "--boundary--";

    // Send email
    return mail($to, $subject, $message, $headers);
}
?>
