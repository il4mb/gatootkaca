<?php
/**
 * MINIMAL WORK - PHP 8.0
 */
if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === "off") {
    $location = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: ' . $location);
    exit;
}

use Core\Navigator;

require_once __DIR__."/core/navigator.php";

$navigator = new Navigator();

$navigator->setPath($_GET["p"]);

$navigator->execute();