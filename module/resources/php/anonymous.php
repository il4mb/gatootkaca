<?php

use Module\ENCH;

date_default_timezone_set("Asia/Jakarta");

require_once __DATABASE__;
require_once __OPENBINARY__;
require_once __ENCH__;

/**
 * @var String $html
 */

if (array_key_exists(1, $this->path)) {

    if ($this->path[1] == "create") {

        $html = file_get_contents(__DIR__ . "/../html/anonymous-create.html");
    } else if ($this->path[1] == "forum") {

        if (
            array_key_exists('HTTP_REQUESTED_WITH', $_SERVER)
            && strlen($_SERVER['HTTP_REQUESTED_WITH'])
            && strtolower($_SERVER['HTTP_REQUESTED_WITH']) == strtolower("xhr")
        ) {
            require_once __DIR__ . "/anonymous-api.php";
            exit;
        }


        if (array_key_exists('token', $_COOKIE) && $_COOKIE['token']) {

            $token = $_COOKIE['token'];

            $stmt = $DBH->prepare("SELECT * FROM anonymous_forum WHERE token=?");
            $stmt->execute([$token]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            $html = file_get_contents(__DIR__ . "/../html/anonymous-forum.html");

            $token = ENCH::encrypt($token);
            $link = "https://" . $_SERVER['SERVER_NAME'] . "/anonymous/reply/" . base64_encode($token);

            $stop_date = date('Y-m-d H:i', strtotime($result['date'] . ' +1 day'));

            $html = str_replace("{TOKEN}", "TOKEN : <span class=\"text-warning\">" . $_COOKIE['token'] . "</span>", $html);
            $html = str_replace("{URL}", $link, $html);
            $html = str_replace("{NAMA}", $result['name'], $html);
            $html = str_replace("{MESSAGE}", $result['message'], $html);
            $html = str_replace("{ACTIVE_UTIL}", $stop_date, $html);
        } else {

            $html = file_get_contents(__DIR__ . "/../html/anonymous-enter.html");

            if (isset($_POST['token'])) {

                //$html = file_get_contents(__DIR__ . "/../html/anonymous-forum.html");

                $token = filter_input(INPUT_POST, 'token', FILTER_DEFAULT);
                $stmt = $DBH->prepare("SELECT * FROM anonymous_forum WHERE token=?");
                $stmt->execute([$token]);
                $result = $stmt->fetch(PDO::FETCH_ASSOC);

                if ($result) {

                    setcookie("token", $token, time() + (86400 * 30), "/"); // 86400 = 1 day
                    header("Location: /anonymous/forum/");
                } else {

                    $html = str_replace("{RESPONSE}", "<div class='response-text text-center text-danger'>Token tidak ditemukan!!!</div>",  $html);
                }
            }

            $html = str_replace("{RESPONSE}", "",  $html);
        }
    } else if ($this->path[1] == "reply") {

        require_once __DIR__ . "/anonymous-reply.php";
    }

    $document->html = $html;
}



if (isset($_POST['name'], $_POST['message'])) {

    $name = filter_input(INPUT_POST, 'name', FILTER_UNSAFE_RAW);
    $message = filter_input(INPUT_POST, 'message', FILTER_UNSAFE_RAW);

    if (strlen($message) > 0 ) {

        $date = date('dHis');
        $dateChar = unpack('C*', $date);

        $token = [];
        foreach ($dateChar as $char) {

            array_push($token, ($char + 23));
        }
        $token = pack('C*', ...$token);


        $stmt = $DBH->prepare("INSERT INTO anonymous_forum (token, name, message, date) VALUES (?, ?, ?, ?)");
        if ($stmt->execute([$token, $name, $message, date("Y-m-d H:i:s")])) {

            setcookie("token", $token, time() + (86400 * 30), "/"); // 86400 = 1 day
            header("Location: /anonymous/forum/");
        }
    }
}
