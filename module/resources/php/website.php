<?php
if(isset($_POST['url'])) {

    set_time_limit(999999999);
    
    $url = filter_var($_POST['url'], FILTER_VALIDATE_URL);
    if($url) {

        require_once dirname(__FILE__)."/crawl.php";

        exit;
    }

    echo "url tidak valid, pariksa kembali url";
    exit;
}