<?php

namespace App\Repositories;

use App\Framework\Repository;
use PDO;

class JazzRepository extends Repository
{

   

    public function getPerformerById(int $id): ?array
    {
        $sql = "
            SELECT id, name, bio, image_path
            FROM jazz_performers
            WHERE id = :id AND is_active = 1
            LIMIT 1
        ";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute([':id' => $id]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row ?: null;
    }
}