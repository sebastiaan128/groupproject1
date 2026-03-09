<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NetwerkController extends AbstractController
{
    #[Route('/netwerk', name: 'netwerk')]
    public function index(): Response
    {
        return $this->render('netwerk.html.twig');
    }
}
