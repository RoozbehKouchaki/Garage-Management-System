<?php

namespace Repositories;

use PDO;
use Models\Vehicle;
use PDOException;

class VehicleRepository extends Repository
{
    public function insert($vehicle)
    {
        $stmt = $this->connection->prepare("INSERT INTO vehicles (license_plate, vehicle_type, user_id) VALUES (?, ?, ?)");
        $stmt->execute([
            $vehicle->license_plate,
            $vehicle->vehicle_type,
            $vehicle->user_id
        ]);
        return $this->connection->lastInsertId();
    }

    public function getCheckInTime($vehicleId)
    {
        try {
            $stmt = $this->connection->prepare("SELECT check_in_time FROM vehicles WHERE id = ?");
            $stmt->execute([$vehicleId]);
            return $stmt->fetch(PDO::FETCH_OBJ)->check_in_time;
        } catch (PDOException $e) {
            error_log("Failed to fetch check-in time: " . $e->getMessage());
            return null;
        }
    }

    public function updateCheckInStatus($vehicleId, $isCheckedIn)
    {
        try {
            if ($isCheckedIn) {
                $stmt = $this->connection->prepare("UPDATE vehicles SET is_checked_in = 1, check_in_time = NOW(), check_out_time = NULL WHERE id = ?");
                $stmt->execute([$vehicleId]);
            } else {
                $stmt = $this->connection->prepare("UPDATE vehicles SET is_checked_in = 0, check_out_time = NOW() WHERE id = ?");
                $stmt->execute([$vehicleId]);
            }

            if ($stmt->rowCount() > 0) {
                return $this->getVehicleById($vehicleId);
            } else {
                error_log("No rows were updated for vehicle ID: " . $vehicleId);
                return false;
            }
        } catch (PDOException $e) {
            error_log("Failed to update vehicle check-in status: " . $e->getMessage());
            return false;
        }
    }

    public function getVehicleById($vehicleId)
    {
        try {
            $stmt = $this->connection->prepare("SELECT * FROM vehicles WHERE id = ?");
            $stmt->execute([$vehicleId]);
            return $stmt->fetch(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Failed to fetch vehicle details: " . $e->getMessage());
            return null;
        }
    }

    public function getVehiclesByUserId($userId)
    {
        try {
            $stmt = $this->connection->prepare("
            SELECT 
                v.*, 
                ps.spot_number 
            FROM 
                vehicles v 
            LEFT JOIN 
                parking_spots ps 
            ON 
                v.id = ps.vehicle_id 
            WHERE 
                v.user_id = ?
        ");
            $stmt->execute([$userId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Failed to fetch vehicles by user ID: " . $e->getMessage());
            return false;
        }
    }

    public function getVehicleByUserId($userId)
    {
        try {
            $stmt = $this->connection->prepare("SELECT * FROM vehicles WHERE user_id = ?");
            $stmt->execute([$userId]);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Failed to fetch vehicle by user ID: " . $e->getMessage());
            return null;
        }
    }

    public function isVehicleCheckedIn($vehicleId)
    {
        try {
            $stmt = $this->connection->prepare("SELECT is_checked_in FROM vehicles WHERE id = ?");
            $stmt->execute([$vehicleId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result && $result['is_checked_in'] == 1;
        } catch (PDOException $e) {
            error_log("Failed to check vehicle check-in status: " . $e->getMessage());
            return false;
        }
    }

    public function isVehicleCheckedOut($vehicleId)
    {
        try {
            $stmt = $this->connection->prepare("SELECT is_checked_in FROM vehicles WHERE id = ?");
            $stmt->execute([$vehicleId]);
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            return $result && $result['is_checked_in'] == 0;
        } catch (PDOException $e) {
            error_log("Failed to check vehicle check-in status: " . $e->getMessage());
            return false;
        }
    }

    public function getAllVehicles()
    {
        try {
            $stmt = $this->connection->prepare("
            SELECT 
                v.*, 
                ps.spot_number 
            FROM 
                vehicles v 
            LEFT JOIN 
                parking_spots ps 
            ON 
                v.id = ps.vehicle_id
        ");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Failed to fetch vehicles: " . $e->getMessage());
            return false;
        }
    }
}
