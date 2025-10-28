<?php

namespace Domain\Service;

class VehicleDTO
{
    public readonly ?int $id;
    public readonly string $registrationNumber;
    public readonly string $brand;
    public readonly string $model;
    public readonly string $type;
    public readonly int $createdAt;
    public readonly int $updatedAt;

    public function __construct(
        ?int $id,
        string $registrationNumber,
        string $brand,
        string $model,
        string $type,
        int $createdAt,
        int $updatedAt
    ) {
        $this->id = $id;
        $this->registrationNumber = $registrationNumber;
        $this->brand = $brand;
        $this->model = $model;
        $this->type = $type;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }
}
