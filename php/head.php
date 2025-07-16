<meta charset="UTF-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="copyright" content="___">
<meta name="robots" content="noindex, nofollow">
<link rel="icon" type="image/jpg" href="/favicon.png">
<link href="/styles.css" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<?php
    // Get clean title to be called on pages
    $uriParts = array_filter(explode('/', $_SERVER['REQUEST_URI']));
    $lastPart = end($uriParts);
    $pageTitle = str_replace(['.php', '01_', '%20', '_'], ['', '', ' ', ' '], $lastPart);

    // Breadcrumbs function
    function breadcrumbs($separator = '<span>></span>', $home = 'Home') {
        // Get the path to file, splits the string (using '/') into array, then filters out empty values
        $path = array_filter(explode('/', parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH)));
    
        // Build base URL (accounting for HTTPS)
        $base = ($_SERVER['HTTPS'] ? 'https' : 'http') . '://' . $_SERVER['HTTP_HOST'] . '/';
        $escaped_base = htmlspecialchars($base, ENT_QUOTES);
    
        // Initialize array for breadcrums, starting with homepage
        $breadcrumbs = Array("<div class='crumb'><a href=\"$escaped_base\"><button>" . htmlspecialchars($home, ENT_QUOTES) . "</button></a></div>");
        
        // If no path, show root
        if (empty($path)) {
            return implode($separator, $breadcrumbs);
        }
    
        // Find index for last value in array (current folder)
        $keys = array_keys($path);
        $last = end($keys);

        // Precompute non-files keys to identify last visible segment
        $nonFilesKeys = [];
        foreach ($path as $x => $crumb) {
            if ($crumb !== 'files') {
                $nonFilesKeys[] = $x;
            }
        }
        $lastNonFilesKey = empty($nonFilesKeys) ? null : end($nonFilesKeys);

        // Accumulate path segments
        $acc_path = [];

        // Breadcrumbs after root folder
        foreach ($path AS $x => $crumb) {
            $acc_path[] = $crumb;
            $current_link = $base . implode('/', $acc_path);
            $escaped_link = htmlspecialchars($current_link, ENT_QUOTES);
            // Skip breadcrumb creation for 'files' directory
            if ($crumb === 'files') {
                continue;
            }

            // Clean title by replacing some characters with nothing or spaces
            $title = str_replace(Array('.php', '01_', '%20', '_'), Array('', '', ' ', ' '), $crumb);
            $escaped_title = htmlspecialchars($title, ENT_QUOTES);

            // If last index (that isn't "files"), create text
            if ($x === $lastNonFilesKey) {
                $breadcrumbs[] = "<div class='crumb'><p>$escaped_title</p></div>";
            // If not last index, create link and button
            } else {
                $breadcrumbs[] = "<div class='crumb'><a href=\"$escaped_link\"><button>$escaped_title</button></a></div>";
            }
        }
        // Turn array into string and output
        return implode($separator, $breadcrumbs);
    }
?>