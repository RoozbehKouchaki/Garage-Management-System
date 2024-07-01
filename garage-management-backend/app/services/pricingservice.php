<?php

namespace Services;

use PDO;
use Repositories\Repository;

class PricingService extends Repository {

    public function getHourlyRate() {
        $stmt = $this->connection->prepare("SELECT rate FROM rates WHERE description = 'Per Hour Parking Rate'");
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function getRatePerSecond() {
        $stmt = $this->connection->prepare("SELECT rate FROM rates WHERE description = 'Per Second Parking Rate'");
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    public function calculateCharge($seconds) {
        //$hourlyRate = $this->getHourlyRate();
        $secondlyRate = $this->getRatePerSecond();
        return $secondlyRate * $seconds;
    }
}
