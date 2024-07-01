<?php

namespace Controllers;

use Exception;
use Services\ParkingService;
use Utils\JwtHandler;

class ParkingController extends Controller
{
    private $parkingService;
    private $jwtHandler;

    public function __construct()
    {
        $this->parkingService = new ParkingService();
        $this->jwtHandler = new JwtHandler();
    }

    public function getAllSpots()
    {
        try {
            $userId = $this->checkAuthorization();
            if (!$userId) return;

            $spots = $this->parkingService->getAllSpots();

            $this->respond(['message' => 'Parking spots fetched successfully.', 'data' => $spots]);
        } catch (Exception $e) {
            $this->respondWithError(500, 'Failed to fetch parking spots.');
        }
    }

    public function addParkingSpot()
    {
        $userId = $this->checkAuthorization();
        if (!$userId) return;

        $data = json_decode(file_get_contents("php://input"), true);
        if (!isset($data['spot_number'])) {
            $this->respondWithError(400, "Spot number is required.");
            return;
        }

        $result = $this->parkingService->addSpot($data['spot_number']);
        if ($result) {
            $this->respond(['message' => 'Parking spot added successfully']);
        } else {
            $this->respondWithError(500, 'Failed to add parking spot.');
        }
    }

    public function updateParkingSpot()
    {
        $userId = $this->checkAuthorization();
        if (!$userId) return;

        $spotId = $_GET['id'];

        $data = json_decode(file_get_contents("php://input"), true);
        if (!isset($data['spot_number'])) {
            $this->respondWithError(400, "Spot number is required.");
            return;
        }

        $result = $this->parkingService->updateSpot($spotId, $data['spot_number']);
        if ($result) {
            $this->respond(['message' => 'Parking spot updated successfully']);
        } else {
            $this->respondWithError(500, 'Failed to update parking spot.');
        }
    }

    public function deleteParkingSpot()
    {
        $userId = $this->checkAuthorization();
        if (!$userId) return;

        $spotId = $_GET['id'];

        $result = $this->parkingService->deleteSpot($spotId);
        if ($result) {
            $this->respond(['message' => 'Parking spot deleted successfully']);
        } else {
            $this->respondWithError(500, 'Failed to delete parking spot.');
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

    public function allocateSpot()
    {
        $userId = $this->checkAuthorization();
        if (!$userId) return;

        $json = file_get_contents('php://input');
        $data = json_decode($json);

        $vehicle_id = $data->vehicle_id ?? null;
        if (!$vehicle_id) {
            $this->respondWithError(400, "Vehicle ID is required.");
            return;
        }

        $spot = $this->parkingService->findAvailableSpot();
        if (!$spot) {
            $this->respondWithError(404, 'No available parking spots.');
            return;
        }

        $result = $this->parkingService->allocateSpot($spot->id, $vehicle_id);
        if ($result) {
            $this->respond(['message' => 'Parking spot allocated', 'spot_number' => $spot->spot_number, 'vehicle_id' => $vehicle_id]);
        } else {
            $this->respondWithError(500, 'Failed to allocate parking spot.');
        }
    }

    public function removeVehicle()
    {
        $userId = $this->checkAuthorization();
        if (!$userId) return;

        $spot_number = $_POST['spot_number'] ?? null;
        if (!$spot_number) {
            $this->respondWithError(400, "Spot number is required.");
            return;
        }

        $result = $this->parkingService->removeVehicleFromSpot($spot_number);
        if ($result) {
            $this->respond(['message' => 'Vehicle removed from spot', 'spot_number' => $spot_number]);
        } else {
            $this->respondWithError(500, 'Failed to remove vehicle.');
        }
    }
}
