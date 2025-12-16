<?php


namespace App\Service;

use App\Entity\Users;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\ConflictHttpException;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AuthService
{
    private EntityManagerInterface $entityManager;
    private UserRepository $userRepository;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(
        EntityManagerInterface      $entityManager,
        UserRepository              $userRepository,
        UserPasswordHasherInterface $passwordHasher)
    {
        $this->entityManager = $entityManager;
        $this->userRepository = $userRepository;
        $this->passwordHasher = $passwordHasher;
    }

    public function addUser(array $registerData): Users
    {
        $existing = $this->userRepository->findOneBy(['email' => $registerData['email']]);

        if ($existing !== null) {
            throw new ConflictHttpException("Email already exists");
        }

        $user = new Users();
        $user->setEmail($registerData['email']);
        $user->setName($registerData['name']);
        $user->setSurname($registerData['surname']);
        $user->setPassword($this->passwordHasher->hashPassword($user, $registerData['password']));
        $user->setRoles($registerData['roles']);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    /**
     * El login ahora lo gestiona el firewall y LexikJWT.
     */
//    public function login(array $loginDetails): Users
//    {
//        $email = $loginDetails['email'];
//        $password = $loginDetails['password'];
//
//        /** @var  Users|null $user */
//        $user = $this->userRepository->findOneBy(['email' => $email]);
//
//        if (!$user || !$this->passwordHasher->isPasswordValid($user, $password)) {
//            throw new UnauthorizedHttpException('', 'Invalid credentials');
//        }
//        return $user;
//    }
}