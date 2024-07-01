<?php

namespace Services;

use DateTime;
use Repositories\ParkingRepository;
use Repositories\VehicleRepository;

class VehicleService
{
    private $paymentService;
    private $vehicleRepository;
    private $parkingRepository;

    public function __construct()
    {
        $this->paymentService = new PaymentService();
        $this->vehicleRepository = new VehicleRepository();
        $this->parkingRepository = new ParkingRepository();
    }

    public function register($vehicle)
    {
        return $this->vehicleRepository->insert($vehicle);
    }

    public function checkInVehicle($vehicleId)
    {
        if ($this->vehicleRepository->isVehicleCheckedIn($vehicleId)) {
            return false;
        }
        return $this->vehicleRepository->updateCheckInStatus($vehicleId, true);
    }

    public function checkOutVehicle($vehicleId)
    {
        if ($this->vehicleRepository->isVehicleCheckedOut($vehicleId)) {
            return false;
        }

        $vehicle = $this->vehicleRepository->getVehicleById($vehicleId);
        $checkInTime = new DateTime($vehicle['check_in_time']);
        $checkOutTime = new DateTime('now');
        $interval = $checkInTime->diff($checkOutTime);
        $seconds = $interval->days * 24 * 60 * 60 + $interval->h * 60 * 60 + $interval->i * 60 + $interval->s;

        $userId = $vehicle['user_id'];

        $this->paymentService->createBill($vehicleId, $userId, $seconds);

        $this->parkingRepository->removeVehicleFromSpotById($vehicleId);
        return $this->vehicleRepository->updateCheckInStatus($vehicleId, false);
    }

    public function getVehicleById($vehicleId)
    {
        return $this->vehicleRepository->getVehicleById($vehicleId);
    }

    public function getVehiclesByUserId($userId)
    {
        return $this->vehicleRepository->getVehiclesByUserId($userId);
    }

    public function getAllVehicles() {
        return $this->vehicleRepository->getAllVehicles();
    }
}
