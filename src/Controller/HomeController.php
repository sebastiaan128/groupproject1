<?php

namespace App\Controller;

use App\Repository\ProfielRepository;
use App\Repository\VragenRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(Request $request, ProfielRepository $profielRepository, VragenRepository $vragenRepository): Response
    {
        return $this->render('home.html.twig', [
            'user_name'      => $request->getSession()->get('user_name'),
            'total_students' => count($profielRepository->findAll()),
            'total_vragen'   => count($vragenRepository->findAll()),
        ]);
    }
}
