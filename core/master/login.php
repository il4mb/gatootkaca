<?php

/**
 * CREATE BY ILHAM B
 * @var Navigator $this - we are in Navigator
 * @var PDO $DBH - database connection
 */

 require_once $_SERVER['DOCUMENT_ROOT']."/module/define.php";

 require_once __DATABASE__;
 require_once __ENCH__;

 use Module\ENCH;
 use Module\Classes\userModel;

class responseModel
{

    public $code;

    public $message;
    public function __construct($code, $message)
    {
        $this->code = $code;
        $this->message = $message;
    }
}

/**
 * @var responseModel $response
 */

$response = null;

if (isset($_POST['email'], $_POST['password'])) {

    $email = filter_input(INPUT_POST, 'email', FILTER_DEFAULT);
    $password = $_POST['password'];

    if ($email && $password) {

        $SQL = "SELECT * FROM users WHERE email=? AND status=1";
        $stmt = $DBH->prepare($SQL);
        $stmt->execute([$email]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($result) {

            if (password_verify($password, $result['password'])) {

                $ench = new ENCH($result);
                $encrypt = $ench->encrypt();

                setcookie("user", $encrypt, time() + (86400 * 30), "/");

                header("Location: /master/dashboard/");

            } else $response = new responseModel(1, "Kata sandi salah atau tidak sesuai!");

        } else  $response = new responseModel(0, "Kata sandi salah atau akun tidak ditemukan!");

    }
    
}
?>
<!DOCTYPE html>
<html>

<head>
    <title>Web Master Gatootkaca.com</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <script>
    </script>

    <link rel="stylesheet" href="/Bootstrap/css/bootstrap.min.css">

</head>

<body>
    <div class="container-fluid">
        <div class="container-sm pb-5" style="margin-top: 5rem;">
            <div class="m-auto" style="max-width: 480px;">
                <div class="pb-5">
                    <a href="/">
                        Gatootkaca.com
                    </a>
                </div>
                <h2 class="mb-4">Login Webmaster</h5>
                    <?php
                    if ($response != null) {
                        $classlist = " ";
                        switch ($response->code) {
                            case 0:
                                $classlist .= "alert-danger";
                                break;
                            case 1:
                                $classlist .= "alert-warning";
                                break;
                        }

                        $alert = "<div class='alert alert-dismissible fade show" . $classlist . "' role='alert'>"; // create div with classlist
                        $alert .= "<strong>Gagal !</strong> ".$response->message;

                        // add close handle
                        $alert .= "<button type='button' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>";
                        $alert .= "</div>"; // close div
                        echo $alert;
                    }
                    ?>
                    <form id="from" method=post>
                        <div class="form-group mb-3">
                            <label for="email">Email address</label>
                            <input type="text" name=email class="form-control" id="email" aria-describedby="emailHelp" placeholder="Masukan email">
                        </div>
                        <div class="form-group mb-3">
                            <label for="password">Password</label>
                            <input type="password" name=password class="form-control" id="password" placeholder="kata sandi">
                        </div>
                        <div class="d-flex mt-4">
                            <button type="submit" class="ms-auto btn btn-primary">Masuk</button>
                        </div>
                    </form>
            </div>
        </div>
    </div>
    <footer>
        <script type="text/javascript" src="/js/jquery-3.6.0.min.js"></script>
        <script type="text/javascript" rel="preload" src="/Bootstrap/js/bootstrap.min.js"></script>
    </footer>
</body>

</html>