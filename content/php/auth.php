<?php

header("HTTP/1.1 200 OK");

if (array_key_exists(1, $this->path) && $this->path[1] == "logout" ) {

    if (isset($_COOKIE['G_auth'])) {
        
        unset($_COOKIE['G_auth']); 
        setcookie('G_auth', null, -1, '/'); 
       
    } 

    header("Location: /");
}



$html = file_get_contents(__DIR__."/../html/auth/login.html");

if(array_key_exists(1, $this->path) && $this->path[1] == "daftar") {

    $html = file_get_contents(__DIR__."/../html/auth/register.html");

}




$response = "";

if(isset($_POST['username'], $_POST['password'], $_POST['email'])) {

    require_once __DIR__."/API/register.php";
    
} elseif (isset($_POST['username'], $_POST['password'])) {

    require_once __DIR__."/API/login.php";

} elseif ( isset($_POST['np'])) {

    require_once __DIR__."/API/check-user-name.php";
    exit;
}



$html = str_replace("{RESPONSE}", $response, $html);

$document->html = $html;