<!DOCTYPE html>
<html lang="en" class="theme-light">
<head>
    <?php include$_SERVER['DOCUMENT_ROOT'].'/head.php';?>
    <title><?php echo $pageTitle ?: 'File'; ?></title>
    <meta name="description" content="___">
    <meta name="og:title" property="og:title" content="___">
    <meta property="og:image" content="___">
    <meta property="og:url" content="___"/>
    <link href="___" rel="canonical">
</head>

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
        <p>This is a work in progress version of Semm's website.</p>
        <p>Recent additions:</p>
        <ul>
            <li>A light/dark mode toggle that remembers your preferences with a simple cookie</li>
            <li>A basic search function. Right now it can only search from a list of links that must be manually created (see <a href="/links.xml" target="_blank">links.xml</a> to view the list). In the future this process will be automated to include every existing link, and in the further future it will be able to search the content of the pages as well.</li>
            <li>A breadcrumbs list in the header that is completely automated</li>
            <li>A font size slider for accessibility</li>
            <li>Folder creation capability (still buggy)</li>
            <li>File upload capability (works in testing but disabled on the public server for security)</li>
        </ul>
        <p>In development:</p>
        <ul>
            <li>A user login system that gives create and upload (and eventually move and delete) privileges to project members</li>
            <li>A comprehensive process for converting DOCX files to HTML (currently using Mammoth which works perfectly for conversion, but Semm's files all need heavy reformatting that I believe must be manual).</li>
        </ul>
    </div>
</div>





</div>

<div id="footer">
    <?php include$_SERVER['DOCUMENT_ROOT'].'/footer.html';?>
</div>

</body>

</html>