<?php

$target_dir = $GLOBALS['currentDir'].'/files/';

if (!isset($GLOBALS['currentDir'])) {
    die("Upload directory not configured");
}

// Create directory if doesn't exist
if (!file_exists($target_dir)) {
    mkdir($target_dir, 0777, true);
}

$target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
$uploadOk = 1;
$fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

// Check if file already exists
if (file_exists($target_file)) {
    echo "Sorry, file already exists.";
    $uploadOk = 0;
}

// Check file size (5MB limit, in bytes)
if ($_FILES["fileToUpload"]["size"] > 5000000) {
    echo "Sorry, your file is too large (max 5MB).";
    $uploadOk = 0;
}

// Allow only specified formats
$allowedTypes = ["docx", "doc", "pdf"];
if(!in_array($fileType, $allowedTypes)) {
    echo "Sorry, only DOCX, DOC, & PDF files are allowed.";
    $uploadOk = 0;
}

// Upload if no errors
if ($uploadOk == 1) {
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "The file ". htmlspecialchars(basename($_FILES["fileToUpload"]["name"])). " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }
}
?>