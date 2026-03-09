<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfielController extends AbstractController
{
    #[Route('/profiel', name: 'profiel')]
    public function index(): Response
    {
        return $this->render('profiel.html.twig');
    }
}
