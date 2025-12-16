<?php

namespace App\Controller;

use App\Service\SensorService;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Serializer\SerializerInterface;

class SensorController extends AbstractController
{
    private SensorService $sensorService;

    public function __construct(SensorService $sensorService)
    {
        $this->sensorService = $sensorService;
    }

    #[IsGranted('ROLE_USER')]
    #[OA\Post(
        path: "/api/sensors",
        description: "Registers a new sensor and associates it with a user.",
        summary: "Add a new sensor",
        requestBody: new OA\RequestBody(
            description: "Sensor data to be added",
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(
                        property: "idUser",
                        description: "ID of the user to associate the sensor with",
                        type: "integer",
                        example: 1
                    ),
                    new OA\Property(
                        property: "name",
                        description: "Name of the sensor",
                        type: "string",
                        example: "Temperature Sensor"
                    ),
                ],
                type: "object"
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Sensor registered successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "status", type: "string", example: "Registered Sensor")
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: "User not found",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "error", type: "string", example: "User not found")
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
    #[OA\Tag('Sensors')]
    public function createSensor(Request $request): JsonResponse
    {
        try {
            $sensorData = json_decode($request->getContent(), true);
            $this->sensorService->addSensor($sensorData);
            return new JsonResponse(['status' => 'Registered Sensor'], 201);
        } catch (BadRequestHttpException $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        } catch (NotFoundHttpException $e) {
            return new JsonResponse(['error' => $e->getMessage()], 404);
        }
    }

    #[IsGranted('ROLE_USER')]
    #[OA\Get(
        path: "/api/sensors",
        description: "Retrieves a list of all sensors sorted by name in ascending order.",
        summary: "Get all sensors",
        responses: [
            new OA\Response(
                response: 200,
                description: "List of sensors retrieved successfully",
                content: new OA\JsonContent(
                    type: "array",
                    items: new OA\Items(
                        properties: [
                            new OA\Property(property: "id", type: "integer", example: 1),
                            new OA\Property(property: "name", type: "string", example: "Temperature Sensor"),
                            new OA\Property(property: "user", properties: [
                                new OA\Property(property: "id", type: "integer", example: 1),
                                new OA\Property(property: "name", type: "string", example: "John Doe"),
                            ],
                                type: "object"
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
    #[OA\Tag('Sensors')]
    public function listSensors(SerializerInterface $serializer): JsonResponse
    {
        $sensors = $this->sensorService->fetchSensors();
        $jsonContent = $serializer->serialize($sensors, 'json', ['groups' => ['sensor_details']]);

        return new JsonResponse($jsonContent, 200, [], true);
    }
}