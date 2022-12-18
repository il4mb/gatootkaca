<?php
if (isset($_GET['p'])) {

    $root = $_SERVER['DOCUMENT_ROOT'];

    $path = $_GET['p'];

    $path = $root . "/public/upload/" . $path;

    //echo $path;

    if (is_file($path)) {

        $type = mime_content_type($path);

        $type = explode("/", $type)[1];

        if ($type == "svg" || str_contains($type, "svg")) {

            header("Content-Type: image/svg+xml");
            $svg = file_get_contents($path);

            print_r($svg);

            exit;
        }


        header("Content-Type: $type");

        $w = 20;
        $h = 20;
        if (isset($_GET['ukuran'])) {
            $size = $_GET['ukuran'];

            if ($size == "max") {
                echo file_get_contents($path);
            }

            $size = floatval($size);

            if ($size > 0) {
                $w = ceil($size / 2);
                $h = ceil($size / 2);
            }
        }

        list($width, $height) = getimagesize($path);
        $r = $width / $height;

        if ($w / $h > $r) {
            $newwidth = $h * $r;
            $newheight = $h;
        } else {
            $newheight = $w / $r;
            $newwidth = $w;
        }

        $dst = imagecreatetruecolor($newwidth, $newheight);

        if ($type == "png") {


            $src = imagecreatefrompng($path);
            $background = imagecolorallocate($dst, 0, 0, 0);
            imagecolortransparent($dst, $background);
            imagealphablending($dst, false);
            imagesavealpha($dst, true);

            imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
            imagepng($dst, null, 1);
        } else if ($type == "webp") {


            $src = imagecreatefromwebp($path);
            $background = imagecolorallocate($dst, 0, 0, 0);
            imagecolortransparent($dst, $background);
            imagealphablending($dst, false);
            imagesavealpha($dst, true);

            imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
            imagewebp($dst, null, 90);
        } else {

            $src = imagecreatefromjpeg($path);
            imagecopyresampled($dst, $src, 0, 0, 0, 0, $newwidth, $newheight, $width, $height);
            imagejpeg($dst, null, 90);
        }

        header('Pragma: public');
        header("Cache-Control: max-age=2629746");
        header('Expires: ' . gmdate('D, d M Y H:i:s \G\M\T', 2629746));


        imagedestroy($dst);


        exit;
    } else {

        echo "<div style='position: absolute; left: 50%; top: 50%; transform: translate(-50%, -50%); text-align: center'><h1>FILE TIDAK DI TEMUKAN</h1></div>";
    }
}
