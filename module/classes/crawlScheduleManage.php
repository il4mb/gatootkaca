<?php
class crawlScheduleManage {

    public $filename, $savepath;

     function __construct()
     {
        $this->filename = date("Y-m-d") . ".json";

        $this->filename = date("Y-m-d") . ".json";
        $root_directory = $_SERVER['DOCUMENT_ROOT'] . "/module";

        if (!file_exists($root_directory . "/schedule")) {
            mkdir($root_directory . "/schedule");
        }
        if (!file_exists($root_directory . "/schedule/crawl")) {
            mkdir($root_directory . "/schedule/crawl");
        }

        $this->savepath = $root_directory . "/schedule/crawl";
        if (!file_exists($this->savepath . "/" . $this->filename)) {

            $stream = fopen($this->savepath . "/" . $this->filename, 'w');
            fwrite($stream, json_encode([]));
            fclose($stream);
        }
     }

     public function addschedule($url) {

        $text = file_get_contents($this->savepath . "/" . $this->filename);
        $dataJSon = json_decode($text, true);
        $url_column = array_column($dataJSon, 'url');

        $found_key = array_filter($url_column, function ($_url) use ($url) {
            return 0 == strcmp($_url, $url);
        });

        if (! $found_key || count($dataJSon) <= 0) {

            $dataJSon[count($dataJSon)] = [
                "url" => $url,
                "insert" => date("Y-m-d H:i:s")
            ];
            
            $stream = fopen($this->savepath . "/" . $this->filename, 'w');
            fwrite($stream, json_encode($dataJSon));
            fclose($stream);
        }
     }
}