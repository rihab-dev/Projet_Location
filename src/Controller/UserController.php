<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class UserController extends AbstractController
{
    #[Route('/user', name: 'app_user')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }
    #[Route('/admin/create-user', name: 'admin_create_user', methods: ['POST'])]
    public function createUser(Request $request, EntityManagerInterface $entityManager): Response
    {
        // Récupérer les données de l'utilisateur (par exemple, email et rôle)
        $email = $request->get('email');
        $role = $request->get('role');  // ROLE_ADMIN ou ROLE_ETUDIANT

        $user = new User();
        $user->setEmail($email);

        // Déterminer et attribuer le rôle en fonction de l'entrée
        if ($role === 'ROLE_ETUDIANT') {
            $user->setRoles(['ROLE_ETUDIANT']);
        } elseif ($role === 'ROLE_ADMIN') {
            $user->setRoles(['ROLE_ADMIN']);
        }

        // Sauvegarder l'utilisateur en base de données
        $entityManager->persist($user);
        $entityManager->flush();

        return new Response('Utilisateur créé avec le rôle : ' . $role, 200);
    }
}
