<?php

if (array_key_exists(1, $this->path))
    if ($this->path[1] == "create")
        $document->html = file_get_contents(__DIR__ . "/../html/anonymous-create.html");

    else if ($this->path[1] == "forum")
        $document->html = file_get_contents(__DIR__ . "/../html/anonymous-forum.html");