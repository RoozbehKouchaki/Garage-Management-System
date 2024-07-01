<?php

namespace Services;

use Repositories\ParkingRepository;
use Repositories\PaymentRepository;

class PaymentService {
    private $paymentRepository;
    private $parkingRepository;
    private $pricingService;

    public function __construct() {
        $this->paymentRepository = new PaymentRepository();
        $this->parkingRepository = new ParkingRepository();
        $this->pricingService = new PricingService();
    }

    public function processPaymentAndFreeSpot($vehicleId, $userId, $hours) {
        $amount = $this->pricingService->calculateCharge($hours);
        if ($this->paymentRepository->recordPayment($vehicleId, $userId, $amount)) {
            $this->parkingRepository->removeVehicleFromSpotById($vehicleId);
            return true;
        }
        return false;
    }

    public function getAllPayments() {
        return $this->paymentRepository->getAllPayments();
    }
    
    public function getAllPaymentsByUser($userId) {
        return $this->paymentRepository->getAllPaymentsByUser($userId);
    }

    public function createBill($vehicleId, $userId, float $seconds)
    {
        $amount = $this->pricingService->calculateCharge($seconds);
        return $this->paymentRepository->recordPayment($vehicleId, $userId, $amount);
    }

    public function processPayment($paymentId)
    {
        return $this->paymentRepository->processPayment($paymentId);
    }
}