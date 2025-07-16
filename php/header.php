<!DOCTYPE html>
<head>
    <script>
        function showResult(str) {
        if (str.length==0) {
            document.getElementById("livesearch").innerHTML="";

            const boxes = document.querySelectorAll('#livesearch, #search-button');

            for (const box of boxes) {
                box.classList.add('none');
                box.classList.remove('results');
            }
            return;
        }
        else {
            const boxes = document.querySelectorAll('#livesearch, #search-button');

            for (const box of boxes) {
                box.classList.remove('none');
                box.classList.add('results');
            }
        }
        var xmlhttp=new XMLHttpRequest();
        xmlhttp.onreadystatechange=function() {
            if (this.readyState==4 && this.status==200) {
                document.getElementById("livesearch").innerHTML=this.responseText;
                document.getElementById("livesearch");
            }
        }
        xmlhttp.open("GET","/livesearch.php?q="+str,true);
        xmlhttp.send();
        }
    </script>
</head>
<html>
    <div id="header-top">
        <div id="header-left">
            <div id="search-div" class="default">
                <div id="search-button" class="default none">
                    <div id="search-q-svg-div" class="search-svg-div default" onclick="addSearch()">
                        <svg id="search-q-svg" class="search-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1080 1080"><defs></defs><path class="cls-1" d="M1058.03,951.97l-258.4-258.4c50.63-71.67,80.37-159.15,80.37-253.57,0-121.5-49.25-231.5-128.87-311.13C671.5,49.25,561.5,0,440,0,196.99,0,0,196.99,0,440s196.99,440,440,440c94.42,0,181.89-29.74,253.57-80.37l258.4,258.4c14.65,14.65,33.84,21.97,53.03,21.97s38.38-7.32,53.03-21.97c29.29-29.29,29.29-76.77,0-106.06ZM645.06,645.06c-54.77,54.78-127.6,84.94-205.06,84.94s-150.29-30.16-205.06-84.94c-54.78-54.77-84.94-127.6-84.94-205.06s30.16-150.29,84.94-205.06c54.77-54.78,127.6-84.94,205.06-84.94s150.29,30.16,205.06,84.94c54.78,54.77,84.94,127.6,84.94,205.06s-30.16,150.29-84.94,205.06Z"/></svg>
                    </div>
                    <form>
                        <input id="search-bar" placeholder="Search..." type="text" onkeyup="showResult(this.value)">
                        <div id="livesearch"></div>
                    </form>
                    <div id="search-x-svg-div" class="search-svg-div default" onclick="clearSearch()">
                        <svg id="search-x-svg" class="search-svg default" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1080 1080"><defs></defs><path class="cls-1" d="M398.16,540L29.38,171.22c-39.17-39.17-39.17-102.67,0-141.84h0c39.17-39.17,102.67-39.17,141.84,0l368.78,368.78L908.78,29.38c39.17-39.17,102.67-39.17,141.84,0h0c39.17,39.17,39.17,102.67,0,141.84l-368.78,368.78,368.78,368.78c39.17,39.17,39.17,102.67,0,141.84h0c-39.17,39.17-102.67,39.17-141.84,0l-368.78-368.78-368.78,368.78c-39.17,39.17-102.67,39.17-141.84,0h0c-39.17-39.17-39.17-102.67,0-141.84l368.78-368.78Z"/></svg>
                    </div>
                </div>
            </div>
            <div id="logo-div">
                <a href="/">
                    <h3>
                        Semm's Files
                    </h3>
                </a>
            </div>
        </div>
        <div id="header-right">
            <div class="header-menu">
                <a class="u-philosophy" href="/Philosophy">
                    Philosophy
                </a>
                <a class="u-race" href="/Race">
                    Race
                </a>
                <a class="u-society" href="/Society">
                    Society
                </a>
                <a class="u-theory" href="/Theory">
                    Theory
                </a>
                <a class="u-war" href="/War">
                    War
                </a>
            </div>
            <div id="mm" class="default">
                <div id="mm-placeholder" class="default"></div>
                <div id="mm-background" class="default" onclick="closeMenu()"></div>
                <div id="mm-button" class="default">
                    <svg id="mm-open-svg" class="mm-svg default" onclick="openMenu()" xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960"><path d="M86-673v-126h788v126H86Zm0 512v-126h788v126H86Zm0-256v-126h788v126H86Z"/></svg>
                    <svg id="mm-x-svg" class="mm-svg default" onclick="closeMenu()" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1080 1080"><defs></defs><path d="M398.16,540L29.38,171.22c-39.17-39.17-39.17-102.67,0-141.84h0c39.17-39.17,102.67-39.17,141.84,0l368.78,368.78L908.78,29.38c39.17-39.17,102.67-39.17,141.84,0h0c39.17,39.17,39.17,102.67,0,141.84l-368.78,368.78,368.78,368.78c39.17,39.17,39.17,102.67,0,141.84h0c-39.17,39.17-102.67,39.17-141.84,0l-368.78-368.78-368.78,368.78c-39.17,39.17-102.67,39.17-141.84,0h0c-39.17-39.17-39.17-102.67,0-141.84l368.78-368.78Z"/></svg>
                    <div id="mm-menu" class="default">
                        <div class="mm-menu-header">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 -960 960 960"><path d="M86-673v-126h788v126H86Zm0 512v-126h788v126H86Zm0-256v-126h788v126H86Z"/></svg>
                            <h2>Menu</h2>
                        </div>
                        <p>
                            <a class="u-philosophy" href="/Philosophy">
                                Philosophy
                            </a>
                        </p>
                        <p>
                            <a class="u-race" href="/Race">
                                Race
                            </a>
                        </p>
                        <p>
                            <a class="u-society" href="/Society">
                                Society
                            </a>
                        </p>
                        <p>
                            <a class="u-theory" href="/Theory">
                                Theory
                            </a>
                        </p>
                        <p>
                            <a class="u-war" href="/War">
                                War
                            </a>
                        </p>
                        <div class="mm-final"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div id="header-bottom">
        <div class="breadcrumbs">
            <?php
                echo breadcrumbs() ;
            ?>
        </div>
    </div>
</html>