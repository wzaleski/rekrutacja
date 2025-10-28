<?php

namespace Domain\Service;

use Domain\Entity\Vehicle;
use Domain\Repository\VehicleRepositoryInterface;

class VehiclesBuilder
{
    use VehicleMapper;
    public function __construct(private VehicleRepositoryInterface $vehicleRepository)
    {
    }

    /**
     * @return VehicleDTO[]
     */
    public function getList(): array
    {
        $items = $this->vehicleRepository->getList();

        return array_map([$this, 'entityToDTO'], $items);
    }
}
