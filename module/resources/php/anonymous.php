<?php

use openbinary\Binary;

require_once __DATABASE__;
require_once __OPENBINARY__;

if (array_key_exists(1, $this->path)) {
    if ($this->path[1] == "create") {

        $html = file_get_contents(__DIR__ . "/../html/anonymous-create.html");
    } else if ($this->path[1] == "forum") {

        $token = $_COOKIE['token'];

        if ($token && strlen($token) > 0) {

            $html = file_get_contents(__DIR__ . "/../html/anonymous-forum.html");

            $html = str_replace("{TOKEN}", "TOKEN : <span class=\"text-warning\">" . $_COOKIE['token'] . "</span>", $html);
            
        }
    }

    $document->html = $html;
}


if (isset($_POST['name'], $_POST['message'])) {

    $name = filter_input(INPUT_POST, 'name', FILTER_UNSAFE_RAW);
    $message = filter_input(INPUT_POST, 'message', FILTER_UNSAFE_RAW);

    $date = date('Y-m-d H:i:s');
    $dateSplit = str_split($date);

    $token = "";
    foreach ($dateSplit as $chart) {
        $value = unpack('H*', $chart);
        $binary = new Binary(base_convert($value[1], 16, 2));
        $binary->sumWith('10100');
        $token .= pack('H*', base_convert($binary, 2, 16));
    }


    $stmt = $DBH->prepare("INSERT INTO anonymous_forum (token, name, message) VALUES (?, ?, ?)");
    if ($stmt->execute([$token, $name, $message])) {

        setcookie("token", $token, time() + (86400 * 30), "/"); // 86400 = 1 day
        header("Location: /anonymous/form/");
    }
}
