<?php
namespace Lib;

use Curl\Curl;
use Directory;
use Exception;

class DownloadSite
{
    private const DOWNLOAD_DIRECTORY = "Downloads/";
    private $curl;

    public function __construct()
    {
        $code = <<<'HTML'
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Downloads</title>
</head>
<body>
    <h1>Elements:</h1>
    <?php
    $dirElements = scandir(__DIR__);
    $path = basename(__DIR__);

    foreach ($dirElements as $element) {
        if (strcmp($element, 'index.php') !== 0) {
            echo "<p><a href='$path/" . $element . "'>" . preg_split("|[.]|", $element)[0] . "</a></p>";
        }
    }
    ?>
</body>
</html>
HTML;
        $this->curl = new Curl();
        if (! file_exists(self::DOWNLOAD_DIRECTORY) || ! is_dir(self::DOWNLOAD_DIRECTORY)) {
            mkdir(self::DOWNLOAD_DIRECTORY, 0777, true);
            $index = fopen(self::DOWNLOAD_DIRECTORY . "index.php", 'w');
            fputs($index, $code);
            fclose($index);
        }
    }

    public function download($url)
    {
        if (empty($url)) {
            $message = 'URL not defined!';
            echo $message;
            throw new Exception($message, 400);
        }

        $urlArray = preg_split("|[/]|", $url);
        $filename = '';

        unset($urlArray[0]); // http|https

        $filename = trim(preg_replace('|[.]|', "_", implode('_', $urlArray)), '_') . ".html";
        $response = $this->curl->get($url)->getResponse();
        $path = self::DOWNLOAD_DIRECTORY . $filename;
        $file = fopen($path, 'w');

        if ($file !== false) {
            fputs($file, $response);
            fclose($file);
        }

        return "Site is download in your filesystem by path: " . "/$path\n\n";
    }
}
