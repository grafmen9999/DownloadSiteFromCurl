<?php

use Lib\DownloadSite;

include "vendor/autoload.php";
if ($argc > 1) {
    $downloadSite = new DownloadSite;
    
    for ($i = 1; $i < $argc; $i++) {
        $url = $argv[$i];
        echo $downloadSite->download($url);
    }
} else {
    $message = 'URL not defined!';
    echo $message;
    throw new Exception($message, 400);
}
