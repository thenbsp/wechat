<?php

$files  = scandir('./');
$ignore = array('index.php', 'example.php');

echo '<ul>';

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
