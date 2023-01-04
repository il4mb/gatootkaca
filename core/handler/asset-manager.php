<?php

/**
 * EXTENDS FROM PUBLIC_MODULE
 * --- CREATE BY ILHAM.B
 * @var array $params - import from current file
 */
header('Pragma: public');
header("Cache-Control: max-age=18600");
header('Expires: '. gmdate('D, d M Y H:i:s \G\M\T', 18600));

if (isset($_GET['p'])) {
    $param = $_GET['p'];

    if(is_file($_SERVER["DOCUMENT_ROOT"] . "/" . $param)) {
        $result = $_SERVER["DOCUMENT_ROOT"] . "/" . $param;
    } else {
        $result = $_SERVER["DOCUMENT_ROOT"] . "/public/asset/" . $param;
    }

    $fn = pathinfo($param, PATHINFO_FILENAME);
    $extension = pathinfo($param, PATHINFO_EXTENSION);

    if (is_file($result)) {

        $mime_type = preg_replace("[plain]", pathinfo($result, PATHINFO_EXTENSION), mime_content_type($result));

        switch ($extension) {
            
            case 'css':
                $mime_type = 'text/css';
                break;

            case 'js':
                $mime_type = 'application/javascript';
                break;

        }

        $fp = fopen($result, 'rb');
        // send the right headers
        header("Content-Type: $mime_type");
        header("Content-Length: " . filesize($result));

        // dump the picture and stop the script
        fpassthru($fp);
        fclose($fp);
        exit;
    }
}

header("HTTP/1.1 404 File tidak di temukan", true, 404);
echo "<h2 style='position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%)'>FILE TIDAK DI TEMUKAN !</h2>";
