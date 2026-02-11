<?php

header("Content-Type: application/json");

// Inputs
$p = $_POST['platform'];
$tone = $_POST['tone'];
$topic = $_POST['topic'];
$words = $_POST['words'] ?? 150;
/* $hashtags = $_POST['hashtags'];
$imageNeed = $_POST['image']; */

// OpenAI API Key
$apiKey = "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";

// Platform instructions
$instructions = [
    "facebook" => "Short, engaging, friendly, use emojis, no hashtags.",
    "linkedin" => "Professional, value-driven, industry tone, add 3–5 hashtags.",
    "instagram" => "Creative, emotional, storytelling, add trendy hashtags.",
    "twitter" => "Short, sharp, bold, max 240 chars, 3 hashtags.",
    "youtube" => "Community post style, simple, engaging, 2 hashtags."
];

$prompt = "Platform: $p
Tone: $tone
Word Limit: $words words
Topic: $topic
Style: {$instructions[$p]}
Generate a perfect post that fits the platform rules.";

// ------------------------------------
// ChatGPT Text Generation
// ------------------------------------
$url = "https://api.openai.com/v1/chat/completions";

$data = [
    "model" => "gpt-4o-mini",
    "messages" => [
        ["role" => "system", "content" => "You are a social media content expert."],
        ["role" => "user", "content" => $prompt]
    ],
    "temperature" => 0.7
];

$headers = [
    "Content-Type: application/json",
    "Authorization: Bearer $apiKey"
];

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

$response = curl_exec($ch);
curl_close($ch);

$res = json_decode($response, true);

var_dump($res); exit;

$post = $res["choices"][0]["message"]["content"] ?? null;
if (!$post) {
    echo json_encode(["error" => "API returned no result"]);
    exit;
}

// ------------------------------------
// Optional Image Generation
// ------------------------------------
$imageUrl = null;
if ($imageNeed == "yes") {
    $imagePrompt = "Create a high-quality social media image for: $topic";

    $imgData = [
        "model" => "gpt-image-1",
        "prompt" => $imagePrompt,
        "size" => "1024x1024"
    ];

    $ch = curl_init("https://api.openai.com/v1/images/generations");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($imgData));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

    $imageResp = curl_exec($ch);

    if (curl_errno($ch)) {
        apiError("Image API cURL Error", curl_error($ch));
    }

    $httpStatus = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($httpStatus >= 400) {
        apiError("Image API returned HTTP error: $httpStatus", $imageResp);
    }

    $img = json_decode($imageResp, true);
    if (!$img) {
        apiError("Invalid JSON from Image API", $imageResp);
    }

    if (isset($img["error"])) {
        apiError("Image Generation Error: " . $img["error"]["message"], $img);
    }

    if (!isset($img["data"][0]["url"])) {
        apiError("Image URL not found in API response", $img);
    }

    $imageUrl = $img["data"][0]["url"];
}
