<?php
header("Content-Type: application/json");

// -------------------------------
// Validate Input
// -------------------------------
$platform = $_POST['platform'] ?? '';
$topic = $_POST['topic'] ?? '';

if (empty($platform) || empty($topic)) {
    echo json_encode(['error' => 'Please fill all fields!']);
    exit;
}

// -------------------------------
// Platform-specific instructions
// -------------------------------
$instruction = "";

if ($platform == "facebook") {
    $instruction = "Write an engaging Facebook post. Use simple tone, emojis, short paragraphs, and CTA with hashtags.";
}

if ($platform == "linkedin") {
    $instruction = "Write a professional LinkedIn post. Use formal tone, value-based content, and add 3 relevant hashtags at the end.";
}

// -------------------------------
// ChatGPT API Request
// -------------------------------
$apiKey = " JQAOO5UAFacA";

$url = "https://api.openai.com/v1/chat/completions";
$data = [
    "model" => "gpt-4o-mini",    // fast + cheap + excellent
    "messages" => [
        ["role" => "system", "content" => "You are a social media content expert."],
        ["role" => "user", "content" => "$instruction\nTopic: $topic"]
    ],
    "temperature" => 0.7
];

$payload = json_encode($data);

$ch = curl_init();

curl_setopt_array($ch, [
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => $payload,
    CURLOPT_HTTPHEADER => [
        "Content-Type: application/json",
        "Authorization: Bearer $apiKey"
    ],
    // ☑ Allow insecure for localhost
    CURLOPT_SSL_VERIFYHOST => 0,
    CURLOPT_SSL_VERIFYPEER => 0,
]);

$response = curl_exec($ch);

if (curl_errno($ch)) {
    echo json_encode(['error' => 'Curl Error: ' . curl_error($ch)]);
    exit;
}

curl_close($ch);

// -------------------------------
// Parse ChatGPT Response
// -------------------------------
$res = json_decode($response, true);
var_dump($res); exit;

if (isset($res["choices"][0]["message"]["content"])) {
    $text = $res["choices"][0]["message"]["content"];

    echo json_encode([
        "generated_text" => nl2br($text)
    ]);
} else {
    echo json_encode([
        "error" => "Failed to generate post. API issue."
    ]);
}
