<?php

namespace App\Controller;

use App\Entity\Measure;
use App\Repository\MeasureRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class MeasureController extends AbstractController
{
    // Ajouter une mesure
    #[Route('/api/measurements', name: 'api_measurement_create', methods: ['POST'])]
    public function create(
        Request $request,
        EntityManagerInterface $entityManager
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        if (!is_array($data)) {
            return $this->json([
                'error' => 'Le JSON envoyé est invalide.'
            ], 400);
        }

        if (!isset($data['light']) || !isset($data['distance']) || !isset($data['panel_open']) ) {
            return $this->json([
                'error' => 'Les champs "light", "distance" et "panel_open" sont obligatoires.'
            ], 400);
        }

        if (!is_numeric($data['light']) || !is_numeric($data['distance'])) {
            return $this->json([
                'error' => 'Les champs "light" et "distance" doivent être numériques.'
            ], 400);
        }

        $measurement = new Measure();

        $measurement->setLight((int) $data['light']);
        $measurement->setDistance((float) $data['distance']);
        $measurement->setPanelOpen((bool) $data['panel_open']);

        $entityManager->persist($measurement);
        $entityManager->flush();

        return $this->json([
            'message' => 'Mesure enregistrée avec succès.',
            'measurement' => [
                'id' => $measurement->getId(),
                'light' => $measurement->getLight(),
                'distance' => $measurement->getDistance(),
                'createdAt' => $measurement->getCreatedAt()?->format(DATE_ATOM),
                'panel_open' => $measurement->getPanelOpen(),
            ]
        ], 201);
    }


    // Liste des mesures
    #[Route('/api/measurements', name: 'api_measurement_list', methods: ['GET'])]
    public function list(
        MeasureRepository $measurementRepository
    ): JsonResponse {
        $measurements = $measurementRepository->findBy(
            [],
            ['createdAt' => 'DESC']
        );

        $data = [];

        foreach ($measurements as $measurement) {
            $data[] = [
                'id' => $measurement->getId(),
                'light' => $measurement->getLight(),
                'distance' => $measurement->getDistance(),
                'panel_open' => $measurement->getPanelOpen(),
                'createdAt' => $measurement->getCreatedAt()?->format(DATE_ATOM),
            ];
        }

        return $this->json($data);
    }
}