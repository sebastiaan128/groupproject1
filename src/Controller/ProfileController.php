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
        $profielId = $request->getSession()->get('profiel_id');
        $profiel = $profielId ? $em->getRepository(Profiel::class)->find($profielId) : null;

        if (!$profiel) {
            return $this->redirectToRoute('login');
        }

        $form = $this->createForm(ProfileType::class, $profiel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $request->getSession()->set('user_name', $profiel->getName());
            return $this->redirectToRoute('profile');
        }

        return $this->render('profile.html.twig', [
            'form' => $form,
            'profiel' => $profiel,
        ]);
    }
}