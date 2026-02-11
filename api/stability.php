<?php
require_once __DIR__ . '../../config/config.php'; 

$apiKey = STABILITY_API_KEY;
$prompt = "Professional social media graphic for Digital India initiative with Robot";

$ch = curl_init("https://api.stability.ai/v2beta/stable-image/generate/ultra");

curl_setopt_array($ch, [
    CURLOPT_POST => true,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_HTTPHEADER => [
        "Authorization: Bearer $apiKey",
        "Accept: application/json"  
    ],
    CURLOPT_POSTFIELDS => [
        "prompt" => $prompt,
        "output_format" => "png"
    ],
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_SSL_VERIFYHOST => 0
]);

$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);

if (!empty($data['image'])) {
    $file = "../generated/" . time() . ".png";
    file_put_contents($file, base64_decode($data['image']));
    echo "Image saved: $file";
} else {
    echo "Image generation failed";
    echo "<pre>";
    print_r($data);
}

