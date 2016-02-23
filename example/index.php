<?php

$files  = scandir('./');
$ignore = array('index.php', 'api.php', 'example.php', 'payment-qrcode-response.php');

echo '<meta name="viewport" content="width=device-width, initial-scale=1.0"><ul>';

foreach( $files AS $file ) {

    // ignore files
    if( in_array($file, $ignore) ) {
        continue;
    }

    // filter php file 
    if( 'php' !== pathinfo($file, PATHINFO_EXTENSION) ) {
        continue;
    }

    echo sprintf('<li><a href="./%s">%s</a></li>', $file, $file);
}

echo '</ul>';
