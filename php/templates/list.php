<!DOCTYPE html>
<html lang="en" class="theme-light">
<head>
    <?php include$_SERVER['DOCUMENT_ROOT'].'/head.php';?>
    <title><?php echo $pageTitle ?: 'File'; ?></title>
    <meta name="description" content="___">
    <meta name="og:title" property="og:title" content="___">
    <meta property="og:image" content="___">
    <meta property="og:url" content="___">
    <link href="___" rel="canonical">
</head>

<?php
    ob_start();

    // CREATE NEW FOLDER
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['newFolder'])) {
        $folderName = trim($_POST['folderName']);
        $message = '';
        $success = false;

        try {
            if (empty($folderName)) {
                throw new Exception("Folder name cannot be empty");
            }

            // Validate folder name (no special characters)
            if (preg_match('/[\/\\\\:\*\?"<>\|]/', $folderName)) {
                throw new Exception("Invalid folder name");
            }

            $newFolderPath = $GLOBALS['currentDir'] . '/' . $folderName;

            if (file_exists($newFolderPath)) {
                throw new Exception("A folder with this name already exists");
            }

            if (!mkdir($newFolderPath, 0777, true)) {
                throw new Exception("Failed to create folder");
            }

            // Create index.php inside new folder with template that calls /templates/list.php
            $indexFilePath = $newFolderPath . '/index.php';
            $indexContent = "<?php\n";
            $indexContent .= "    \$currentDir = __DIR__; // Get current directory\n";
            $indexContent .= "    include \$_SERVER['DOCUMENT_ROOT'].'/templates/list.php';\n";
            $indexContent .= "?>\n";
            
            if (!file_put_contents($indexFilePath, $indexContent)) {
                // Remove new folder if index.php creation fails
                rmdir($newFolderPath);
                throw new Exception("Failed to create index file in folder");
            }

            $message = "Folder '$folderName' created successfully";
            $success = true;
        } catch (Exception $e) {
            $message = "Error: " . $e->getMessage();
            $success = false;
        }

        header('Content-Type: application/json');
        echo json_encode(['success' => $success, 'message' => $message]);
        ob_end_flush(); // Send output and turn off buffering
        exit();
    }

    // UPLOAD NEW FILE
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['fileToUpload'])) {
        try {
            $target_dir = $GLOBALS['currentDir'] . '/files/';
            
            // Create /files/ directory if doesn't exist
            if (!file_exists($target_dir)) {
                if (!mkdir($target_dir, 0777, true)) {
                    throw new Exception('Failed to create upload directory');
                }
            }

            $original_filename = basename($_FILES["fileToUpload"]["name"]);
            $fileType = strtolower(pathinfo($original_filename, PATHINFO_EXTENSION));
            $base_name = pathinfo($original_filename, PATHINFO_FILENAME);
            $message = '';
            $success = false;

            // Check if file uploaded
            if ($_FILES['fileToUpload']['error'] !== UPLOAD_ERR_OK) {
                throw new Exception(getUploadErrorMessage($_FILES['fileToUpload']['error']));
            }
            
            // Check if file is correct size
            if ($_FILES["fileToUpload"]["size"] > 5000000) {
                throw new Exception("File too large (max 5MB)");
            }
            
            // Check if file is correct format
            if (!in_array($fileType, ["docx", "doc", "pdf"])) {
                throw new Exception("Only DOCX, DOC, & PDF files allowed");
            }

            // Create directory for file
            $newDir = $target_dir . $base_name;
            if (!mkdir($newDir, 0777, true)) {
                if (is_dir($newDir)) {
                    throw new Exception("A folder with this name already exists");
                } else {
                    throw new Exception("Failed to create folder");
                }
            }

            $target_file = $newDir . '/' . $original_filename;
            
            // Check if folder with same name already exists in directory
            if (file_exists($target_file)) {
                throw new Exception("File already exists in destination folder");
            }

            // Upload file into filename directory
            if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {

                // Create index.php inside new filename folder with template that calls /templates/document.php
                $indexFilePath = $newDir . '/index.php';
                $indexContent = "<?php\n";
                $indexContent .= "    \$currentDir = __DIR__; // Get current directory\n";
                $indexContent .= "    \$currentPath = basename(__DIR__); //Get current path to directory\n\n";
                $indexContent .= "    include \$_SERVER['DOCUMENT_ROOT'].'/templates/document.php';\n";
                $indexContent .= "?>\n";
                
                if (file_put_contents($indexFilePath, $indexContent) === false) {
                    throw new Exception("Failed to create index file");
                }
                
                $message = htmlspecialchars($original_filename) . " has been successfully uploaded";
                $success = true;
            } else {
                throw new Exception("Error moving uploaded file");
            }
        } catch (Exception $e) {

            // Clean up directory if created
            if (isset($newDir) && is_dir($newDir)) {
                $files = scandir($newDir);
                foreach ($files as $file) {
                    if ($file !== '.' && $file !== '..') {
                        $filePath = $newDir . '/' . $file;
                        if (is_file($filePath)) unlink($filePath);
                    }
                }
                rmdir($newDir);
            }
            $message = "Error: " . $e->getMessage();
            $success = false;
        }

        header('Content-Type: application/json');
        echo json_encode(['success' => $success, 'message' => $message]);
        ob_end_flush(); // Send output and turn off buffering
        exit();
    }

    function getUploadErrorMessage($error_code) {
        $errors = [
            UPLOAD_ERR_INI_SIZE => 'File exceeds upload_max_filesize',
            UPLOAD_ERR_FORM_SIZE => 'File exceeds form MAX_FILE_SIZE',
            UPLOAD_ERR_PARTIAL => 'File only partially uploaded',
            UPLOAD_ERR_NO_FILE => 'No file selected',
            UPLOAD_ERR_NO_TMP_DIR => 'Missing temporary folder',
            UPLOAD_ERR_CANT_WRITE => 'Failed to write file to disk',
            UPLOAD_ERR_EXTENSION => 'File upload blocked by extension',
        ];
        return $errors[$error_code] ?? "Unknown upload error ($error_code)";
    }

    function generateFolders() {
        $group1 = [];
        $allFolders = scandir($GLOBALS['currentDir']);
        foreach ($allFolders as $folder) {

            // Skip special directories, non-directories, and 'files' directory
            if ($folder === '.' || $folder === '..' || !is_dir($GLOBALS['currentDir'] . '/' . $folder) || $folder === 'files') {
                continue;
            }
            
            $group1[] = $folder;
        }
        natsort($group1);

        $html = '';
        if (!empty($group1)) {
            foreach ($group1 as $folder) {
                $folderName = htmlspecialchars($folder);
                $folderUrl = htmlspecialchars(rawurlencode($folder));
                $html .= "<a class='folder' href=\"$folderUrl\"><svg xmlns='http://www.w3.org/2000/svg' viewBox='0 -960 960 960'><path d='M160-160q-33 0-56.5-23.5T80-240v-480q0-33 23.5-56.5T160-800h207q16 0 30.5 6t25.5 17l57 57h320q33 0 56.5 23.5T880-640v400q0 33-23.5 56.5T800-160H160Z'/></svg><span>$folderName</span></a>\n";
            }
        } else {
            $html = '<span class="no-items">No folders found</span>';
        }
        return $html;
    }

    function generateFiles() {
        $group2 = [];
        $filesDir = $GLOBALS['currentDir'].DIRECTORY_SEPARATOR.'files';
        if (is_dir($filesDir)) {
            $fileFolders = scandir($filesDir);
            foreach ($fileFolders as $folder) {
                if ($folder === '.' || $folder === '..') continue;
                if (!is_dir($filesDir . '/' . $folder)) continue;
                if (strpos($folder, 'files') === 0) continue;
                
                $group2[] = $folder;
            }
            natsort($group2);
        }

        $html = '';
        if (!empty($group2)) {
            foreach ($group2 as $folder) {
                $folderName = htmlspecialchars($folder);
                $folderUrl = rawurlencode($folder);
                $html .= "<a class='file' href=\"files/$folderUrl\"><svg xmlns='http://www.w3.org/2000/svg' viewBox='0 -960 960 960'><path d='M242.87-71.87q-37.78 0-64.39-26.61t-26.61-64.39v-634.26q0-37.78 26.61-64.39t64.39-26.61H525.8q18.16 0 34.69 6.84 16.53 6.83 29.21 19.51L781.78-669.7q12.68 12.68 19.51 29.21 6.84 16.53 6.84 34.69v442.93q0 37.78-26.61 64.39t-64.39 26.61H242.87Zm274.26-570.76q0 19.15 13.17 32.33 13.18 13.17 32.33 13.17h154.5l-200-200v154.5Z'/></svg><span>$folderName</span></a>\n";
            }
        } else {
            $html = '<span class="no-items">No files found</span>';
        }
        return $html;
    }
?>

<body id="body" class="close">

<div id="header">
    <?php include$_SERVER['DOCUMENT_ROOT'].'/header.php';?>
</div>

<div id="content">
    <h1>
        <?php echo $pageTitle ?: 'File'; ?>
    </h1>
    <div class="about-div">
        <div class="about-text">
            <h3>Dev Notes:</h3>
            <p>The folder creating button is currently buggy (JSON parsing issue) but it works. Click submit and reload the page, and your new directory will appear.</p>
            <p>The file creating button works in testing but is disabled on the server. Feel free to try it to see what the interaction is like, but be aware that your file will not upload. In the future, there will be a system to let logged in users create, edit, move, and delete folders.</p>
            <p>For both the folder and file creation systems, in the future they will reload automatically without reloading the page in order to display newly added folders/files.</p>
            <p>On the final version of the site, the edit controls will be hidden publically and only availabe to logged in users. For the current alpha version, they are displayed at limited capability for testing and feedback.</p>
        </div>
    </div>

    <div class="folders-div">
        <div class="folders-title">
            <h2 class="folders-h2">Folders</h2>
            <div id="addFolderButton" class="btn">
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960"><path d="M559.28-313.3h83.83v-82.87h83.59V-480h-83.59v-83.11h-83.83V-480H475.7v83.83h83.58v82.87ZM162.87-151.87q-37.78 0-64.39-26.61t-26.61-64.39v-474.26q0-37.78 26.61-64.39t64.39-26.61h233.54L480-724.54h317.13q37.78 0 64.39 26.61 26.61 26.6 26.61 64.39v390.67q0 37.78-26.61 64.39t-64.39 26.61H162.87Z"/></svg>
                <div id="folderForm" class="folder-form">
                    <input type="text" id="folderName" placeholder="Folder name" required>
                </div>
            </div>
            <button id="createFolderBtn">
                <span>Create</span>
            </button>
            <button id="cancelFolderBtn" class="cancel">
                <span>Cancel</span>
            </button>
            <div id="folderResultDiv"></div>
        </div>
        <div class="folders">
            <?php echo generateFolders(); ?>
        </div>
    </div>

    <div class="files-div">
        <div class="files-title">
            <h2 class="files-h2">Files</h2>
            <div id="upload-div">
                <form id="uploadForm" method="post" enctype="multipart/form-data">
                    <label for="fileToUpload">
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960"><path d="M246.78-60.78q-44.3 0-75.15-30.85-30.85-30.85-30.85-75.15v-626.44q0-44.3 30.85-75.15 30.85-30.85 75.15-30.85h277.39q21.23 0 40.46 7.98 19.24 7.98 34.2 22.94L788.3-678.83q14.96 14.96 22.94 34.2 7.98 19.23 7.98 40.46v437.39q0 44.3-30.85 75.15-30.85 30.85-75.15 30.85H246.78Zm266.44-585.44q0 22.09 15.45 37.55 15.46 15.45 37.55 15.45h147l-200-200v147ZM440-353.78v80q0 17 11.5 28.5t28.5 11.5q17 0 28.5-11.5t11.5-28.5v-80h80q17 0 28.5-11.5t11.5-28.5q0-17-11.5-28.5t-28.5-11.5h-80v-80q0-17-11.5-28.5t-28.5-11.5q-17 0-28.5 11.5t-11.5 28.5v80h-80q-17 0-28.5 11.5t-11.5 28.5q0 17 11.5 28.5t28.5 11.5h80Z"/></svg>
                    </label>
                    <input type="file" name="fileToUpload" accept=".docx,.doc,.pdf" id="fileToUpload">
                </form>
            </div>
            <button type="submit" id="upload" name="submit">
                <span>Upload</span>
            </button>
            <button id="cancelUpload">
                <span>Cancel</span>
            </button>
            <div id="uploadResult"></div>
        </div>
        <div class="files">
            <?php echo generateFiles(); ?>
        </div>
    </div>

</div>

<div id="footer">
    <?php include$_SERVER['DOCUMENT_ROOT'].'/footer.html';?>
</div>

<script>
    document.getElementById('addFolderButton').addEventListener('click', function() {
        const form = document.getElementById('folderForm');
        const btn = document.getElementById('addFolderButton');
        const create = document.getElementById('createFolderBtn');
        const cancel = document.getElementById('cancelFolderBtn');
        form.classList.add('active');
        btn.classList.add('active');
        btn.classList.remove('btn');
        create.classList.add('active');
        cancel.classList.add('active');
        document.getElementById('folderName').focus();
    });

    document.getElementById('cancelFolderBtn').addEventListener('click', function(e) {
        const btn = document.getElementById('addFolderButton');
        const create = document.getElementById('createFolderBtn');
        const cancel = document.getElementById('cancelFolderBtn');
        e.preventDefault();
        document.getElementById('folderForm').classList.remove('active');
        document.getElementById('folderName').value = '';
        btn.classList.add('btn');
        btn.classList.remove('active');
        create.classList.remove('active');
        cancel.classList.remove('active');
    });

    document.getElementById('createFolderBtn').addEventListener('click', function() {
        const folderName = document.getElementById('folderName').value.trim();
        const resultDiv = document.getElementById('folderResultDiv');

        if (!folderName) {
            alert('Please enter a folder name');
            return;
        }

        const formData = new FormData();
        formData.append('newFolder', true);
        formData.append('folderName', folderName);

        fetch('', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Refresh page to show new folder (doesn't work, replace with AJAX anyway)
                location.reload();
            } else {
                alert(data.message);
            }
        })
        .catch(error => {
            resultDiv.innerHTML = 'Upload Error: ' + error.message;
            resultDiv.className = 'upload-error';
            console.error('Upload failed:', error);
        });
    });

    document.getElementById('uploadForm').addEventListener('submit', function(e) {
        e.preventDefault();
        const form = this;
        const formData = new FormData(form);
        const resultDiv = document.getElementById('uploadResult');
        const cancel = document.getElementById('cancelUpload');
        
        // Show loading state (doesn't work)
        resultDiv.innerHTML = '<span class="upload-pending">Uploading...</span>';
        resultDiv.className = '';

        fetch('', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            return response.text().then(text => {
                try {
                    return JSON.parse(text);
                } catch {
                    throw new Error(`Invalid response: ${text.slice(0, 100)}...`);
                }
            });
        })
        .then(data => {
            if (typeof data.success !== 'boolean') {
                throw new Error('Invalid server response format');
            }
            
            resultDiv.innerHTML = data.message;
            resultDiv.className = data.success ? 'upload-success' : 'upload-error';
            
            if (data.success) {
                form.reset();
                // Reload the page to show updated file list (doesn't work, replace with AJAX anyway)
                location.reload();
            }
        })
        .catch(error => {
            resultDiv.innerHTML = 'Upload Error: ' + error.message;
            resultDiv.className = 'upload-error';
            console.error('Upload failed:', error);
        });
    });

    document.addEventListener('DOMContentLoaded', function() {
        // Clear folder name input on load
        const folderNameInput = document.getElementById('folderName');
        if (folderNameInput) {
            folderNameInput.value = '';
        }
        
        // Clear file upload input on load
        const fileUploadInput = document.getElementById('fileToUpload');
        if (fileUploadInput) {
            fileUploadInput.value = '';
        }

        // Show file upload input on click
        const uploadDiv = document.getElementById('upload-div');
        if (uploadDiv) {
            uploadDiv.addEventListener('click', function() {
                const fileInput = document.getElementById('fileToUpload');
                if (fileInput) {
                    fileInput.classList.add('upload');
                }
                
                const uploadButton = document.getElementById('upload');
                if (uploadButton) {
                    uploadButton.classList.add('upload');
                }

                const cancelUpload = document.getElementById('cancelUpload');
                if (uploadButton) {
                    cancelUpload.classList.add('upload');
                }
            });
        }

        // File upload handling
        const fileInput = document.getElementById('fileToUpload');
        const uploadButton = document.getElementById('upload');
        const cancelButton = document.getElementById('cancelUpload');
        
        if (fileInput && uploadButton && cancelButton) {
            fileInput.addEventListener('change', function() {
                if (this.files.length > 0) {
                    uploadButton.style.display = '';
                    cancelButton.style.display = '';
                }
            });

            cancelButton.addEventListener('click', function(e) {
                e.preventDefault();
                fileInput.value = '';
                fileInput.classList.remove('upload');
                uploadButton.classList.remove('upload');
                cancelButton.classList.remove('upload');
                const resultDiv = document.getElementById('uploadResult');
                if (resultDiv) {
                    resultDiv.innerHTML = '';
                    resultDiv.className = '';
                }
            });
        }
    });
</script>

</body>

</html>