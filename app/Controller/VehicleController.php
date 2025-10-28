<?php

namespace App\Controller;

use Domain\Service\VehicleDTO;
use Domain\Service\VehiclesBuilder;
use Domain\Service\VehiclesReader;
use Domain\Service\VehiclesWriter;
use Persistence\Repository\VehicleRepository;
use Symfony\Component\HttpFoundation\{JsonResponse, Request, Response};

class VehicleController extends BaseController
{
    public function index(): Response
    {
        ob_start();
        include __DIR__ . '/../views/index.php';
        return (new Response(ob_get_clean()))->send();
    }

    public function list(): JsonResponse
    {
        $results = (new VehiclesBuilder(new VehicleRepository()))->getList();

        return $this->toJsonResponse(['results' => $results]);
    }

    public function save(?int $id, Request $request): JsonResponse
    {
        try {
            $content = json_decode($request->getContent(), true);
            $vehicleDTO = new VehicleDTO(
                $content['id'],
                strtoupper($content['registrationNumber'] ?? ''),
                $content['brand'] ?? '',
                $content['model'] ?? '',
                $content['type'] ?? '',
                $content['createdAt'] ?? time(),
                time()
            );
            return $this->toJsonResponse([(new VehiclesWriter(new VehicleRepository()))->saveVehicle($vehicleDTO)]);
        } catch (\InvalidArgumentException $e) {
            return $this->toJsonResponse(['error' => $e->getMessage()], 400);
        } catch (\PDOException $e) {
            return $this->toJsonResponse(['error' => 'Database error'], 500);
        } catch (\Throwable $e) {
            return $this->toJsonResponse(['error' => 'An error occurred'], 500);
        }
    }

    public function delete(int $id): JsonResponse
    {
        $success = (new VehiclesWriter(new VehicleRepository()))->deleteById($id);
        return $this->toJsonResponse([$success]);
    }

    public function getById(int $id): JsonResponse
    {
        return $this->toJsonResponse([(new VehiclesReader(new VehicleRepository()))->getVehicleById($id)]);
    }
}
