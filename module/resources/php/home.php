<?php

use Module\Classes\VM;

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
