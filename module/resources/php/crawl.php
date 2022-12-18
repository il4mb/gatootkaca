<?php
/*
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
ini_set('display_startup_errors', TRUE);
*/
set_time_limit(9999999);
date_default_timezone_set('Asia/Jakarta');

/**
 * @var string $url
 */
require_once $_SERVER['DOCUMENT_ROOT'] . "/module/define.php";
require_once __SUBMITSITE__;
require_once __CRAWL_SCHEDULE__;
require_once __CRAWLER__;
require_once __DATABASE__;
require_once __TOOLS__;

$crawler = new CRAWLER();
$crawler->url = $url;
$crawler->sameorigin = false;
$crawler->database = $DBH;
$crawler->limit = 100;
$crawler->init();

$result = $crawler->start(3);

if ($result != 'null' && is_array($result) && array_key_exists('0', $result)) {

    $total = count($result);
    $response = '';
    $icon = "";
    $host = "";
    $scheme = "";
    $status = "";
    $title = "";
    $description = "";

    $result = $result[0];

    if (array_key_exists('url', $result)) {


        $url = $result['url'];
        $response = '200';
        $icon = filter_var($result['icon'], FILTER_VALIDATE_URL) ? tools::imageToBase64($result['icon']) : $result['icon'];
        $host = "Tidak di ketahui";
        $scheme = "http";
        $status = "Belum terdaftar";

        $title = $result['title'];
        $description = $result['description'];

        $parse = parse_url($result['url']);

        if (array_key_exists('host', $parse)) {

            $host = $parse['host'];


            $SQL = "SELECT host FROM masterpage WHERE host=?";
            $stmt = $DBH->prepare($SQL);
            $stmt->execute([$host]);
            if ($stmt->fetch(PDO::FETCH_ASSOC)) {

                $status = "Website ini telah di crawl oleh Gatootkaca";
            }
        }

        if (array_key_exists('scheme', $parse)) {

            $scheme = $parse['scheme'];
        }

        $result = [
            "G_type" => "G_A",
            "response" => $response,
            "host" => $host,
            "icon" => $icon,
            "scheme" => $scheme,
            "status" => $status,
            "title" => $title,
            "description" => $description,
            "total" => $total." halaman crawled"
        ];

        print json_encode($result, JSON_UNESCAPED_UNICODE, 165);

        /**
         * ADD SCHEDULE
         */

        $schedule = new crawlScheduleManage();
        $schedule->addschedule($url);


        $submit = new submitSite([
            "host" => $host,
            "url" => $url,
            "title" => $title,
            "description" => $description
        ]);
        $submit->database = $DBH;
        $submit->init();

        exit;
    }
}

echo "url tidak dapat di crawl oleh pak Gatoot";
