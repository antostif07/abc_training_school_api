<?php

namespace App\Controller;
use App\Entity\User;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class SecurityController extends AbstractController
{
    #[Route('/login', name: 'app_login', methods: ["POST"])]
    public function login(
        Request $request,
        ManagerRegistry $managerRegistry
    ): JsonResponse
    {
        $data = json_decode($request->getContent(), true);
//        return $data;
        $username = $data['username'];
        $password = $data['password'];

        $userRepository = $managerRegistry->getRepository(User::class);
        $user = $userRepository->findOneBy(["number" => $username]);
        if ($user) {
            if($user->getPassword() == $password) {
                return $this->json([
                    'message' => 'Ok',
                    'id' => $user->getId(),
                    'username' => $user->getNumber()
                ], 200);
            } else {
                return $this->json([
                    'message' => 'Wrong Password',
                ], 400);
            }
        } else {
            return $this->json([
                'message' => 'User Not found',
            ], 400);
        }
    }
}
