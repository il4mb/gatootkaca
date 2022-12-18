<?php
date_default_timezone_set('Asia/Jakarta');

require_once $root . "/module/define.php";
require_once __ENCH__;

use Module\ENCH;

if (isset($_COOKIE['user'])) {

    $ench = new ENCH($_COOKIE['user']);
    $user = (array)$ench->decrypt();

    if (!array_key_exists('name', $user) || !array_key_exists('id', $user) || !array_key_exists('email', $user) || !array_key_exists('password', $user)) {
        header("Location: /master/login");
    }
} else {
    header("Location: /master/login");
}
?>
<!DOCTYPE html>
<html lang="id">

<head>
    <title>Webmaster Gatootkaca.com</title>

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="/master/css/master-core.css" />
    <link rel="stylesheet" href="/Bootstrap/css/bootstrap.min.css">
</head>

<body>

    <div class="container-fluid">

        <nav class="navbar navbar-light bg-light">
            <button id="sidebarToggler" class="btn">
                <svg xmlns="http://www.w3.org/2000/svg" width="25" height="25" fill="currentColor" class="bi bi-list" viewBox="0 0 16 16">
                    <path fill-rule="evenodd" d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z" />
                </svg>
            </button>
            <span class="navbar-brand">Maxima group</span>
        </nav>

        <div id="sidebar" class="sidebar closed">
            <div class="d-flex flex-column flex-shrink-0 p-3 bg-light">
                <a href="/">
                    Gatootkaca.com
                </a>
                <hr>
                <ul class="nav nav-pills flex-column mb-auto">
                    <li class="user-badge">
                        <span class="font-large"><?php echo strtolower($user['name']) ?></span>
                    </li>
                    <?php
                    $listKeys = array_keys($recognize);

                    foreach ($listKeys as $val) {

                        if (0 == strcmp('hr', $val)) {
                            echo "<hr/>";
                        } else {

                            $classlist = "nav-link hover-red";
                            $link = "/master" . $recognize[$val]["path"] . "/";
                            $name = $recognize[$val]['name'];

                            if ($this->path[1] == preg_replace('/[^a-z0-9-_]/', '', $recognize[$val]["path"])) {
                                $classlist .= " active";
                            }

                            echo "<li>\n<a class='$classlist' href='$link'>$name</a>\n</li>";
                        }
                    }
                    ?>
                    <li>
                        <a href="/master/logout/" class="nav-link text-danger mt-5">
                            <i class="fa-solid fa-person-walking-dashed-line-arrow-right" style="transform: scale(-1, 1)"></i> Log out
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="container-wrapper">
            <?php
            if (array_key_exists('2', $this->path)) {

                $file = dirname(__FILE__) . "/" . $this->path[1] . "/" . $this->path[2] . ".php";

                if (is_file($file)) {

                    require_once $file;
                } else {

                    echo " document tidak ditemukan";
                }
            } else if (array_key_exists('1', $this->path)) {

                if (array_key_exists($this->path[1], $recognize) && is_file(dirname(__FILE__) . $recognize[$this->path[1]]['path'] . ".php")) {

                    require_once dirname(__FILE__) . $recognize[$this->path[1]]['path'] . ".php";
                } else {

                    echo "document tidak ditemukan!.";
                }
            } else echo "document tidak ditemukan!.";
            ?>
        </div>

    </div>
    <footer>
        <script type="text/javaScript" src="/master/js/master-core.js"></script>

        <script type="text/javascript" src="/js/jquery-3.6.1.min.js"></script>
        <script type="text/javascript" rel="preload" src="/Bootstrap/js/bootstrap.min.js"></script>
    </footer>
</body>

</html>