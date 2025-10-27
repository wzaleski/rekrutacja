<?php

namespace Domain\Service;

use Domain\Entity\Vehicle;
use Domain\Repository\VehicleRepositoryInterface;

class VehiclesBuilder
{
    public function __construct(private VehicleRepositoryInterface $vehicleRepository)
    {
    }

    public function getList()
    {
        $items = $this->vehicleRepository->getList();

        return array_map([$this, 'entityToDTO'], $items);
    }

    private function entityToDTO(Vehicle $vehicle)
    {
        return new VehicleDTO(
            $vehicle->getId(),
            $vehicle->getRegistrationNumber(),
            $vehicle->getBrand(),
            $vehicle->getModel(),
            $vehicle->getType(),
            $vehicle->getCreatedAt(),
            $vehicle->getUpdatedAt()
        );
    }
}
