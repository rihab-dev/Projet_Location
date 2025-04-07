<?php

namespace App\Service;

use App\Entity\RendezVous;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class EmailService
{
    public function __construct(private MailerInterface $mailer) {}

    public function sendRendezVousNotification(string $to, RendezVous $rendezVous): void
    {
        $email = (new TemplatedEmail())
            ->from('noreply@location-maison.com')
            ->to($to)
            ->subject('Nouvelle demande de rendez-vous')
            ->htmlTemplate('emails/rendezvous_notification.html.twig')
            ->context([
                'rendezVous' => $rendezVous,
            ]);

        $this->mailer->send($email);
    }

    public function sendRendezVousConfirmation(string $to, RendezVous $rendezVous): void
    {
        $email = (new TemplatedEmail())
            ->from('noreply@location-maison.com')
            ->to($to)
            ->subject('Votre rendez-vous a été confirmé')
            ->htmlTemplate('emails/rendezvous_confirmation.html.twig')
            ->context([
                'rendezVous' => $rendezVous,
            ]);

        $this->mailer->send($email);
    }
}