<?php

$apiKey = "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";

$url = "https://api.openai.com/v1/chat/completions";

$data = [
    "model" => "gpt-4o-mini",
    "messages" => [
        ["role" => "user", "content" => "Hello from localhost!"]
    ]
];

$headers = [
    "Content-Type: application/json",
    "Authorization: Bearer $apiKey"
];

$ch = curl_init($url);

curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// 🔥 Ignore SSL for local development
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo 'Error: ' . curl_error($ch);
}

curl_close($ch);

echo "<pre>";
print_r(json_decode($response, true));
echo "</pre>";
?>
