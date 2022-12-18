<?php

namespace Core;

/**
 * DI PROGRAM OLEH ILHAM B
 */

class Navigator {
    public array $path = [];

    public function __construct() {
    }

    public function setPath(string $path) : void {

        if($path) {

            $path = explode("/", $path);
            $path = array_filter($path, function($value) { return $value != null && $value != ""; });
            $this->path = $path;
            
        }

    }
    public function execute() : void {

        require_once __DIR__."/view.php";

    }
}
