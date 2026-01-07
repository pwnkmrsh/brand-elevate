<?php
require_once __DIR__ . '../../../vendor/autoload.php';

$client = new Google_Client();
$client->setClientId("594486659687-mn7l54piajhcf47kb21d6o7vjc4d7flc.apps.googleusercontent.com");
$client->setClientSecret("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");
//$client->setRedirectUri("http://localhost/brand-elevate/public/google-callback.php");
$client->setRedirectUri("https://brand-elevate.in/public/google-callback.php");

$client->setHttpClient(new GuzzleHttp\Client(['verify' => false]));
$client->addScope("email");
$client->addScope("profile");
