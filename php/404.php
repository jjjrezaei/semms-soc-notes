<!DOCTYPE html>
<html lang="en">
<head>
    <?php include$_SERVER['DOCUMENT_ROOT'].'/head.php';?>
    <title>404 Not Found</title>
    <style>
    
    .p-404-container {
        max-width: 800px;
        margin: auto;
    }

    .p-404 {
        margin: 15px 15px 20px;
        text-align: center;
    }

    @media only screen and (min-width: 700px) {
        .button-left {
            margin-left: 15px;
        }
        .button-middle {
            margin: 0 15px;
        }
        .button-right {
            margin-right: 15px;
        }
    }

    @media only screen and (max-width: 700px) {
        .buttons-container {
            flex-direction: column;
        }
        .buttons-container button {
            margin: 0 15px 15px;
        }
        .buttons-container div {
            display: flex;
            justify-content: center;
        }
    }

    .buttons-container {
        display: flex;
        justify-content: center;
        margin: 45px 0;
    }

    </style>
</head>

<body id="body" class="error close">

<div id="header">
    <?php include$_SERVER['DOCUMENT_ROOT'].'/header.php';?>
</div>

<div id="content">












<div class="title-container">
    <h1 class="title">
        404: PAGE NOT FOUND
    </h1>
</div>

<div class="p-404-container">
    <p class="p-404 link-underlines">
        It looks like the page you're searching for doesn't exist.
    </p>
    <p class="p-404 link-underlines">
        While you figure it out, here are some things to keep you occupied!
    </p>
</div>

<div class="buttons-container">
    <div>
        <a href="/">
            <button class="button-left">
                Home
            </button>
        </a>
    </div>
    <div>
        <a href="/syllabus/">
            <button class="button-middle">
                Syllabus
            </button>
        </a>
    </div>
    <div>
        <a href="/resources/">
            <button class="button-right">
                Resources
            </button>
        </a>
    </div>
</div>












</div>

<div id="footer">
    <?php include$_SERVER['DOCUMENT_ROOT'].'/footer.html';?>
</div>

</body>

</html>