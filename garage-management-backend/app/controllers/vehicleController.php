<?php

namespace Controllers;

use Models\Vehicle;
use Services\VehicleService;
use Utils\JwtHandler;

class VehicleController extends Controller
{
    private $vehicleService;
    private $jwtHandler;

    public function __construct()
    {
        $this->vehicleService = new VehicleService();
        $this->jwtHandler = new JwtHandler();
    }

    public function checkIn()
    {
        if (!$this->checkAuthorization()) {
            $this->respondWithError(403, "Unauthorized action.");
            return;
        }

        $data = json_decode(file_get_contents("php://input"), true);
        if (!isset($data['vehicle_id'])) {
            $this->respondWithError(400, "Vehicle ID is required.");
            return;
        }

        $vehicleId = $data['vehicle_id'];

        $vehicle = $this->vehicleService->checkInVehicle($vehicleId);
        if ($vehicle) {
            $this->respond(['message' => 'Vehicle checked in successfully.', 'data' => $vehicle]);
        } elseif ($vehicle === false) {
            $this->respond(['message' => 'Vehicle is already checked in.', 'data' => $this->vehicleService->getVehicleById($vehicleId)]);
        } else {
            $this->respondWithError(500, 'Failed to check in vehicle.');
        }
    }

    public function checkOut()
    {
        if (!$this->checkAuthorization()) {
            $this->respondWithError(403, "Unauthorized action.");
            return;
        }

        $data = json_decode(file_get_contents("php://input"), true);
        if (!isset($data['vehicle_id'])) {
            $this->respondWithError(400, "Vehicle ID is required.");
            return;
        }

        $vehicleId = $data['vehicle_id'];

        $vehicle = $this->vehicleService->checkOutVehicle($vehicleId);
        if ($vehicle) {
            $this->respond(['message' => 'Vehicle checked out successfully.', 'data' => $vehicle]);
        } elseif ($vehicle === false) {
            $this->respond(['message' => 'Vehicle is already checked out.', 'data' => $this->vehicleService->getVehicleById($vehicleId)]);
        } else {
            $this->respondWithError(500, 'Failed to check out vehicle.');
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

    public function register()
    {
        $token = $_SERVER['HTTP_AUTHORIZATION'] ?? '';

        if (preg_match('/Bearer\s(\S+)/', $token, $matches)) {
            $token = $matches[1];
        } else {
            $this->respondWithError(401, "Bearer token not found in request");
            return;
        }

        $decoded = $this->jwtHandler->jwtDecodeData($token);

        if (!$decoded['status']) {
            $this->respondWithError(401, 'Invalid or expired token');
            return;
        }

        $userId = $decoded['data']->id;

        $vehicle = $this->createObjectFromPostedJson('Models\\Vehicle');
        if (!$vehicle || empty($vehicle->license_plate) || empty($vehicle->vehicle_type)) {
            $this->respondWithError(400, "Missing required vehicle information.");
            return;
        }

        $vehicle->user_id = $userId;
        $vehicle_id = $this->vehicleService->register($vehicle);

        if ($vehicle_id) {
            $vehicle->id = $vehicle_id;
            $this->respond(['message' => 'Vehicle registration successful', 'data' => $vehicle]);
        } else {
            $this->respondWithError(400, "You already have a registered vehicle.");
        }
    }

    public function getVehiclesByUserId()
    {
        if (!$this->checkAuthorization()) {
            $this->respondWithError(403, "Unauthorized action.");
            return;
        }

        $userId = $this->checkAuthorization();

        $vehicles = $this->vehicleService->getVehiclesByUserId($userId);
        if ($vehicles) {
            $this->respond(['message' => 'Vehicles fetched successfully.', 'data' => $vehicles]);
        } else {
            $this->respondWithError(500, 'Failed to fetch vehicles.');
        }
    }

    public function getAllVehicles()
    {
        if (!$this->checkAuthorization()) {
            $this->respondWithError(403, "Unauthorized action.");
            return;
        }

        $vehicles = $this->vehicleService->getAllVehicles();
        if ($vehicles) {
            $this->respond(['message' => 'All vehicles fetched successfully.', 'data' => $vehicles]);
        } else {
            $this->respondWithError(500, 'Failed to fetch vehicles.');
        }
    }
}
