<?php

namespace Domain\Service;

use Domain\Repository\VehicleRepositoryInterface;

class VehiclesReader
{
    use VehicleMapper;
    public function __construct(private VehicleRepositoryInterface $vehicleRepository)
    {
    }

    public function getVehicleById(int $id): VehicleDTO
    {
        return $this->entityToDTO($this->vehicleRepository->getById($id));
    }
}
