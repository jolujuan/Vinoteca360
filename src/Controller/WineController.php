<?php

namespace App\Controller;

use App\Service\WineService;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\SerializerInterface;

class WineController extends AbstractController
{
    private WineService $wineService;

    public function __construct(WineService $wineService)
    {
        $this->wineService = $wineService;
    }

    #[OA\Get(
        path: "/api/wines/measurements",
        description: "Returns a list of all wine measurements available in the database.",
        summary: "Gets all wine measurements",
        responses: [
            new OA\Response(
                response: 200,
                description: "Wine measurement list successfully obtained",
                content: new OA\JsonContent(
                    type: "array",
                    items: new OA\Items(
                        properties: [
                            new OA\Property(property: "id", type: "integer", example: 1),
                            new OA\Property(property: "name", type: "string", example: "Chardonnay"),
                            new OA\Property(property: "year", type: "integer", example: 2020),
                            new OA\Property(
                                property: "measurements",
                                type: "array",
                                items: new OA\Items(
                                    properties: [
                                        new OA\Property(property: "id", type: "integer", example: 1),
                                        new OA\Property(property: "year", type: "integer", example: 2020),
                                        new OA\Property(property: "sensor", properties: [
                                            new OA\Property(property: "id", type: "integer", example: 1),
                                            new OA\Property(property: "name", type: "string", example: "Temperature Sensor"),
                                        ],
                                            type: "object"
                                        ),
                                        new OA\Property(property: "color", type: "string", example: "Red"),
                                        new OA\Property(property: "temperature", type: "number", format: "float", example: 18.5),
                                        new OA\Property(property: "graduation", type: "number", format: "float", example: 12.5),
                                        new OA\Property(property: "ph", type: "number", format: "float", example: 3.5),
                                    ],
                                    type: "object"
                                )
                            ),
                        ],
                        type: "object"
                    )
                )
            ),
            new OA\Response(
                response: 401,
                description: "Unauthorized access",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "string", example: "error"),
                        new OA\Property(property: "message", type: "string", example: "Unauthorized access"),
                    ]
                )
            ),
        ]
    )]
    #[OA\Tag('Wines')]
    public function listWinesMeasurements(SerializerInterface $serializer): JsonResponse
    {
        $wines = $this->wineService->fetchWinesMeasurements();
        $context = $this->createSerializationContext();
        $jsonContent = $serializer->serialize($wines, 'json', $context);

        return new JsonResponse($jsonContent, 200, [], true);
    }

    private function createSerializationContext(): array
    {
        return [
            'groups' => ['wine_details'],
            AbstractNormalizer::CIRCULAR_REFERENCE_HANDLER => function ($object) {
                return $object->getId();
            },
        ];
    }
}