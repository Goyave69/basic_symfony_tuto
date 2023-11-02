<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;

final class SecurityController extends AbstractController
{
    public function __construct(
        private readonly UserPasswordHasherInterface $passwordHasher,
        private readonly SerializerInterface $serializer,
        private readonly EntityManagerInterface $em
    )
    {
    }

    #[Route('/registration', name: 'registration', methods: "POST")]
    public function registration(Request $request)
    {
        /** @var User $user */
       $user = $this->serializer->deserialize(
           $request->getContent(),
           User::class,
           'json',
           ['groups' => 'user:write']
       );

       $user->setPassword(
           $this->passwordHasher->hashPassword(
               $user,
               $user->getPlainPassword()
           )
       );
       $user->eraseCredentials();

       $this->em->persist($user);
       $this->em->flush();

       return $this->json($user, 201, [], ['groups'=> 'user:read']);
    }

    #[Route('/api/login', name: 'api_login', methods: 'POST')]
    public function login(): Response
    {
        return $this->json([
            'message' => 'Welcome to your new controller!',
        ]);
    }
}
