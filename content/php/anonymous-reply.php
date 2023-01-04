<?php

require_once __TOOLS__;

use Module\ENCH;


$actual_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

if (array_key_exists(2, $this->path)) {

    $tokenForum = $this->path[2];
    $tokenForum = base64_decode($tokenForum);
    $tokenForum = ENCH::decrypt($tokenForum);

    $stmt = $DBH->prepare("SELECT * FROM anonymous_forum WHERE token=?");
    $stmt->execute([$tokenForum]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($result) {

        $ip = getUserIP();

        $stmt = $DBH->prepare("SELECT * FROM anonymous_analis WHERE token=? AND ip=?");
        $stmt->execute([$tokenForum, $ip]);
        $resultI = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$resultI) {

            $stmt = $DBH->prepare("INSERT INTO anonymous_analis (token, ip) VALUES (?, ?)");
            $stmt->execute([$tokenForum, $ip]);
        }

        $stmt = $DBH->prepare("SELECT * FROM anonymous_message WHERE token=? AND ip=?");
        $stmt->execute([$tokenForum, $ip]);
        $resultII = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($resultII) {

            $html = file_get_contents(__DIR__ . "/../html/anonymous-empty.html");
            $html = str_replace("{RESPONSE}", "Kamu telah mengirim balasan ke forum ini!!!", $html);
        } else {


            $name = strlen($result['name']) > 0 ?  $result['name'] : "Anonymouse";
            $message = $result['message'];

            $html = file_get_contents(__DIR__ . "/../html/anonymous-reply.html");

            $html = str_replace("{NAME}", $name, $html);
            $html = str_replace("{MESSAGE}", $message, $html);
            $html = str_replace("{CURRENT_URL}", $actual_link, $html);
            $html = str_replace("{SITE_URL}", "https://" . $_SERVER['SERVER_NAME'], $html);
        }



        if (isset($_POST['message'])) {

            $message = filter_input(INPUT_POST, "message", FILTER_DEFAULT);

            $stmt = $DBH->prepare("INSERT INTO anonymous_message (token, message, date, ip) VALUES (?, ?, ?, ?)");
            $stmt->execute([$tokenForum, $message, date("Y-m-d H:i:s"), $ip]);

            header("Location: $actual_link");
        }
    }
}
