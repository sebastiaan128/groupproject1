<?php

namespace App\Controller;

use App\Entity\Stemmen;
use App\Repository\VragenRepository;
use App\Repository\AntwoordenRepository;
use App\Repository\ProfielRepository;
use App\Repository\StemmenRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class VoteController extends AbstractController
{
    #[Route('/vote/vraag/{id}/{type}', name: 'vote_vraag', methods: ['POST'])]
    public function voteVraag(int $id, string $type, Request $request, EntityManagerInterface $em, VragenRepository $vragenRepository, ProfielRepository $profielRepository, StemmenRepository $stemmenRepository): JsonResponse
    {
        if ($request->getSession()->get('is_guest')) {
            return $this->json(['error' => 'Guests cannot vote'], 403);
        }

        $profielId = $request->getSession()->get('profiel_id');
        $profiel   = $profielRepository->find($profielId);
        $vraag     = $vragenRepository->find($id);

        if (!$vraag || !$profiel) {
            return $this->json(['error' => 'Not found'], 404);
        }

        if ($vraag->getProfiel()?->getId() === $profiel->getId()) {
            return $this->json(['error' => 'Cannot vote on your own question'], 403);
        }

        $bestaand = $stemmenRepository->findOneBy(['profiel' => $profiel, 'vraag' => $vraag]);

        if ($bestaand) {
            if ($bestaand->getType() === $type) {
                if ($type === 'up') $vraag->setUpvotes($vraag->getUpvotes() - 1);
                else $vraag->setDownvotes($vraag->getDownvotes() - 1);
                $em->remove($bestaand);
            } else {
                if ($type === 'up') {
                    $vraag->setUpvotes($vraag->getUpvotes() + 1);
                    $vraag->setDownvotes($vraag->getDownvotes() - 1);
                } else {
                    $vraag->setDownvotes($vraag->getDownvotes() + 1);
                    $vraag->setUpvotes($vraag->getUpvotes() - 1);
                }
                $bestaand->setType($type);
            }
        } else {
            $stem = new Stemmen();
            $stem->setProfiel($profiel);
            $stem->setVraag($vraag);
            $stem->setType($type);
            $em->persist($stem);

            if ($type === 'up') $vraag->setUpvotes($vraag->getUpvotes() + 1);
            else $vraag->setDownvotes($vraag->getDownvotes() + 1);
        }

        $em->flush();

        return $this->json([
            'upvotes'   => $vraag->getUpvotes(),
            'downvotes' => $vraag->getDownvotes(),
            'userVote'  => $bestaand && $bestaand->getType() === $type ? null : $type,
        ]);
    }

    #[Route('/vote/antwoord/{id}/{type}', name: 'vote_antwoord', methods: ['POST'])]
    public function voteAntwoord(int $id, string $type, Request $request, EntityManagerInterface $em, AntwoordenRepository $antwoordenRepository, ProfielRepository $profielRepository, StemmenRepository $stemmenRepository): JsonResponse
    {
        if ($request->getSession()->get('is_guest')) {
            return $this->json(['error' => 'Guests cannot vote'], 403);
        }

        $profielId = $request->getSession()->get('profiel_id');
        $profiel   = $profielRepository->find($profielId);
        $antwoord  = $antwoordenRepository->find($id);

        if (!$antwoord || !$profiel) {
            return $this->json(['error' => 'Not found'], 404);
        }

        if ($antwoord->getProfiel()?->getId() === $profiel->getId()) {
            return $this->json(['error' => 'Cannot vote on your own answer'], 403);
        }

        $bestaand = $stemmenRepository->findOneBy(['profiel' => $profiel, 'antwoord' => $antwoord]);

        if ($bestaand) {
            if ($bestaand->getType() === $type) {
                if ($type === 'up') $antwoord->setUpvotes($antwoord->getUpvotes() - 1);
                else $antwoord->setDownvotes($antwoord->getDownvotes() - 1);
                $em->remove($bestaand);
            } else {
                if ($type === 'up') {
                    $antwoord->setUpvotes($antwoord->getUpvotes() + 1);
                    $antwoord->setDownvotes($antwoord->getDownvotes() - 1);
                } else {
                    $antwoord->setDownvotes($antwoord->getDownvotes() + 1);
                    $antwoord->setUpvotes($antwoord->getUpvotes() - 1);
                }
                $bestaand->setType($type);
            }
        } else {
            $stem = new Stemmen();
            $stem->setProfiel($profiel);
            $stem->setAntwoord($antwoord);
            $stem->setType($type);
            $em->persist($stem);

            if ($type === 'up') $antwoord->setUpvotes($antwoord->getUpvotes() + 1);
            else $antwoord->setDownvotes($antwoord->getDownvotes() + 1);
        }

        $em->flush();

        return $this->json([
            'upvotes'   => $antwoord->getUpvotes(),
            'downvotes' => $antwoord->getDownvotes(),
            'userVote'  => $bestaand && $bestaand->getType() === $type ? null : $type,
        ]);
    }
}
