<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NetworkController extends AbstractController
{
    #[Route('/network', name: 'network')]
    public function index(): Response
    {
        return $this->render('network.html.twig');
    }
}
