<?php

namespace Domain\Entity;

class Vehicle
{
    private int $id;
    private string $registrationNumber;
    private string $brand;
    private string $model;
    private string $type;
    private int $createdAt;
    private int $updatedAt;

    public function getId(): int
    {
        return $this->id;
    }

    public function setId(int $id): Vehicle
    {
        $this->id = $id;
        return $this;
    }

    public function getRegistrationNumber(): string
    {
        return $this->registrationNumber;
    }

    public function setRegistrationNumber($registrationNumber): Vehicle
    {
        $this->registrationNumber = $registrationNumber;
        return $this;
    }

    public function getBrand(): string
    {
        return $this->brand;
    }

    public function setBrand($brand): Vehicle
    {
        $this->brand = $brand;
        return $this;
    }

    public function getModel(): string
    {
        return $this->model;
    }

    public function setModel($model): Vehicle
    {
        $this->model = $model;
        return $this;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function setType($type): Vehicle
    {
        $this->type = $type;
        return $this;
    }

    public function getCreatedAt(): int
    {
        return $this->createdAt;
    }

    public function setCreatedAt($createdAt): Vehicle
    {
        $this->createdAt = $createdAt;
        return $this;
    }

    public function getUpdatedAt(): int
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt($updatedAt): Vehicle
    {
        $this->updatedAt = $updatedAt;
        return $this;
    }
}
