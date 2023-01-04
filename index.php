<?php
/**
 * MINIMAL WORK - PHP 8.0
 */
/*
if (empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] === "off") {
    $location = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: ' . $location);
    exit;
}
*/

if ( is_file("public/asset/" . $_GET["p"])) {

    require_once __DIR__."/core/handler/asset-manager.php";
    exit;
} 


global $structure;
$structure = file_get_contents(__DIR__."/.structure");
$structure = explode("\r\n", $structure);
$structure = array_values(array_filter($structure, function($value) { return !is_null($value) && $value !== ''; }));


use Core\Navigator;

require_once __DIR__."/core/navigator.php";

$navigator = new Navigator();

$navigator->setPath($_GET["p"]);

$navigator->execute();