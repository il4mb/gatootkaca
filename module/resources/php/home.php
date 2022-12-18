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

 VM::$html = str_replace('{RANDOM_PLACEHOLDER}', $random, VM::$html);