<?php

namespace App\Controller;

use App\Service\MeasurementService;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class MeasurementController extends AbstractController
{
    private MeasurementService $measurementService;

    public function __construct(MeasurementService $measurementService)
    {
        $this->measurementService = $measurementService;
    }

    #[IsGranted('ROLE_USER')]
    #[OA\Post(
        path: "/api/measurements",
        description: "Creates a new measurement entry for a specific wine using the provided sensor data.",
        summary: "Add a new measurement",
        requestBody: new OA\RequestBody(
            description: "Measurement data to be added",
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: "idSensor",
                        description: "ID of the sensor used",
                        type: "integer",
                        example: 1
                    ),
                    new OA\Property(
                        property: "idWine",
                        description: "ID of the wine being measured",
                        type: "integer",
                        example: 3
                    ),
                    new OA\Property(
                        property: "year",
                        description: "Year of the measurement",
                        type: "integer",
                        example: 2024
                    ),
                    new OA\Property(
                        property: "color",
                        description: "Color of the wine",
                        type: "string",
                        example: "Red"
                    ),
                    new OA\Property(
                        property: "temperature",
                        description: "Temperature of the wine",
                        type: "number",
                        format: "float",
                        example: 18.5
                    ),
                    new OA\Property(
                        property: "graduation",
                        description: "Alcohol graduation of the wine",
                        type: "number",
                        format: "float",
                        example: 12.5
                    ),
                    new OA\Property(
                        property: "ph",
                        description: "pH level of the wine",
                        type: "number",
                        format: "float",
                        example: 3.5
                    ),
                ],
                type: "object"
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Measurement created",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "string", example: "Measurement created")
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: "Sensor or Wine not found",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "error", type: "string", example: "Sensor or Wine not found")
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: "Invalid input",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "error", type: "string", example: "Invalid input data")
                    ]
                )
            ),
            new OA\Response(
                response: 403,
                description: "Unauthorized access, only administrator users",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "string", example: "error"),
                        new OA\Property(property: "message", type: "string", example: "Unauthorized access"),
                    ]
                )
            ),
        ]
    )]
    #[OA\Tag('Measurements')]
    public function createMeasurement(Request $request): JsonResponse
    {
        try {
            $measurementData = json_decode($request->getContent(), true);
            $this->measurementService->addMeasurement($measurementData);
            return new JsonResponse(['status' => 'Measurement created'], 201);
        } catch (BadRequestHttpException $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        } catch (NotFoundHttpException $e) {
            return new JsonResponse(['error' => $e->getMessage()], 404);
        }
    }
}