<?php
namespace Domain\Service;

use Domain\Entity\Vehicle;

trait VehicleMapper
{
    private function entityToDTO(Vehicle $vehicle): VehicleDTO
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

    private function dtoToEntity(VehicleDTO $vehicleDTO): Vehicle
    {
        $registrationNumber = trim($vehicleDTO->registrationNumber);
        if (empty($registrationNumber)) {
            throw new \InvalidArgumentException("Registration number cannot be empty.");
        }

        $brand = trim($vehicleDTO->brand);
        if (empty($brand)) {
            throw new \InvalidArgumentException("Brand cannot be empty.");
        }

        $model = trim($vehicleDTO->model);
        if (empty($model)) {
            throw new \InvalidArgumentException("Model cannot be empty.");
        }

        $type = trim($vehicleDTO->type);
        if (!in_array($type, ['Passenger', 'Bus', 'Truck'])) {
            throw new \InvalidArgumentException("Invalid vehicle type.");
        }

        $createdAt = $vehicleDTO->createdAt <= 0 ? time() : $vehicleDTO->createdAt;
        $updatedAt = $vehicleDTO->updatedAt <= 0 ? time() : $vehicleDTO->updatedAt;

        return (new Vehicle())
            ->setId((int) $vehicleDTO->id)
            ->setRegistrationNumber(strtoupper(
                htmlspecialchars($registrationNumber, ENT_QUOTES, 'UTF-8')
            ))
            ->setBrand(htmlspecialchars($brand, ENT_QUOTES, 'UTF-8'))
            ->setModel(htmlspecialchars($model, ENT_QUOTES, 'UTF-8'))
            ->setType(htmlspecialchars($type, ENT_QUOTES, 'UTF-8'))
            ->setCreatedAt($createdAt)
            ->setUpdatedAt($updatedAt);
    }
}

