<?php

class ImageService
{
    private string $apiKey;

    public function __construct(string $apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function generate(string $prompt): ?string
    {
        $ch = curl_init("https://api.stability.ai/v2beta/stable-image/generate/ultra");
        curl_setopt_array($ch, [
            CURLOPT_POST => true,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTPHEADER => [
                "Authorization: Bearer {$this->apiKey}"
            ],
            CURLOPT_POSTFIELDS => [
                "prompt" => $prompt,
                "output_format" => "png"
            ]
        ]);

        $result = curl_exec($ch);
        curl_close($ch);

        $data = json_decode($result, true);

        if (empty($data['image'])) {
            return null;
        }

        $path = "generated/" . time() . ".png";
        file_put_contents($path, base64_decode($data['image']));

        return $path;
    }
}
