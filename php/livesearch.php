
<?php
$xmlDoc=new DOMDocument();
$xmlDoc->load("https://semm.emrysmayell.com/links.xml");

$x=$xmlDoc->getElementsByTagName('link');

// Get the q parameter from URL
$q=$_GET["q"];

// Lookup all links from the xml file if length of q>0
if (strlen($q)>0) {
  $hint="";
  for($i=0; $i<($x->length); $i++) {
    $y=$x->item($i)->getElementsByTagName('title');
    $z=$x->item($i)->getElementsByTagName('url');
    if ($y->item(0)->nodeType==1) {
      // Find a link matching the search text
      if (stristr($y->item(0)->childNodes->item(0)->nodeValue,$q)) {
        if ($hint=="") {
          $hint="<div class='box'><a href='" .
          $z->item(0)->childNodes->item(0)->nodeValue .
          "'>" .
          $y->item(0)->childNodes->item(0)->nodeValue . "</a></div>";
        } else {
          $hint=$hint . "<div class='box'><a href='" .
          $z->item(0)->childNodes->item(0)->nodeValue .
          "'>" .
          $y->item(0)->childNodes->item(0)->nodeValue . "</a></div>";
        }
      }
    }
  }
}

// Set output to "no suggestion" if no hint found
if ($hint=="") {
  $response="<div class='box no-results'><em><p>No results</p><em></div>";
  // Else, set to correct values
} else {
  $response=$hint;
}

echo $response;
?> 