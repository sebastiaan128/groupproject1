<?php

namespace App\Controller;

use App\Entity\Profiel;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'profile')]
    public function index(EntityManagerInterface $em): Response
    {
        $profiel = $em->getRepository(Profiel::class)->find(1);

        return $this->render('profile.html.twig', [
            'profiel' => $profiel,
        ]);
    }
}
