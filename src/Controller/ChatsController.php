<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ChatsController extends AbstractController
{
    #[Route('/chats', name: 'chats')]
    public function index(): Response
    {
        return $this->render('chats.html.twig');
    }
}
