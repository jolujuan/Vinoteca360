<?php

/**
 * @deprecated Esta clase ya no se usa.
 * El login ahora lo gestiona el firewall y LexikJWT.
 */


namespace App\Service;

use App\Entity\Users;
use App\Repository\UserRepository;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AuthService
{
    private UserRepository $userRepository;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher)
    {
        $this->userRepository = $userRepository;
        $this->passwordHasher = $passwordHasher;
    }

    public function login(array $loginDetails): Users
    {
        $email = $loginDetails['email'];
        $password = $loginDetails['password'];

        /** @var  Users|null $user */
        $user = $this->userRepository->findOneBy(['email' => $email]);

        if (!$user || !$this->passwordHasher->isPasswordValid($user, $password)) {
            throw new UnauthorizedHttpException('', 'Invalid credentials');
        }
        return $user;
    }
}