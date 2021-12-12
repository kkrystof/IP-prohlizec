<?php
header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found", true, 404);
//include("notFound.php");

//Not Found
//The requested URL /test.php was not found on this server.
//
//That's because the web server doesn't send that page when PHP returns a 404 code (at least Apache doesn't). PHP is responsible for sending all its own output. So if you want a similar page, you'll have to send the HTML yourself, e.g.:
//
//<?php
//header($_SERVER["SERVER_PROTOCOL"]." 404 Not Found", true, 404);
//include("notFound.php");
//
//You could configure Apache to use the same page for its own 404 messages, by putting this in httpd.conf:
//
//ErrorDocument 404 /notFound.php
?>
<!doctype html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link rel="stylesheet" href="style.css">
    <title>Not Found</title>
</head>
<body>
<div style="width: max-content; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
    <h1 style="color: red;width: max-content;">404 : NOT FOUND</h1>
    <p>Stránka nenalezena :(</p>
</div>
</body>
</html>