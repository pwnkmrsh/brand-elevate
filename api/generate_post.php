<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../services/ChatGPTService.php';
require_once __DIR__ . '/../services/ImageService.php';
require_once __DIR__ . '/../services/SocialPostService.php';

header('Content-Type: application/json');

$platform = $_POST['platform'] ?? '';
$tone     = $_POST['tone'] ?? '';
$topic    = $_POST['topic'] ?? '';
$imgFlag  = $_POST['generate_image'] ?? 'no';

if (!$platform || !$tone || !$topic) {
    echo json_encode(['error' => 'Missing required fields']);
    exit;
}

/* Platform templates */
$templates = [
    "facebook"  => "Write a Facebook post with emojis and CTA.",
    "linkedin"  => "Professional LinkedIn style post.",
    "instagram" => "Creative Instagram caption.",
    "twitter"   => "Short Twitter/X post.",
    "youtube"   => "YouTube community post."
];

$prompt = "
Generate a {$platform} social media post.

Topic: {$topic}
Tone: {$tone}

Rules:
{$templates[$platform]}

Return only post text.
";

/* Services */
$chat = new ChatGPTService(OPENAI_API_KEY);
$imageService = new ImageService(STABILITY_API_KEY);
$postService  = new SocialPostService($pdo);

/* Generate text */
$text = $chat->generate($prompt);
var_dump($text); exit;

if (!$text) {
    echo json_encode(['error' => 'Text generation failed']);
    exit;
}

/* Optional image */
$image = null;
if ($imgFlag === 'yes') {
    $image = $imageService->generate("Social media graphic for {$topic}");
}

/* Save */
$postId = $postService->save($platform, $tone, $topic, $text, $image);

/* Response */
echo json_encode([
    "status" => "success",
    "post_id" => $postId,
    "generated_text" => $text,
    "image_url" => $image
]);
