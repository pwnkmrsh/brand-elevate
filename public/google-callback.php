<?php
require "../config/db.php";
require "../config/google/google-config.php";

if (!isset($_GET['code'])) {
    die("Unauthorized Access");
}

$token = $client->fetchAccessTokenWithAuthCode($_GET['code']);

$client->setAccessToken($token);
$googleUser = $client->verifyIdToken();

$email = $googleUser['email'];
$name  = $googleUser['name'];
$image = $googleUser['picture'];

$stmt = $pdo->prepare("SELECT id FROM users WHERE email=? LIMIT 1");
$stmt->execute([$email]);

if ($stmt->rowCount() == 0) {
    //    $ins = $pdo->prepare("INSERT INTO users (name,email,image,email_verified_at,created_at) VALUES (?,?,?,?,NOW())");
    //    $ins->execute([$name, $email, $image, date("Y-m-d H:i:s")]);
    //    Insert new Google user
    $stmt = $pdo->prepare("INSERT INTO users (name, email, image, created_at) VALUES (?, ?, ?, NOW())");
    $stmt->execute([$name, $email, $picture]);
}


// After insert, fetch new user
$user_id = $pdo->lastInsertId();

$stmt = $pdo->prepare("SELECT id, name, image FROM users WHERE email = ? LIMIT 1");
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

print_r($user);
if ($user['id']) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['name']    = $user['name'];
    $_SESSION['image']   = $user['image'];
}



header("Location: dashboard.php");
exit;
