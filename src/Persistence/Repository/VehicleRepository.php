<?php

namespace Persistence\Repository;

use App\SQLiteConnection;
use Domain\Entity\Vehicle;
use Domain\Repository\VehicleRepositoryInterface;
use PDO;

class VehicleRepository implements VehicleRepositoryInterface
{
    private \PDO $pdo;

    public function __construct()
    {
        $this->pdo = (new SQLiteConnection())->connect();
    }

    /**
     * @return Vehicle[]
     */
    public function getList(): array
    {
        $results = $this->pdo->query('SELECT * FROM vehicles', PDO::FETCH_ASSOC);

        $items = [];
        foreach ($results as $row) {
            $items[] = $this->rowToEntity($row);
        }

        return $items;
    }

    public function getById(int $id): ?Vehicle
    {
        $stmt = $this->pdo->prepare('SELECT * FROM vehicles WHERE id = :id');
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        return $this->rowToEntity($row);
    }

    public function deleteById(int $id): bool
    {
        $stmt = $this->pdo->prepare('DELETE FROM vehicles WHERE id = :id');
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->rowCount() > 0;
    }

    public function persist(Vehicle $vehicle): Vehicle
    {
        if (empty($vehicle->getId())) {
            $stmt = $this->pdo->prepare('
                INSERT INTO vehicles (registration_number, brand, model, type, created_at, updated_at)
                VALUES (:registration_number, :brand, :model, :type, :created_at, :updated_at)
            ');
            $stmt->bindValue(':registration_number', $vehicle->getRegistrationNumber());
            $stmt->bindValue(':brand', $vehicle->getBrand());
            $stmt->bindValue(':model', $vehicle->getModel());
            $stmt->bindValue(':type', $vehicle->getType());
            $stmt->bindValue(':created_at', $vehicle->getCreatedAt());
            $stmt->bindValue(':updated_at', $vehicle->getUpdatedAt());
            $stmt->execute();
            $vehicle->setId((int) $this->pdo->lastInsertId());
        } else {
            $stmt = $this->pdo->prepare('
            UPDATE vehicles
            SET registration_number = :registration_number,
                brand = :brand,
                model = :model,
                type = :type,
                updated_at = :updated_at
            WHERE id = :id
        ');
            $stmt->bindValue(':id', $vehicle->getId());
            $stmt->bindValue(':registration_number', $vehicle->getRegistrationNumber());
            $stmt->bindValue(':brand', $vehicle->getBrand());
            $stmt->bindValue(':model', $vehicle->getModel());
            $stmt->bindValue(':type', $vehicle->getType());
            $stmt->bindValue(':updated_at', $vehicle->getUpdatedAt());
            $stmt->execute();
        }

        return $vehicle;
    }

    private function rowToEntity($row): Vehicle
    {
        return (new Vehicle())
            ->setId($row['id'])
            ->setRegistrationNumber($row['registration_number'])
            ->setBrand($row['brand'])
            ->setModel($row['model'])
            ->setType($row['type'])
            ->setCreatedAt($row['created_at'])
            ->setUpdatedAt($row['updated_at'])
        ;
    }
}
