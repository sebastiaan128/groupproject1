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
    private function looksLikeSqli(string $value): bool
    {
        $patterns = [
            "/('|--|;|\/\*|\*\/)/",
            '/\b(select|insert|update|delete|drop|union|exec|execute|create|alter|truncate|cast|convert|declare)\b/i',
            '/\b(or|and)\s+[\w\'"]+\s*=\s*[\w\'"]+/i',
            '/xp_/i',
            '/0x[0-9a-f]+/i',
        ];
        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $value)) return true;
        }
        return false;
    }

    #[Route('/profile', name: 'profile')]
    public function index(Request $request, EntityManagerInterface $em): Response
    {
        $profielId = $request->getSession()->get('profiel_id');
        $profiel = $profielId ? $em->getRepository(Profiel::class)->find($profielId) : null;

        if (!$profiel || $request->getSession()->get('is_guest')) {
            return $this->redirectToRoute('login');
        }

        $form = $this->createForm(ProfileType::class, $profiel);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $fields = [$profiel->getName(), $profiel->getEmail(), $profiel->getBio(), $profiel->getStudie()];
            foreach ($fields as $value) {
                if ($value !== null && $this->looksLikeSqli($value)) {
                    return $this->render('profile.html.twig', [
                        'form'        => $this->createForm(ProfileType::class, $profiel),
                        'profiel'     => $profiel,
                        'sql_attempt' => true,
                    ]);
                }
            }
            $em->flush();
            $request->getSession()->set('user_name', $profiel->getName());
            return $this->redirectToRoute('profile');
        }

        return $this->render('profile.html.twig', [
            'form'    => $form,
            'profiel' => $profiel,
        ]);
    }
}