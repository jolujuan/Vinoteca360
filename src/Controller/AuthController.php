<?php

namespace App\Controller;

use App\Service\AuthService;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use OpenApi\Attributes as OA;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;


class AuthController extends AbstractController
{
    private AuthService $authService;

    public function __construct(AuthService $authService, JWTTokenManagerInterface $jwt)
    {
        $this->authService = $authService;
        $this->jwt = $jwt;
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
    public function login(Request $request): JsonResponse
    {
        throw new \LogicException('By the firewall.');
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


    #[OA\Post(
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
    }
}