<?php
$db_host = 'localhost';
$db_user = 'root';
$db_pass = '';
$db = 'agrokajak';

$conn = mysqli_connect($db_host, $db_user, $db_pass, $db);

if (mysqli_connect_error()) {
    echo mysqli_connect_error();
}

$user = 'root';
$pass = '1234';
?>