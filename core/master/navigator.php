<?php

/**
 * @var Navigator $this - we are in Navigator
 */

$recognize = [
    "dashboard" => [
        "name" => "Dashboard",
        "path" => "/dashboard"
    ],
    "crawler" => [
        "name" => "Crawler",
        "path" => "/crawler"
    ]
];

if (array_key_exists('1', $this->path)) {

    if (0 == strcmp($this->path[1], "login")) {

        require_once __DIR__ . "/login.php";

        exit;
    } else if (array_key_exists($this->path[1], $recognize)) {

        require_once dirname(__FILE__) . "/panel/view.php";

        exit;
    }
}
