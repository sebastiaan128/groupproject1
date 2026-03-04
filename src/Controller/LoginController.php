<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'login')]
    public function index(): Response
    {
        return $this->render('login.html.twig', [
            'firebaseConfig' => [
                'apiKey'            => $_ENV['FIREBASE_API_KEY'],
                'authDomain'        => $_ENV['FIREBASE_AUTH_DOMAIN'],
                'projectId'         => $_ENV['FIREBASE_PROJECT_ID'],
                'storageBucket'     => $_ENV['FIREBASE_STORAGE_BUCKET'],
                'messagingSenderId' => $_ENV['FIREBASE_MESSAGING_SENDER_ID'],
                'appId'             => $_ENV['FIREBASE_APP_ID'],
            ],
        ]);
    }
}
