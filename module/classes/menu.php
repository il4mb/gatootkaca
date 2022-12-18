<?php
namespace Module\Classes;

class Menu {
    public $menu = [], $currentPosition = [], $head, $body;
    private $html = "";

    public function __construct($menu) {

        $this->menu = $menu;

    }

    public function setCurrentPosition($path = "") {

        foreach($this->menu AS $item) {
                    
            if( array_key_exists('path', $item) && 0 == strcmp($path, preg_replace('/[^a-z0-9-_]+/', '', $item['path']))) {
                $this->currentPosition = $item;
            }

        }

    }

    public function getDocument() {

        $this->html = "<body><h1 class='text-center' style='margin-top: 15%'>Document halaman tidak ditemukan</h1></body>";

        if( array_key_exists('html', $this->currentPosition) && is_file($_SERVER['DOCUMENT_ROOT']."/module/resources/html".$this->currentPosition['html'])) {

            $this->html = file_get_contents($_SERVER['DOCUMENT_ROOT']."/module/resources/html".$this->currentPosition['html']);

        } else {

            header("HTTP/1.0 404 Not Found");
            
        }

        return $this->html;

    }

    public function getPHPSupport() {
        if( array_key_exists('php', $this->currentPosition) && is_file( $_SERVER['DOCUMENT_ROOT']."/module/resources/php".$this->currentPosition['php']) ) {

            return $_SERVER['DOCUMENT_ROOT']."/module/resources/php".$this->currentPosition['php'];

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