<?php
require_once __TOOLS__;
use Module\ENCH;

if (isset($_POST['kode'], $_COOKIE['token'])) {

    $token = $_COOKIE['token'];
    $kode = $_POST['kode'];

    $stmt;
    $result;

    switch ($kode) {

        case "0":
            $stmt = $DBH->prepare("SELECT * FROM anonymous_forum WHERE token=?");
            $stmt->execute([ $token ]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);

            $token = $result['token'];
            $token = ENCH::encrypt($token);
            $result['link'] = "https://".$_SERVER['SERVER_NAME'] . "/anonymous/reply/" . base64_encode($token);
            break;

        case "1":
            $stmt = $DBH->prepare("SELECT id, date, message, readed FROM anonymous_message WHERE token=? ORDER BY id DESC");
            $stmt->execute([ $token ]);
            $result = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach($result AS $key => $value) {

                $stmt = $DBH->prepare("UPDATE `anonymous_message` SET readed=? WHERE id=?");
                $stmt->execute([ 1,  $value['id'] ]);

                $result[$key]['date'] = time_elapsed_string($result[$key]['date']);
            }
            break;

        case "2" :
            $stmt = $DBH->prepare("SELECT COUNT(*) AS unread FROM anonymous_message WHERE token=? AND readed=0");
            $stmt->execute([ $token ]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            break;
    }

    print json_encode($result);
}
