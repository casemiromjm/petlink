<?php

declare(strict_types=1);
require_once(__DIR__ . '/users.class.php');
class Reviews {
    public int $rating;
    public ?string $comment;

    private int $id;
    private int $ad_id;
    private int $client_id;
    private string $created_at;


    public ?User $userObject;

    private function __construct(
        int $id,
        int $client_id,
        int $ad_id,
        int $rating,
        ?string $comment,
        string $created_at,
        ?User $userObject

    ) {
        $this->id = $id;
        $this->client_id = $client_id;

        $this->ad_id = $ad_id;
        $this->rating = $rating;
        $this->comment = $comment;
        $this->created_at = $created_at;
        $this->userObject = $userObject;
    }

    public function getId(): int {
        return $this->id;
    }

    public function getUser(): int {
        return $this->client_id;
    }

    public function getService(): int {
        return $this->ad_id;
    }

    public function getCreatedAt(): string {
        return $this->created_at;
    }

    public function getUserObject(): ?User {
        return $this->userObject;
    }

    public static function getById(PDO $db, int $id): ?Reviews {
        $stmt = $db->prepare("SELECT * FROM reviews WHERE review_id = :review_id");
        $stmt->execute([':review_id' => $id]);

        $review = $stmt->fetch();
        if (!$review) return null;

        return new Reviews(
            (int)$review['id'],
            (int)$review['client_id'],
            (int)$review['ad_id'],
            (int)$review['rating'],
            $review['comment'] ?? null,
            $review['created_at']
        );
    }

    static public function getByAdId(PDO $db, int $adId): array {

        $stmt = $db->prepare('
            SELECT
                r.review_id,
                r.ad_id,
                r.client_id,
                r.rating,
                r.comment,
                r.created_at AS review_created_at,
                u.user_id,
                u.username AS user_username,
                u.name AS user_name,
                u.email AS user_email,
                u.district AS user_district,
                u.phone AS user_phone,
                u.photo_id AS user_photo_id,
                u.created_at AS user_created_at
            FROM
                reviews r
            JOIN
                users u ON r.client_id = u.user_id
            WHERE
                r.ad_id = ?
            ORDER BY
                r.created_at DESC
        ');
        $stmt->execute([$adId]);

        $reviews = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

            $user = new User(
                (int)$row['user_id'],
                $row['user_name'],
                $row['user_username'],
                $row['user_email'],
                $row['user_district'],
                $row['user_phone'],
                (int)$row['user_photo_id'],
                $row['user_created_at']

            );

            $reviews[] = new Reviews(
                (int)$row['review_id'],
                (int)$row['ad_id'],
                (int)$row['user_id'],
                (int)$row['rating'],
                $row['comment'],
                $row['review_created_at'],
                $user
            );
        }
        return $reviews;
    }


    public static function getByUser(PDO $db, int $client_idId): array {
        $stmt = $db->prepare("SELECT * FROM reviews WHERE client_id = :client_id");
        $stmt->execute([':client_id' => $client_idId]);
        $reviews = [];
        while ($row = $stmt->fetch()) {
            $reviews[] = new Reviews(
                (int)$row['id'],
                (int)$row['client_id'],
                (int)$row['ad_id'],
                (int)$row['rating'],
                $row['comment'] ?? null,
                $row['created_at']
            );
        }
        return $reviews;
    }

    public static function create(
        PDO $db,
        int $client_id,
        int $ad_id,
        int $rating,
        ?string $comment
    ): ?Reviews {
        $stmt = $db->prepare("INSERT INTO reviews (client_id, ad_id, rating, comment) VALUES (:client_id, :ad_id, :rating, :comment)");
        $result = $stmt->execute([
            ':client_id'    => $client_id,
            ':ad_id' => $ad_id,
            ':rating'  => $rating,
            ':comment' => $comment
        ]);

        if (!$result) return null;

        $newId = (int)$db->lastInsertId();
        return self::getById($db, $newId);
    }

 function update(PDO $db): bool {
        $stmt = $db->prepare("
            UPDATE reviews
            SET rating = :rating, comment = :comment
            WHERE id = :id
        ");
        return $stmt->execute([
            ':rating'  => $this->rating,
            ':comment' => $this->comment,
            ':id'      => $this->id
        ]);
    }


    public function delete(PDO $db): bool {
        $stmt = $db->prepare("DELETE FROM reviews WHERE id = :id");
        return $stmt->execute([':id' => $this->id]);
    }

    static public function getAverageRatingForAd(PDO $db, int $adId): float {
        $stmt = $db->prepare('
            SELECT AVG(rating) AS avg_rating
            FROM reviews WHERE ad_id = ?');
        $stmt->execute([$adId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (float)($result['avg_rating'] ?? 0.0);
    }

    static public function getReviewCountForAd(PDO $db, int $adId): int {
        $stmt = $db->prepare('
        SELECT COUNT(*) AS review_count
        FROM reviews WHERE ad_id = ?');
        $stmt->execute([$adId]);
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return (int)($result['review_count'] ?? 0);
    }
}
