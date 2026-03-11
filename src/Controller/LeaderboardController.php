<?php

namespace App\Controller;

use App\Repository\ProfielRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LeaderboardController extends AbstractController
{
    #[Route('/leaderboard', name: 'leaderboard')]
    public function index(Request $request, ProfielRepository $profielRepository): Response
    {
        $profielen = $profielRepository->createQueryBuilder('p')
            ->leftJoin('p.vragen', 'v')
            ->leftJoin('p.antwoorden', 'a')
            ->addSelect('COUNT(DISTINCT v.id) AS HIDDEN vragenCount')
            ->addSelect('COUNT(DISTINCT a.id) AS HIDDEN antwoordenCount')
            ->groupBy('p.id')
            ->orderBy('COUNT(DISTINCT v.id) + COUNT(DISTINCT a.id)', 'DESC')
            ->getQuery()
            ->getResult();

        $currentProfielId = $request->getSession()->get('profiel_id');

        return $this->render('leaderboard.html.twig', [
            'profielen'        => $profielen,
            'current_profiel_id' => $currentProfielId,
        ]);
    }
}
