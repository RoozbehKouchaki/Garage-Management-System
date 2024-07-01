<?php

namespace Controllers;

use DateTime;
use Services\PricingService;
use Services\PaymentService;
use Services\VehicleService;
use Utils\JwtHandler;

class PaymentController extends Controller
{
    private $pricingService;
    private $paymentService;
    private $vehicleService;
    private $jwtHandler;

    public function __construct()
    {
        $this->pricingService = new PricingService();
        $this->paymentService = new PaymentService();
        $this->vehicleService = new VehicleService();
        $this->jwtHandler = new JwtHandler();
    }

    public function processPayment()
    {
        if (!$this->checkAuthorization()) {
            $this->respondWithError(403, "Unauthorized action.");
            return;
        }

        $data = json_decode(file_get_contents("php://input"), true);
        if (!isset($data['paymentId'])) {
            $this->respondWithError(400, "Payment ID is required.");
            return;
        }

        $paymentId = $data['paymentId'];
        $result = $this->paymentService->processPayment($paymentId);
        if ($result) {
            $this->respond(['message' => 'Payment processed successfully.']);
        } else {
            $this->respondWithError(500, 'Failed to process payment.');
        }
    }

    private function checkAuthorization()
    {
        $token = $_SERVER['HTTP_AUTHORIZATION'] ?? '';
        if (!preg_match('/Bearer\s(\S+)/', $token, $matches)) {
            $this->respondWithError(401, "No token provided.");
            return false;
        }

        $decoded = $this->jwtHandler->jwtDecodeData($matches[1]);
        if (!$decoded['status']) {
            $this->respondWithError(401, "Unauthorized or invalid token.");
            return false;
        }

        return $decoded['data']->id;
    }

    public function getPaymentInfo()
    {
        $userId = $this->checkAuthorization();
        if (!$userId) {
            $this->respondWithError(403, "Unauthorized action.");
            return;
        }

        $data = json_decode(file_get_contents("php://input"), true);
        if (!isset($data['vehicle_id'])) {
            $this->respondWithError(400, "Vehicle ID is required.");
            return;
        }

        $vehicleId = $data['vehicle_id'];

        $vehicle = $this->vehicleService->getVehicleById($vehicleId);
        if (!$vehicle) {
            $this->respondWithError(404, "No vehicle found for the user.");
            return;
        }

        if ($vehicle['check_out_time'] != null) {

            $checkInTime = new DateTime($vehicle['check_in_time']);
            $checkOutTime = new DateTime($vehicle['check_out_time'] ?? 'now');
            $interval = $checkInTime->diff($checkOutTime);

            $amount = $this->pricingService->calculateCharge($interval);

            $this->respond(['message' => 'Payment info fetched successfully.', 'amount' => $amount]);
        } else {
            $this->respond(['message' => 'Payment info fetched successfully.', 'amount' => 0]);
        }
    }

    public function getAllPayments()
    {
        $userId = $this->checkAuthorization();
        if (!$userId) {
            $this->respondWithError(403, "Unauthorized action.");
            return;
        }

        $payments = $this->paymentService->getAllPayments();
        if ($payments) {
            $this->respond(['message' => 'All payments fetched successfully.', 'data' => $payments]);
        } else {
            $this->respondWithError(500, 'Failed to fetch payments.');
        }
    }

    public function getAllPaymentsByUser()
    {
        $userId = $this->checkAuthorization();
        if (!$userId) {
            $this->respondWithError(403, "Unauthorized action.");
            return;
        }

        $payments = $this->paymentService->getAllPaymentsByUser($userId);
        if ($payments) {
            $this->respond(['message' => 'All payments fetched successfully.', 'data' => $payments]);
        } else {
            $this->respondWithError(500, 'Failed to fetch payments.');
        }
    }
}
