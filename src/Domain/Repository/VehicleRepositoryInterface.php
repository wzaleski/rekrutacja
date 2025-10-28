<?php

namespace Domain\Repository;

use Domain\Entity\Vehicle;

interface VehicleRepositoryInterface
{
    /**
     * @return Vehicle[]
     */
    public function getList(): array;

    public function getById(int $id);

    public function deleteById(int $id): bool;

    public function persist(Vehicle $vehicle): Vehicle;
}
