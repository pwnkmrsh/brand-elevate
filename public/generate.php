<?php
// generate.php
require_once __DIR__ . '../../config/config.php';

header('Content-Type: application/json; charset=utf-8');

function jsonError($msg, $details = null, $http_code = 200) {
    http_response_code($http_code);
    echo json_encode(['error' => $msg, 'details' => $details]);
    exit;
}

// simple validation
$platform = trim($_POST['platform'] ?? '');
$tone     = trim($_POST['tone'] ?? '');
$topic    = trim($_POST['topic'] ?? '');

if (!$platform || !$topic) {
    jsonError("Platform and topic are required.");
}

// build system prompt & user prompt
$system = "You are a social media content expert. Produce concise, platform-appropriate posts.";
$userPrompt = "Platform: {$platform}\nTone: {$tone}\nTopic: {$topic}\n\nWrite an optimized social media post suitable for the platform. Output only the post text.";

// payload for OpenAI-compatible Gemini endpoint
$payload = [
    'model' => GEMINI_MODEL,
    'messages' => [
        ['role' => 'system', 'content' => $system],
        ['role' => 'user', 'content' => $userPrompt],
    ],
    'temperature' => GEMINI_TEMPERATURE,
    'max_tokens' => GEMINI_MAX_TOKENS,
];

// prepare curl
$ch = curl_init();
$url = GEMINI_OPENAI_ENDPOINT;

// If you need to use an API key in query string instead of bearer, modify $url to add ?key=YOUR_KEY
$headers = [
    'Content-Type: application/json',
    // For most Google Cloud setups you must use an OAuth bearer token (set GEMINI_API_KEY to that token)
    // If you have a simple API key and Google expects it as ?key=API_KEY, then remove this header and append ?key=API_KEY to $url
    'Authorization: Bearer ' . GEMINI_API_KEY,
];


curl_setopt_array($ch, [
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => json_encode($payload),
    CURLOPT_HTTPHEADER => $headers,
    CURLOPT_TIMEOUT => 45,
    // For localhost only — remove on production
    CURLOPT_SSL_VERIFYHOST => 0,
    CURLOPT_SSL_VERIFYPEER => 0,
]);

$response = curl_exec($ch);
$curlErr = curl_error($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch); 

if ($curlErr) {
    jsonError("cURL error: " . $curlErr, null, 500);
}

if ($httpCode >= 400) {
    // try to decode error JSON
    $decoded = json_decode($response, true);
    // Return API error details for debugging
    jsonError("API returned HTTP error: " . $httpCode, $decoded, $httpCode);
}

$decoded = json_decode($response, true);
if ($decoded === null) {
    jsonError("Invalid JSON from API", $response, 500);
}

// OpenAI-compatible error structure
if (isset($decoded['error'])) {
    jsonError("API error", $decoded['error'], $httpCode ?: 500);
}

// Extract message (OpenAI-compatible shape)
$generated = null;
if (isset($decoded['choices'][0]['message']['content'])) {
    $generated = $decoded['choices'][0]['message']['content'];
} elseif (isset($decoded['choices'][0]['text'])) {
    // fallback older/completion-like responses
    $generated = $decoded['choices'][0]['text'];
} else {
    // If the response shape differs, return whole raw struct for inspection
    jsonError("No generated text found in API response", $decoded, 500);
}

// Success: return HTML-safe string and raw response for debug
echo json_encode([
    'generated_text' => nl2br(htmlspecialchars($generated)),
    'raw' => $decoded
]);
exit;
