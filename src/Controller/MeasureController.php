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
        // Transforme le corps JSON de la requête en tableau associatif exploitable.
        $data = json_decode($request->getContent(), true);

        // Refuse les requêtes dont le corps n'est pas un objet JSON valide.
        if (!is_array($data)) {
            return $this->json([
                'error' => 'Le JSON envoyé est invalide.'
            ], 400);
        }

        // Vérifie que les trois valeurs nécessaires à une mesure sont présentes.
        if (!isset($data['light']) || !isset($data['distance']) || !isset($data['panel_open']) ) {
            return $this->json([
                'error' => 'Les champs "light", "distance" et "panel_open" sont obligatoires.'
            ], 400);
        }

        // Vérifie le type numérique des valeurs fournies par les capteurs.
        if (!is_numeric($data['light']) || !is_numeric($data['distance'])) {
            return $this->json([
                'error' => 'Les champs "light" et "distance" doivent être numériques.'
            ], 400);
        }

        // Crée l'entité qui représentera la mesure en base de données.
        $measurement = new Measure();

        // Convertit les données reçues vers les types attendus par l'entité.
        $measurement->setLight((int) $data['light']);
        $measurement->setDistance((float) $data['distance']);
        $measurement->setPanelOpen((bool) $data['panel_open']);

        // Programme puis exécute l'insertion de la mesure dans la base.
        $entityManager->persist($measurement);
        $entityManager->flush();

        // Retourne la mesure créée, y compris son identifiant et sa date générés.
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
        // Récupère les mesures de la plus récente à la plus ancienne.
        $measurements = $measurementRepository->findBy(
            [],
            ['createdAt' => 'DESC']
        );

        // Prépare le tableau qui sera sérialisé en réponse JSON.
        $data = [];

        // Convertit chaque entité Doctrine en données simples destinées au client.
        foreach ($measurements as $measurement) {
            $data[] = [
                'id' => $measurement->getId(),
                'light' => $measurement->getLight(),
                'distance' => $measurement->getDistance(),
                'panel_open' => $measurement->getPanelOpen(),
                'createdAt' => $measurement->getCreatedAt()?->format(DATE_ATOM),
            ];
        }

        // Envoie la liste des mesures au tableau de bord.
        return $this->json($data);
    }
}