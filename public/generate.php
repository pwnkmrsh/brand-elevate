<?php

// Your OpenAI API Key
$apiKey = "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";

$platform = $_POST['platform'];
$topic = $_POST['topic'];

// Platform-specific tone
$platformPrompt = [
    "facebook" => "Write a friendly, engaging Facebook post.",
    "linkedin" => "Write a professional LinkedIn post with clean formatting.",
];

$finalPrompt = $platformPrompt[$platform] . " Topic: " . $topic;

// API URL
$url = "https://api.openai.com/v1/chat/completions";

// Request payload
$data = [
    //"model" => "gpt-4o-mini",
    "model" => "gpt-4o",   // HIGHER QUALITY
    "temperature" => 0.7,  // MORE CREATIVE

    "messages" => [
        ["role" => "system", "content" => "You are an expert social media content creator. Write high-quality, engaging, polished posts."],
        ["role" => "user", "content" => $finalPrompt]
    ]
];

// Headers
$headers = [
    "Content-Type: application/json",
    "Authorization: Bearer $apiKey"
];

$ch = curl_init($url);

curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Ignore SSL (only for localhost)
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

$response = curl_exec($ch);
curl_close($ch);

// Decode response
$result = json_decode($response, true);
$postText = $result["choices"][0]["message"]["content"] ?? "No response";

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Generated Post</title>
    <style>
        body {
            font-family: Arial;
            padding: 20px;
        }

        .output {
            background: #f4f4f4;
            padding: 20px;
            border-radius: 5px;
        }
    </style>
</head>

<body>

    <h2>Your Generated <?= ucfirst($platform) ?> Post</h2>

    <div class="output">
        <pre><?php echo htmlspecialchars($postText); ?></pre>
    </div>

    <a href="index.html">⬅ Generate another post</a>

</body>

</html>