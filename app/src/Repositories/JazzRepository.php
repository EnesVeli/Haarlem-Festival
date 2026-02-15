<?php

namespace App\Repositories;

use App\Framework\Repository;
use PDO;

class JazzRepository extends Repository
{
    public function getActiveExperiences(): array
    {
        $sql = "
            SELECT id, title, description
            FROM jazz_experiences
            WHERE is_active = 1
            ORDER BY sort_order ASC, id ASC
        ";

        $stmt = $this->connection->prepare($sql);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getActivePerformers(): array
    {
     $sql = "
         SELECT id, name, bio
         FROM jazz_performers
         WHERE is_active = 1
        ORDER BY sort_order ASC, id ASC
     "; 

     $stmt = $this->connection->prepare($sql);
     $stmt->execute();

     return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}