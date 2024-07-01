<?php

namespace Repositories;

use PDO;
use PDOException;

class PricingRepository extends Repository {
    public function getHourlyRate() {
        try {
            $stmt = $this->connection->prepare("SELECT hourly_rate FROM rates WHERE description = 'Per Hour Parking Rate'");
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_COLUMN);
        } catch (PDOException $e) {
            error_log("Failed to fetch hourly rate: " . $e->getMessage());
            return null;
        }
    }
}