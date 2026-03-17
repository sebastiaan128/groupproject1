<?php

namespace App\Controller;

use App\Repository\AntwoordenRepository;
use App\Repository\ProfielRepository;
use App\Repository\VragenRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(Request $request, ProfielRepository $profielRepository, VragenRepository $vragenRepository, AntwoordenRepository $antwoordenRepository): Response
    {
        $recenteVragen = $vragenRepository->createQueryBuilder('v')
            ->orderBy('v.id', 'DESC')
            ->setMaxResults(5)
            ->getQuery()
            ->getResult();

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
        $top3 = array_slice($profielen, 0, 3);

        $currentProfielId = $request->getSession()->get('profiel_id');
        $mijnProfiel = $currentProfielId ? $profielRepository->find($currentProfielId) : null;

        $mijnRank = null;
        if ($mijnProfiel) {
            foreach ($profielen as $i => $p) {
                if ($p->getId() === $mijnProfiel->getId()) {
                    $mijnRank = $i + 1;
                    break;
                }
            }
        }

        return $this->render('home.html.twig', [
            'user_name'       => $request->getSession()->get('user_name'),
            'total_students'  => count($profielen),
            'total_vragen'    => count($vragenRepository->findAll()),
            'recente_vragen'  => $recenteVragen,
            'top3'            => $top3,
            'punten'          => $punten,
            'mijn_profiel'    => $mijnProfiel,
            'mijn_punten'     => $mijnProfiel ? ($punten[$mijnProfiel->getId()] ?? 0) : 0,
            'mijn_rank'       => $mijnRank,
            'is_guest'        => (bool) $request->getSession()->get('is_guest'),
        ]);
    }
}
