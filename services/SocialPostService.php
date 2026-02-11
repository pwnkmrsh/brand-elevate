<?php

class SocialPostService
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function save(
        string $platform,
        string $tone,
        string $topic,
        string $text,
        ?string $image
    ): int {
        $sql = "INSERT INTO social_posts
                (platform, tone, topic, post_text, image_url)
                VALUES (:platform, :tone, :topic, :post_text, :image_url)";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            ':platform'  => $platform,
            ':tone'      => $tone,
            ':topic'     => $topic,
            ':post_text' => $text,
            ':image_url' => $image
        ]);

        return (int)$this->pdo->lastInsertId();
    }
}
