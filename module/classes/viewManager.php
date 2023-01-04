<?php
namespace Module\Classes;

class VM {
    public $menu = [], $currentPosition = [], $head, $body, $html = "";

    private $path;

    public function __construct($menu) {

        $this->menu = $menu;

    }

    public function setCurrentPosition($path = "") {

        $this->path = $path;

        foreach($this->menu AS $item) {
                    
            if( array_key_exists('path', $item) && 0 == strcmp($path, preg_replace('/[^a-z0-9-_]+/', '', $item['path']))) {
                $this->currentPosition = $item;
            }

        }

    }

    public function getDocument() {

        global $structure;
        

        $this->html = "<body><h1 class='text-center' style='margin-top: 15%'>Document halaman tidak ditemukan</h1></body>";

        if( array_key_exists('html', $this->currentPosition) && is_file($_SERVER['DOCUMENT_ROOT']. "/".$structure[0]."/html".$this->currentPosition['html']) && file_exists($_SERVER['DOCUMENT_ROOT']. "/".$structure[0]."/html".$this->currentPosition['html'])) {

            $this->html = file_get_contents($_SERVER['DOCUMENT_ROOT']. "/".$structure[0]."/html".$this->currentPosition['html']);

        } else {

            header("HTTP/1.0 404 Not Found");
            
        }


        return $this->html;

    }

    public function getPHPSupport() {

        global $structure;


        if( array_key_exists('php', $this->currentPosition) && is_file( $_SERVER['DOCUMENT_ROOT']. "/".$structure[0]."/php".$this->currentPosition['php']) ) {

            return $_SERVER['DOCUMENT_ROOT']. "/".$structure[0]."/php".$this->currentPosition['php'];


        } else if (is_file( $_SERVER['DOCUMENT_ROOT']. "/".$structure[0]."/php/".$this->path.".php")) {

            return  $_SERVER['DOCUMENT_ROOT']. "/".$structure[0]."/php/".$this->path.".php";


        } else return false;


    }

    public function init()
    {

        if (preg_match('/(?:<head[^>]*>)(.*)<\/head>/isU', $this->html, $match)) {

            $this->head = $match[1];
            $this->html = str_replace($this->head, "", $this->html);

        }

        if (preg_match('/(?:<body[^>]*>)(.*)<\/body>/isU', $this->html, $match)) {
    
            $this->body = $match[1];
            
        }

    }
}