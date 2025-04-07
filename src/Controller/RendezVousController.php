<?php

// src/Controller/RendezVousController.php



namespace App\Controller;

use App\Entity\RendezVous;
use App\Entity\Utilisateur;
use App\Form\RendezVousType;
use App\Repository\RendezVousRepository;
use App\Service\EmailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/rendezvous')]
class RendezVousController extends AbstractController
{
    #[Route('/', name: 'app_rendez_vous_index', methods: ['GET'])]
    public function index(RendezVousRepository $rendezVousRepository): Response
    {
        return $this->render('rendez_vous/index.html.twig', [
            'rendez_vouses' => $rendezVousRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_rendez_vous_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, EmailService $emailService): Response
    {
        $rendezVous = new RendezVous();
        $form = $this->createForm(RendezVousType::class, $rendezVous);
        $form->handleRequest($request);

       if ($form->isSubmitted() && $form->isValid()) {
            // Vérifier que l'utilisateur connecté est un étudiant
            $user = $this->getUser();
            if (!$user || !in_array('ROLE_ETUDIANT', $user->getRoles())) {
                throw $this->createAccessDeniedException('Seuls les étudiants peuvent prendre rendez-vous.');
            }

            $rendezVous->setEtudiant($user);
            $rendezVous->setStatut('en_attente');

            $entityManager->persist($rendezVous);
            $entityManager->flush();

            // Envoyer un email au propriétaire
            $emailService->sendRendezVousNotification(
                $rendezVous->getProprietaire()->getEmail(),
                $rendezVous
            );

            return $this->redirectToRoute('app_rendez_vous_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('rendez_vous/new.html.twig', [
            'rendez_vous' => $rendezVous,
            'form' => $form,
        ]);
    }

    #[Route('/{id}/confirm', name: 'app_rendez_vous_confirm', methods: ['POST'])]
    public function confirm(Request $request, RendezVous $rendezVous, EntityManagerInterface $entityManager, EmailService $emailService): Response
    {
        // Vérifier que l'utilisateur est le propriétaire
        $user = $this->getUser();
        if ($user !== $rendezVous->getProprietaire()) {
            throw $this->createAccessDeniedException('Vous ne pouvez pas confirmer ce rendez-vous.');
        }

        $rendezVous->setStatut('confirme');
        $entityManager->flush();

        // Envoyer une confirmation à l'étudiant
        $emailService->sendRendezVousConfirmation(
            $rendezVous->getEtudiant()->getEmail(),
            $rendezVous
        );

        return $this->redirectToRoute('app_rendez_vous_index');
    }

    // Autres méthodes (show, edit, delete)...
}
