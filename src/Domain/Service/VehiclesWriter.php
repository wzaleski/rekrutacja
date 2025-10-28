<?php

namespace Domain\Service;

use Domain\Repository\VehicleRepositoryInterface;

class VehiclesWriter
{
    use VehicleMapper;
    public function __construct(private VehicleRepositoryInterface $vehicleRepository)
    {
    }

    public function saveVehicle(VehicleDTO $vehicleDTO): VehicleDTO
    {
        $vehicle = $this->dtoToEntity($vehicleDTO);
        return $this->entityToDTO($this->vehicleRepository->persist($vehicle));
    }

    public function deleteById(int $id): bool
    {
        return $this->vehicleRepository->deleteById($id);
    }
}
