<?php
/**
 * Fetch social posts
 * 
 * @param PDO $pdo
 * @param int $limit
 * @return array
 */
function getSocialPosts(PDO $pdo, $limit = 20)
{
    $sql = "
        SELECT id, platform, tone, topic, post_text, image_url, created_at
        FROM social_posts
        ORDER BY id DESC
        LIMIT :limit
    ";

    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
    $stmt->execute();

    return $stmt->fetchAll();
}
