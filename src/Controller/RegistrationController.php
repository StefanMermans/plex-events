<?php

namespace App\Controller;

use App\Dto\RegisterDto;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

final class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_registration', methods: ['POST'])]
    public function index(
        UserPasswordHasherInterface      $passwordHasher,
        EntityManagerInterface           $entityManager,
        ValidatorInterface               $validator,
        #[MapRequestPayload] RegisterDto $registerDto
    ): JsonResponse
    {
        $user = new User();
        $user->setEmail($registerDto->email);
        $plainPassword = $registerDto->password;

        $hashedPassword = $passwordHasher->hashPassword(
            $user,
            $plainPassword
        );
        $user->setPassword($hashedPassword);

        $errors = $validator->validate($user);
        if (count($errors) > 0) {
            return new JsonResponse(['errors' => (string)$errors], 422);
        }

        $entityManager->persist($user);
        $entityManager->flush();

        return $this->json($user);
    }
}
