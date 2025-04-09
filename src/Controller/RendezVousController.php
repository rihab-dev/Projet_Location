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
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

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
    #[Route('/new', name: 'app_rendez_vous_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $rendezVous = new RendezVous();
        $form = $this->createForm(RendezVousType::class, $rendezVous);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            if ($form->isValid()) {
                $rendezVous->setStatut('en_attente');
                $entityManager->persist($rendezVous);
                $entityManager->flush();

                $this->addFlash('success', 'Rendez-vous enregistré avec succès!');
                return $this->redirectToRoute('app_rendez_vous_index');
            } else {
                // Affichez les erreurs de validation
                $this->addFlash('error', 'Veuillez corriger les erreurs dans le formulaire.');
            }
        }

        return $this->render('rendez_vous/new.html.twig', [
            'form' => $form->createView(),
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
