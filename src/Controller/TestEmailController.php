<?php
// src/Controller/TestEmailController.php

namespace App\Controller;

use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TestEmailController extends AbstractController
{
public function test(MailerInterface $mailer): Response
{
$email = (new Email())
->from('test@example.com')
->to('admin@example.com')
->subject('Test email')
->text('Ceci est un test d\'envoi d\'email');

$mailer->send($email);

return new Response('Email envoyé!');
}
}
