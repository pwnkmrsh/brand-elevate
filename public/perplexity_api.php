<?php
require_once __DIR__ . '../../config/config.php';
header('Content-Type: application/json');

// Inputs
$platform = $_POST['platform'] ?? '';
$tone = $_POST['tone'] ?? '';
$topic = $_POST['topic'] ?? '';
$generate_image = $_POST['generate_image'] ?? 'no';

if (!$platform || !$tone || !$topic) {
    echo json_encode(['error' => 'Missing required fields']);
    exit;
}

/* Platform templates (same as before) */
$templates = [
    "facebook" => "Write in Facebook style...",
    "linkedin" => "Professional LinkedIn style...",
    "instagram" => "Creative Instagram caption...",
    "twitter" => "Short Twitter/X style...",
    "youtube" => "YouTube community post..."
];

$template = $templates[$platform];

// Create main text prompt
$main_prompt = "Generate a {$platform} social media post. Topic: {$topic} Tone: {$tone} Platform-specific rules: {$template} Return only the post text.";

// #### API KEY ####
$api_key = PERPLEXITY_API_KEY;

// ====== 1) TEXT GENERATION ======
$text_payload = [
    "model" => "sonar",
    "messages" => [
        ["role" => "user", "content" => $main_prompt]
    ]
];

$text_ch = curl_init("https://api.perplexity.ai/chat/completions");
curl_setopt_array($text_ch, [
    CURLOPT_POST => true,
    CURLOPT_POSTFIELDS => json_encode($text_payload),
    CURLOPT_HTTPHEADER => [
        "Content-Type: application/json",
        "Authorization: Bearer $api_key"
    ],
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_SSL_VERIFYHOST => 0
]);

$text_response = curl_exec($text_ch);
curl_close($text_ch);

$text_data = json_decode($text_response, true);
$post_text = $text_data['choices'][0]['message']['content'] ?? '';

// ====== 2) OPTIONAL IMAGE GENERATION ======
 $image_url = null;

if ($generate_image === "yes") {

    $stability_key = $api_key;

    $prompt = "Create a clean professional social media graphic based on: {$topic}";

    $img_ch = curl_init();

    curl_setopt_array($img_ch, [
        CURLOPT_URL => "https://api.stability.ai/v2beta/stable-image/generate/ultra",
        CURLOPT_POST => true,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            "Authorization: Bearer $stability_key"
        ],
        CURLOPT_POSTFIELDS => [
            "prompt" => $prompt,
            "output_format" => "png"
        ],
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_SSL_VERIFYHOST => 0
    ]);

    $img_result = curl_exec($img_ch);
    curl_close($img_ch);

    if ($img_result) {
        $img_data = json_decode($img_result, true);

        if (isset($img_data["image"])) {
            $base64 = $img_data["image"];

            // Save locally
            $filename = "generated/" . time() . ".png";
            file_put_contents($filename, base64_decode($base64));

            // Public URL
            $image_url = $filename;
        }
    }
}


$generated_text = $data['choices'][0]['message']['content'] ?? '';
$search_results = $data['search_results'][0] ?? [];  // ADD THIS

echo json_encode([
    'generated_text' => $generated_text,
    'search_results' => $search_results,
    'raw' => $data,
    'image_url' => $image_url ?? null
]);
exit;
