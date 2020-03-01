<?php
$host = "localhost";
$user = "root";
$password = "";
$dbname = "practice-php";

$pdo = new PDO("mysql:host=".$host.";dbname=".$dbname, $user, $password);