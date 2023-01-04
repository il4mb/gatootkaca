<?php

require_once __DATABASE__;

$np = $_POST['np'];


$stmt = $DB->prepare("SELECT username FROM users WHERE username=?");
$stmt->execute([ $np ]);
$result = $stmt->fetch(PDO::FETCH_ASSOC);


echo $result ? 1 : 0;