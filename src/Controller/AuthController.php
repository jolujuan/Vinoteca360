<?php

namespace App\Controller;

use App\Service\AuthService;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;


class AuthController extends AbstractController
{
    private AuthService $authService;

    public function __construct(AuthService $authService)
    {
        $this->authService = $authService;
    }

    #[OA\Post(
        path: "/api/login",
        summary: "Login user",
        requestBody: new OA\RequestBody(
            description: "User credentials",
            required: true,
            content: new OA\MediaType(
                mediaType: "application/json",
                schema: new OA\Schema(
                    properties: [
                        new OA\Property(property: "email", type: "string", format: "email", example: "user@example.com"),
                        new OA\Property(property: "password", type: "string", format: "password", example: "password")
                    ],
                    type: "object"
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Successful!",
                content: new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        properties: [
                            new OA\Property(property: "status", type: "string", example: "success"),
                            new OA\Property(property: "user", type: "string", example: "user@example.com")
                        ],
                        type: "object"
                    )
                )
            ),
            new OA\Response(
                response: 401,
                description: "Unauthorized",
                content: new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        properties: [
                            new OA\Property(property: "status", type: "string", example: "error"),
                            new OA\Property(property: "message", type: "string", example: "Invalid credentials")
                        ]
                    )
                )
            )
        ]
    )]
    #[OA\Tag('Authentication')]
    public function login(): JsonResponse
    {
        throw new \LogicException('By the firewall.');
    }

    #[OA\Post(
        path: "/api/register",
        summary: "Register user",
        requestBody: new OA\RequestBody(
            description: "User data",
            required: true,
            content: new OA\MediaType(
                mediaType: "application/json",
                schema: new OA\Schema(
                    properties: [
                        new OA\Property(property: "email", type: "string", format: "email", example: "user@example.com"),
                        new OA\Property(property: "name", type: "string", example: "Jose"),
                        new OA\Property(property: "surname", type: "string", example: "Juan"),
                        new OA\Property(property: "password", type: "string", format: "password", example: "admin1234"),
                        new OA\Property(property: "roles", type: "array", items: new OA\Items(type: "string"), example: ["ROLE_USER"]
                        )
                    ],
                    type: "object"
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "User successfully registered",
                content: new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        properties: [
                            new OA\Property(property: "status", type: "string", example: "success"),
                            new OA\Property(property: "user", type: "string", example: "user@example.com")
                        ],
                        type: "object"
                    )
                )
            ),
            new OA\Response(
                response: 400,
                description: "Bad request, invalid or missing data",
                content: new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        properties: [
                            new OA\Property(property: "error", type: "string", example: "Missing email or invalid data")
                        ],
                        type: "object"
                    )
                )
            ),
            new OA\Response(
                response: 409,
                description: "Email already exists",
                content: new OA\MediaType(
                    mediaType: "application/json",
                    schema: new OA\Schema(
                        properties: [
                            new OA\Property(property: "error", type: "string", example: "Email already exists")
                        ],
                        type: "object"
                    )
                )
            )
        ]
    )]
    #[OA\Tag('Authentication')]
    public function register(Request $request): JsonResponse
    {
        try {
            $registerData = json_decode($request->getContent(), true);
            $this->authService->addUser($registerData);
            return new JsonResponse(['status' => 'Registered User'], 201);
        } catch (BadRequestHttpException $e) {
            return new JsonResponse(['error' => $e->getMessage()], 400);
        } catch (ConflictHttpException  $e) {
            return new JsonResponse(['error' => $e->getMessage()], 409);
        }
    }



    /*
     * Ahora manejamos el login mediante el firewall i el LexikJWT
     *
     * try {
        $loginData = json_decode($request->getContent(), true);
        $user = $this->authService->login($loginData);

        $token = $this->jwt->create($user);

        return new JsonResponse([
            'token' => $token,
            'user' => $user->getEmail()
        ], 200);

    } catch (UnauthorizedHttpException $e) {
        return new JsonResponse([
            'status' => 'error',
            'message' => $e->getMessage()
        ], 401);
    }*/


    /*
     *
     * Solo lo utilizamos para hacer pruebas.
     *
     * #[OA\Post(
        path: "/api/logout",
        description: "Just tests. Deletes the session when cookies are stored in memory (stateless:false)",
        summary: "Logout user",
        responses: [
            new OA\Response(
                response: 200,
                description: "Successful"
            ),
            new OA\Response(
                response: 401,
                description: "Unauthorized"
            )
        ]
    )]
    #[OA\Tag('Authentication')]
    public function logout(): Response
    {
        return $this->json(['message' => 'see you soon!']);
    }*/
}