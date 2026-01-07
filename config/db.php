<?php
$host = "localhost";
$user = "root";
$pass = "";
$db   = "brand_ele";
/* $user = "nukkadne_pawan";
$pass = "b%@vyq-xBQcd";
$db   = "nukkadne_n360in"; */

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
    ]);
} catch (Exception $e) {
    die("DB Connection Failed: " . $e->getMessage());
}
