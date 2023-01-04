<?php

use Module\ENCH;

require_once __DATABASE__;
require_once __ENCH__;


$username = filter_input(INPUT_POST, "username", FILTER_UNSAFE_RAW);
$password = $_POST['password'];

$stmt = $DB->prepare("SELECT * FROM users WHERE username=? OR email=?");
$stmt->execute([ $username, $username ]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);


if($result) {

    if(password_verify($password, $result['password'])) {

        $cookie_value = ENCH::encrypt(base64_encode(json_encode($result)));

        setcookie("G_auth", $cookie_value, time() + 2629746, "/"); // 86400 = 1 day

        header("Location: /");

    } else {

        $response = "<span class='text-warning'>Kata sandi salah</span>";
    }

} else {

    $response = "<span class='text-warning'>Akun tidak di temukan</span>";
}