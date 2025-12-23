<?php   session_start();
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
/* 
Array ( [iss] => https://accounts.google.com [azp] => 594486659687-mn7l54piajhcf47kb21d6o7vjc4d7flc.apps.googleusercontent.com [aud] => 594486659687-mn7l54piajhcf47kb21d6o7vjc4d7flc.apps.googleusercontent.com [sub] => 103095015243791753070 [email] => pwnkmrsh@gmail.com [email_verified] => 1 [at_hash] => 6zzbYIovinvMlelnsdT12A [name] => Pawan Kumar [picture] => https://lh3.googleusercontent.com/a/ACg8ocKg2EcB-BtaOnSBQAiHjv8kznRDRDj18jpVTNcWi92NKDp3lcWp=s96-c [given_name] => Pawan [family_name] => Kumar [iat] => 1766407672 [exp] => 1766411272 )
     */
if ($stmt->rowCount() == 0) {
    //    $ins = $pdo->prepare("INSERT INTO users (name,email,image,email_verified_at,created_at) VALUES (?,?,?,?,NOW())");
    //    $ins->execute([$name, $email, $image, date("Y-m-d H:i:s")]);
    //    Insert new Google user
    $stmt = $pdo->prepare("INSERT INTO users (name, email, image, created_at) VALUES (?, ?, ?, NOW())");
    $stmt->execute([$name, $email, $picture]);
}


// After insert, fetch new user
$user_id = $pdo->lastInsertId();

$stmt = $pdo->prepare("SELECT `id`, `name`, `email`, `image` FROM users WHERE email = ? LIMIT 1");
$stmt->execute([$email]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);
print_r($user);

if ($user['id']) {
    echo "Welcome";
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['name']    = $user['name'];
    $_SESSION['image']   = $user['image'];
    $_SESSION['user_email']   = $email;
    $_SESSION['email']   =  $user['email'];
}

echo '<pre>';
print_r($_SESSION['user_id']);
echo '</pre>';

//exit("Done");
 
        echo "<script> location.href='dashboard.php'; </script>";
        exit;
 
 