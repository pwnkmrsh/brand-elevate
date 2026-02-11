<?php session_start();
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
$picture = $googleUser['picture'];

$stmt = $pdo->prepare("SELECT id FROM users WHERE email=? LIMIT 1");
$stmt->execute([$email]);

if ($stmt->rowCount() == 0) {
    $stmt = $pdo->prepare("INSERT INTO users (name, email, image, created_at) VALUES (?, ?, ?, NOW())");
    $stmt->execute([$name, $email, $picture]);
}

// After insert, fetch new user
$user_id = $pdo->lastInsertId();

$stmt = $pdo->prepare("SELECT `id`, `name`, `email`, `image` FROM users WHERE email = ? LIMIT 1");
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
if ($user['id']) {
    echo "Welcome";
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['name']    = $user['name'];
    $_SESSION['image']   = $user['image'];
    $_SESSION['user_email']   = $email;
    $_SESSION['email']   =  $user['email'];
}

echo "<script> location.href='dashboard.php'; </script>";
exit;
