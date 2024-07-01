<?php

namespace Repositories;

use PDO;
use PDOException;

class PaymentRepository extends Repository
{
    public function recordPayment($vehicleId, $userId, $amount)
    {
        try {
            $stmt = $this->connection->prepare("INSERT INTO payments (vehicle_id, user_id, amount, payment_time) VALUES (?, ?, ?, NOW())");
            $stmt->execute([$vehicleId, $userId, $amount]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Failed to record payment: " . $e->getMessage());
            return false;
        }
    }

    public function getAllPayments()
    {
        try {
            $stmt = $this->connection->prepare("
                SELECT 
                    payments.id,
                    users.firstname,
                    users.lastname,
                    vehicles.license_plate,
                    vehicles.vehicle_type,
                    payments.amount,
                    payments.payment_time,
                    payments.isPaid
                FROM 
                    payments
                JOIN 
                    users ON payments.user_id = users.id
                JOIN 
                    vehicles ON payments.vehicle_id = vehicles.id
                ORDER BY 
                    payments.payment_time DESC
            ");
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Failed to fetch payments: " . $e->getMessage());
            return false;
        }
    }

    public function getAllPaymentsByUser($userId)
    {
        try {
            $stmt = $this->connection->prepare("
                SELECT 
                    payments.id,
                    users.firstname,
                    users.lastname,
                    vehicles.license_plate,
                    vehicles.vehicle_type,
                    payments.amount,
                    payments.payment_time,
                    payments.isPaid
                FROM 
                    payments
                JOIN 
                    users ON payments.user_id = users.id
                JOIN 
                    vehicles ON payments.vehicle_id = vehicles.id
                WHERE 
                    payments.user_id = ?
                ORDER BY
                    payments.payment_time DESC
            ");
            $stmt->execute([$userId]);
            // $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Failed to fetch payments: " . $e->getMessage());
            return false;
        }
    }

    public function processPayment($paymentId)
    {
        try {
            $stmt = $this->connection->prepare("UPDATE payments SET isPaid = 1 WHERE id = ?");
            $stmt->execute([$paymentId]);
            return $stmt->rowCount() > 0;
        } catch (PDOException $e) {
            error_log("Failed to process payment: " . $e->getMessage());
            return false;
        }
    }
}
