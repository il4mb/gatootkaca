<?php
$db_host = "localhost:3306";
$db_name = "doocoid_gatoot";
$u_name = "doocoid_gatootMaster";
$u_pass = "Ilham#1998";

try {
  # MySQL with PDO_MYSQL
  $DBH = new PDO("mysql:host=$db_host;dbname=$db_name", $u_name, $u_pass);
}
catch(PDOException $e) {
    echo $e->getMessage();
}