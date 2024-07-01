<?php

namespace Services;

use Repositories\ParkingRepository;

class ParkingService
{
    private $parkingRepository;

    public function __construct()
    {
        $this->parkingRepository = new ParkingRepository();
    }

    public function findAvailableSpot()
    {
        return $this->parkingRepository->findAvailableSpot();
    }

    public function allocateSpot($spotId, $vehicleId)
    {
        if (!$this->parkingRepository->allocateSpot($spotId, $vehicleId)) {
            throw new \Exception("Allocation failed, the spot might already be occupied or an error occurred.");
        }
        return true;
    }

    public function removeVehicleFromSpot($spotNumber)
    {
        if (!$this->parkingRepository->removeVehicleFromSpot($spotNumber)) {
            throw new \Exception("Failed to remove the vehicle, the spot might be empty or an error occurred.");
        }
        return true;
    }

    public function addSpot($spotNumber)
    {
        if (!$this->parkingRepository->insertSpot($spotNumber)) {
            throw new \Exception("Could not add the parking spot.");
        }
        return true;
    }

    public function updateSpot($spotId, $spotNumber)
    {
        if (!$this->parkingRepository->updateSpot($spotId, $spotNumber)) {
            throw new \Exception("Could not update the parking spot.");
        }
        return true;
    }

    public function deleteSpot($spotId)
    {
        if (!$this->parkingRepository->deleteSpot($spotId)) {
            throw new \Exception("Could not delete the parking spot.");
        }
        return true;
    }
    
    public function removeVehicleFromSpotById($vehicleId)
    {
        if (!$this->parkingRepository->removeVehicleFromSpotById($vehicleId)) {
            throw new \Exception("Could not delete the parking spot.");
        }
        return true;
    }

    public function getAllSpots() {
        return $this->parkingRepository->getAllSpots();
    }
}
