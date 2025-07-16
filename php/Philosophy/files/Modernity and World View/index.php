<?php
    $currentDir = __DIR__; // Get name of current directory
    $currentPath = basename(__DIR__); //Get current path to directory
    $baseDir = __DIR__.DIRECTORY_SEPARATOR.$currentPath;
    
    include$_SERVER['DOCUMENT_ROOT'].'/templates/document.php';
?>