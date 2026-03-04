<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class VragenController extends AbstractController
{
    #[Route('/vragen', name: 'vragen')]
    public function index(): Response
    {
        return $this->render('vragen.html.twig');
    }
}
