<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\ProfileType;
use App\Entity\Profiel;

class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'profile')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $profiel = $em->getRepository(Profiel::class)->findOneBy([]);
    
        if (!$profiel) {
            throw $this->createNotFoundException('Geen profiel gevonden.');
        }
    
        $form = $this->createForm(ProfileType::class, $profiel);
        $form->handleRequest($request);
    
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            return $this->redirectToRoute('profile');
        }
    
        return $this->render('profile.html.twig', [
            'form' => $form,
            'profiel' => $profiel,
        ]);
    }
}