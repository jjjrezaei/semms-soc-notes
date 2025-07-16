<!DOCTYPE html>
<html lang="en" class="theme-light">
<head>
    <?php include$_SERVER['DOCUMENT_ROOT'].'/head.php';?>
    <title><?php $title ?></title>
    <meta name="description" content="___">
    <meta name="og:title" property="og:title" content="___">
    <meta property="og:image" content="___">
    <meta property="og:url" content="___"/>
    <link href="___" rel="canonical">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/mammoth/1.4.2/mammoth.browser.min.js"></script>
</head>

<?php
    // Define extensions in priority order
    $extensions = ['.docx', '.doc', '.pdf'];
    $selectedExtension = '.docx'; // Default fallback

    // Check for filesystem access availability
    if (isset($_SERVER['DOCUMENT_ROOT'])) {
        // Build full filesystem path
        $fileBase = $_SERVER['DOCUMENT_ROOT'] . '/' . ltrim($currentPath, '/');
        
        // Check each extension in priority order
        foreach ($extensions as $ext) {
            if (file_exists($fileBase . $ext)) {
                $selectedExtension = $ext;
                break;
            }
        }
    }
?>

<body id="body" class="close">

<div id="header">
    <?php include$_SERVER['DOCUMENT_ROOT'].'/header.php';?>
</div>

<div id="content">

    <h1><?php echo $pageTitle ?: 'File'; ?></h1>

    <div class="about-div">
        <div class="about-text">
            <p>If the document is not showing, it is probably a .doc file. Only .docx files are currently supported.</p>
            <p>If the document appears but text is not formatted correctly, it is a .docx file that hasn't been optimized for HTML conversion.</p>
        </div>
    </div>
 
    <div class="doc">
        <div id="download">
            <p>Download this document</p>
            <a href="<?php $GLOBALS['currentDir']; echo $currentPath . $selectedExtension; ?>">
                <button>
                    <svg id="download-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960"><path d="M480-322.87 268.52-534.35l63.89-65.41L434.5-497.44v-310.69h91v310.69l102.09-102.32 63.89 65.41L480-322.87Zm-237.13 171q-37.78 0-64.39-26.61t-26.61-64.39v-120h91v120h474.26v-120h91v120q0 37.78-26.61 64.39t-64.39 26.61H242.87Z"/></svg>
                </button>
            </a>
        </div>

        <div id="output"></div>

        <script>
            const docxUrl = "<?php $GLOBALS['currentDir']; echo $currentPath . $selectedExtension ?>";

            const fileExtension = docxUrl.split('.').pop().toLowerCase();

            if (fileExtension === 'doc') {
                // Handle .doc files - show unsupported message
                document.getElementById('output').innerHTML = '<p>.doc files not yet supported</p>';
            } else {
                // Process .docx files with Mammoth
                fetch(docxUrl)
                    .then(r => r.arrayBuffer())
                    .then(buffer => mammoth.convertToHtml({ arrayBuffer: buffer }))
                    .then(result => {
                        document.getElementById('output').innerHTML = result.value;
                    })
                    .catch(e => console.error(e));
            }
        </script>
    </div>

</div>

<div id="footer">
    <?php include$_SERVER['DOCUMENT_ROOT'].'/footer.html';?>
</div>

</body>

</html>