<?php
require_once $_SERVER['DOCUMENT_ROOT']."/core/handler/api-connection.php";

$API = new APIconnection();

$SQL = "SELECT * FROM contents ORDER BY id DESC";
$API->prepare($SQL, "all");

$result = $API->execute();

$result = json_decode($result, true);

$DOMList = "";
foreach($result AS $content) {

    $thumbnail = '//maxima-group.co.id'.$content['thumbnail'];
    $description = $content["description"];
    if (strlen($description) > 150) {
        $description = substr($description, 0, 144) . " [...]";
    }

    $DOMList .= "<div class='col mb-3 ms-auto me-auto grid-support'>
                    <div class='card' animation='from-bottom'>
                        <img loading='lazy' src='$thumbnail' class='card-img-top thubnail' alt='...'>
                        <div class='card-body'>
                        <h5 class='card-title'>".$content['title']."</h5>
                        <p class='card-text'>".$description."</p>
                        </div>
                    </div>
                </div>";
}

$document = str_replace("[KONTENT]", $DOMList, $document);