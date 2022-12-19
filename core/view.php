<?php

namespace Core;

$root = $_SERVER['DOCUMENT_ROOT'];
$site_url = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
$current_url = $site_url . $_SERVER['REQUEST_URI'];

/**
 * @var Navigator $this - we are in Navigator
 */

if (array_key_exists('0', $this->path) && 0 == strcmp($this->path[0], "master")) {

    if (file_exists(__DIR__ . "/master/navigator.php")) {

        require_once __DIR__ . "/master/navigator.php";
    }
}

require_once dirname(__FILE__) . "/../module/define.php";
require_once $_SERVER['DOCUMENT_ROOT'] . "/module/classes/viewManager.php";

use Module\Classes\VM;

$document = new VM(json_decode(file_get_contents(__MENU__), true));

$first_path = array_key_exists('0', $this->path) ? $this->path[0] : "";
$document->setCurrentPosition($first_path);

$document->getDocument();

if ($document->getPHPSupport()) {
    require_once $document->getPHPSupport();
}

$document->init();

?>
<!DOCTYPE html>
<html lang="id" chartset="utf-8">

<head>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <?php echo $document->head ?>

    <link rel="stylesheet" href="/css/gatoot-core.css?v=8" />
    <link rel="stylesheet" href="/Bootstrap/css/bootstrap.min.css" />
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-4GV9L1NQJG"></script>
    <script>
        window.dataLayer = window.dataLayer || [];

        function gtag() {
            dataLayer.push(arguments);
        }
        gtag('js', new Date());

        gtag('config', 'G-4GV9L1NQJG');
    </script>
</head>

<body>
    <div class="document">
        <?php echo $document->body ?>
    </div>

    <script type="text/javascript" src="/js/jquery-3.6.1.min.js"></script>
    <script type="text/javascript" src="/Bootstrap/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="/js/gatoot-core.js?v=5"></script>
</body>

</html>