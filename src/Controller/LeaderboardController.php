<?php

namespace App\Controller;

use App\Repository\AntwoordenRepository;
use App\Repository\ProfielRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class LeaderboardController extends AbstractController
{
    #[Route('/leaderboard', name: 'leaderboard')]
    public function index(Request $request, ProfielRepository $profielRepository, AntwoordenRepository $antwoordenRepository): Response
    {
        $antwoorden = $antwoordenRepository->createQueryBuilder('a')
            ->select('a', 'p', 'v')
            ->join('a.profiel', 'p')
            ->join('a.vraag', 'v')
            ->getQuery()
            ->getResult();

        $vraagBest = [];
        foreach ($antwoorden as $antwoord) {
            $vraagId = $antwoord->getVraag()->getId();
            $net = ($antwoord->getUpvotes() ?? 0) - ($antwoord->getDownvotes() ?? 0);

            if (!isset($vraagBest[$vraagId]) || $net > $vraagBest[$vraagId]['max']) {
                $vraagBest[$vraagId] = ['max' => $net, 'profielIds' => [$antwoord->getProfiel()->getId()]];
            } elseif ($net === $vraagBest[$vraagId]['max']) {
                $vraagBest[$vraagId]['profielIds'][] = $antwoord->getProfiel()->getId();
            }
        }

        $punten = [];
        foreach ($vraagBest as $data) {
            if ($data['max'] > 0) {
                foreach ($data['profielIds'] as $profielId) {
                    $punten[$profielId] = ($punten[$profielId] ?? 0) + 1;
                }
            }
        }
        $profielen = $profielRepository->createQueryBuilder('p')
            ->where('p.firebaseUid IS NULL OR p.firebaseUid NOT LIKE :guestPrefix')
            ->setParameter('guestPrefix', 'guest_%')
            ->getQuery()
            ->getResult();
        usort($profielen, function ($a, $b) use ($punten) {
            return ($punten[$b->getId()] ?? 0) <=> ($punten[$a->getId()] ?? 0);
        });

        $currentProfielId = $request->getSession()->get('profiel_id');

        return $this->render('leaderboard.html.twig', [
            'profielen'          => $profielen,
            'punten'             => $punten,
            'current_profiel_id' => $currentProfielId,
        ]);
    }
}
