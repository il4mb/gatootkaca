<?php

require_once __ENCH__;

use Module\ENCH;

$tabPhotoProfile = "<i class=\"fa-regular fa-circle-user fa-2xl\"></i>";
$tabMenu = "<li><a href=\"/auth/masuk\"><i class=\"fa-solid fa-person-walking-dashed-line-arrow-right\"></i> Masuk</a></li>\n<li><a href=\"/auth/daftar\"><i class=\"fa-solid fa-people-line\"></i> Daftar</a></li>";



if(isset($_COOKIE['G_auth'])) {

    $data = $_COOKIE['G_auth'];

    $data = json_decode(base64_decode(ENCH::decrypt($data)), true);

    if(strlen($data['photo']) > 0) {
        $tabPhotoProfile = "<img src=\"".$data['photo']."\"/>";
    }

    $tabMenu = "<li><a href=\"/@". $data['username'] ."\"><i class=\"fa-regular fa-user\"></i>  " . $data['username'] . "</a></li>\n<li><a class='text-danger' href='/auth/logout'>Keluar <i class=\"fa-solid fa-person-walking\"></i></a></li>";

}





$listText = [
    "Nasi Padang",
    "Ciki Sihotang",
    "Ciki Anak Mama",
    "Cari Sesuatu?",
    "Om Jangan Om",
    "Kominfo Wah"
];

$random = $listText[array_rand($listText)];








$document->html = str_replace('{RANDOM_PLACEHOLDER}', $random, $document->html);
$document->html = str_replace('{PHOTO_PROFILE}', $tabPhotoProfile, $document->html);
$document->html = str_replace('{TAB_MENU}', $tabMenu, $document->html);