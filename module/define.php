<?php
$root = $_SERVER['DOCUMENT_ROOT'];

define("__MENU__", dirname(__FILE__) . "/resources/json/menu.json");

define("__ENCH__", dirname(__FILE__) . "/utils/ench.php");

define("__OPENBINARY__", dirname(__FILE__)."/utils/OpenBinary.php");

define("__DATABASE__", $root . "/core/connection/database.php");

/**
 * ONLY DEFINE CLASSES FROM MODULE
 *      |       |       |
 *      V       V       V
 */
define("__USERMODEL__", $root . "/module/classes/userModel.php");

define("__CRAWLER__", $root . "/module/classes/crawler.php");
define("__CRAWL_SCHEDULE__", $root . "/module/classes/crawlScheduleManage.php");

define("__SUBMITSITE__", $root . "/module/classes/submitSite.php");

define("__TOOLS__", $root . "/module/utils/tools.php");
