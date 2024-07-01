<?php

namespace Repositories;

use PDO;
use PDOException;

class ParkingRepository extends Repository
{
    public function findAvailableSpot()
    {
        try {
            $stmt = $this->connection->prepare("SELECT id, spot_number FROM parking_spots WHERE is_occupied = FALSE LIMIT 1");
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            error_log("Failed to find available parking spot: " . $e->getMessage());
            return null;
        }
    }

    public function allocateSpot($spotId, $vehicleId)
    {
        try {
            $stmt = $this->connection->prepare("UPDATE parking_spots SET is_occupied = TRUE, vehicle_id = ? WHERE id = ? AND is_occupied = FALSE");
            return $stmt->execute([$vehicleId, $spotId]);
        } catch (PDOException $e) {
            error_log("Failed to allocate parking spot: " . $e->getMessage());
            return false;
        }
    }

    public function removeVehicleFromSpot($spotNumber)
    {
        try {
            $stmt = $this->connection->prepare("UPDATE parking_spots SET is_occupied = FALSE, vehicle_id = NULL WHERE spot_number = ?");
            return $stmt->execute([$spotNumber]);
        } catch (PDOException $e) {
            error_log("Failed to remove vehicle from spot: " . $e->getMessage());
            return false;
        }
    }

    public function removeVehicleFromSpotById($vehicleId) {
        try {
            $stmt = $this->connection->prepare("UPDATE parking_spots SET is_occupied = FALSE, vehicle_id = NULL WHERE vehicle_id = ?");
            $stmt->execute([$vehicleId]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Failed to free parking spot: " . $e->getMessage());
            return false;
        }
    }
    
    public function insertSpot($spotNumber)
    {
        try {
            $stmt = $this->connection->prepare("INSERT INTO parking_spots (spot_number, is_occupied) VALUES (?, FALSE)");
            $stmt->execute([$spotNumber]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Failed to insert parking spot: " . $e->getMessage());
            return false;
        }
    }

    public function updateSpot($spotId, $spotNumber)
    {
        try {
            $stmt = $this->connection->prepare("UPDATE parking_spots SET spot_number = ? WHERE id = ?");
            $stmt->execute([$spotNumber, $spotId]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Failed to update parking spot: " . $e->getMessage());
            return false;
        }
    }

    public function deleteSpot($spotId)
    {
        try {
            $stmt = $this->connection->prepare("DELETE FROM parking_spots WHERE id = ?");
            $stmt->execute([$spotId]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Failed to delete parking spot: " . $e->getMessage());
            return false;
        }
    }

    public function getAllSpots() {
        try {
            $stmt = $this->connection->prepare("SELECT * FROM parking_spots");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Failed to fetch parking spots: " . $e->getMessage());
            return false;
        }
    }
}
