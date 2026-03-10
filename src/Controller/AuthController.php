<?php

namespace App\Controller;

use Kreait\Firebase\Factory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthController extends AbstractController
{
    #[Route('/auth/verify', name: 'auth_verify', methods: ['POST'])]
    public function verify(Request $request): Response
    {
        $idToken = $request->request->get('idToken');

        try {
            $firebase = (new Factory)
                ->withServiceAccount($_ENV['FIREBASE_CREDENTIALS_PATH']);

            $verifiedToken = $firebase->createAuth()->verifyIdToken($idToken);

            $session = $request->getSession();
            $session->set('user_uid',   $verifiedToken->claims()->get('sub'));
            $session->set('user_email', $verifiedToken->claims()->get('email'));
            $session->set('user_name',  $verifiedToken->claims()->get('name'));

            return $this->json(['ok' => true]);
        } catch (\Throwable $e) {
            return $this->json(['ok' => false], 401);
        }
    }

    #[Route('/logout', name: 'logout')]
    public function logout(Request $request): Response
    {
        $request->getSession()->invalidate();
        return $this->redirectToRoute('login');
    }
}
