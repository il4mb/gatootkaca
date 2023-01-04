<?php

require_once __DATABASE__;

$username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_FULL_SPECIAL_CHARS);
$email = filter_input(INPUT_POST, "email", FILTER_VALIDATE_EMAIL);
$password = $_POST['password'];


if (str_contains($username, 'admin')) {

    $response = "<span class='text-danger'>Nama penguna mengandung kata illegal</span>";

    $username = null;
    $email = null;
    $password = null;
}


if (!empty($password) && !empty($username) && !empty($email)) {


    $stmt = $DB->prepare("SELECT username FROM users WHERE username=?");
    $stmt->execute([$username]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if (! $result) {

        $stmt = $DB->prepare("INSERT INTO users (username, email, password) VALUES (?, ?, ?)");
        $result = $stmt->execute([$username, $email, password_hash($password, PASSWORD_DEFAULT)]);

        if ($result) {

            echo
            "<div style=\"position: fixed;
                   width: 100%;
                   height: 100%;
                   z-index: 999;
                   background: rgb(0 0 0 /.95);\">

            <div style=\"position: fixed;
                        top: 50%;
                        left: 50%;
                        transform: translate(-50%, -50%);
                        width: 100%;
                        max-width: max-content;
                        text-align: center;
                        z-index: 999;
                        background: white;
                        padding: 15px;
                        border-radius: 8px;
                        box-shadow: -1px 1px 4px rgb(0 0 0 / 50%);\">

                  <p>Akun berhasil di buat<br/><a href='/auth/login'> Masuk </a></p>

            </div>

         </div>";

        }
    }
}
