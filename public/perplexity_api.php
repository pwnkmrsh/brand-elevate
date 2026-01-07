<?php
require_once __DIR__ . '../../config/config.php';
header('Content-Type: application/json');
 $platform       = $_POST['platform'] ?? '';
$tone           = $_POST['tone'] ?? '';
$topic          = $_POST['topic'] ?? '';
$generate_image = $_POST['generate_image'] ?? 'no';

if (!$platform || !$tone || !$topic) {
    echo json_encode(['error' => 'Missing required fields']);
    exit;
}

/* ===============================
   PLATFORM PROMPTS
================================ */
$templates = [
    "facebook"  => "Write in Facebook style...",
    "linkedin"  => "Professional LinkedIn style...",
    "instagram" => "Creative Instagram caption...",
    "twitter"   => "Short Twitter/X style...",
    "youtube"   => "YouTube community post..."
];

$template = $templates[$platform] ?? '';

$main_prompt = "
Generate a {$platform} social media post.

Topic: {$topic}
Tone: {$tone}

Platform rules:
{$template}

Return only the post text.
";

/* ===============================
   1) TEXT GENERATION (PERPLEXITY)
================================ */
$api_key = PERPLEXITY_API_KEY;

$text_payload = [
    "model" => "sonar",
    "messages" => [
        ["role" => "user", "content" => $main_prompt]
    ]
];

$ch = curl_init("https://api.perplexity.ai/chat/completions");
curl_setopt_array($ch, [
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

$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);
$post_text = $data['choices'][0]['message']['content'] ?? '';

if (!$post_text) {
    echo json_encode(["error" => "Text generation failed"]);
    exit;
}

/* ===============================
   2) OPTIONAL IMAGE GENERATION
================================ */
$image_url = null;

if ($generate_image === 'yes') {

    $stability_key = STABILITY_API_KEY;
    $prompt = "Professional social media graphic for: {$topic}";

    $img = curl_init("https://api.stability.ai/v2beta/stable-image/generate/ultra");
    curl_setopt_array($img, [
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

    $img_result = curl_exec($img);
    curl_close($img);

    $img_data = json_decode($img_result, true);

    if (!empty($img_data['image'])) {
        $filename = "generated/" . time() . ".png";
        file_put_contents($filename, base64_decode($img_data['image']));
        $image_url = $filename;
    }
}

/* ===============================
   3) SAVE TO DB (FUNCTION)
================================ */
function saveSocialPost(PDO $pdo, $platform, $tone, $topic, $post_text, $image_url)
{
    $sql = "
        INSERT INTO social_posts
        (platform, tone, topic, post_text, image_url)
        VALUES (:platform, :tone, :topic, :post_text, :image_url)
    ";

    $stmt = $pdo->prepare($sql);

    $stmt->execute([
        ':platform'  => $platform,
        ':tone'      => $tone,
        ':topic'     => $topic,
        ':post_text' => $post_text,
        ':image_url' => $image_url
    ]);

    return $pdo->lastInsertId();
}

$post_id = saveSocialPost(
    $pdo,
    $platform,
    $tone,
    $topic,
    $post_text,
    $image_url
);

/* ===============================
   FINAL RESPONSE
================================ */
echo json_encode([
    "status" => "success",
    "post_id" => $post_id,
    "generated_text" => $post_text,
    "image_url" => $image_url
]);
exit;
