<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\VragenRepository;
use App\Repository\StemmenRepository;
use App\Entity\Tags;
use App\Entity\vragen;
use App\Entity\Antwoorden;
use App\Entity\Notificatie;
use Kreait\Firebase\Factory;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ProfielRepository;


class QuestionController extends AbstractController
{
    #[Route('/questions', name: 'questions')]
    public function questions(VragenRepository $vragenRepository, Request $request, EntityManagerInterface $em): Response
    {
        $filter    = $request->query->get('filter', 'all');
        $search    = $request->query->get('search', '');
        $profielId = $request->getSession()->get('profiel_id');
        $tags      = $em->getRepository(Tags::class)->findAll();

        $vragen = $vragenRepository->findFiltered($filter, $search, $profielId);

        return $this->render('questions.html.twig', [
            'vragen'   => $vragen,
            'filter'   => $filter,
            'search'   => $search,
            'tags'     => $tags,
            'is_guest' => $request->getSession()->get('is_guest', false),
        ]);
    }

    #[Route('/questions/load', name: 'questions_load')]
    public function load(VragenRepository $vragenRepository, Request $request): JsonResponse
    {
        $filter    = $request->query->get('filter', 'all');
        $search    = $request->query->get('search', '');
        $tag       = $request->query->get('tag', '');
        $profielId = $request->getSession()->get('profiel_id');

        $vragen = $vragenRepository->findFiltered($filter, $search, $profielId, $tag);

        $data = array_map(function ($v) {
            return [
                'id'         => $v->getId(),
                'titel'      => $v->getTitel(),
                'beschrijving' => $v->getBeschrijving(),
                'upvotes'    => $v->getUpvotes(),
                'antwoorden' => count($v->getAntwoorden()),
                'views'      => $v->getViews(),
                'status'     => $v->getStatus(),
                'tags'       => array_map(fn($t) => $t->getNaam(), $v->getTags()->toArray()),
            ];
        }, $vragen);

        return $this->json(['vragen' => $data, 'total' => count($data)]);
    }

    #[Route('/create-question', name: 'create-question')]
    public function createQuestion(Request $request, EntityManagerInterface $em): Response
    {
        if ($request->getSession()->get('is_guest')) {
            return $this->redirectToRoute('questions');
        }
        $tags = $em->getRepository(Tags::class)->findAll();
        return $this->render('create-question.html.twig', ['tags' => $tags]);
    }

    #[Route('/anwser-question/{id}', name: 'anwser-question')]
    public function anwserQuestion(int $id, VragenRepository $vragenRepository, Request $request, StemmenRepository $stemmenRepository, ProfielRepository $profielRepository, EntityManagerInterface $em): Response
    {
        $vraag = $vragenRepository->find($id);

        if (!$vraag) {
            throw $this->createNotFoundException('Question not found.');
        }

        $profielId = $request->getSession()->get('profiel_id');
        $profiel   = $profielRepository->find($profielId);

        $vraag->setViews($vraag->getViews() + 1);
        $em->flush();

        $vraagStem = $profiel ? $stemmenRepository->findOneBy(['profiel' => $profiel, 'vraag' => $vraag]) : null;

        $antwoordStemmen = [];
        if ($profiel) {
            foreach ($vraag->getAntwoorden() as $antwoord) {
                $stem = $stemmenRepository->findOneBy(['profiel' => $profiel, 'antwoord' => $antwoord]);
                $antwoordStemmen[$antwoord->getId()] = $stem ? $stem->getType() : null;
            }
        }

        return $this->render('components/anwser-question.html.twig', [
            'vraag'           => $vraag,
            'username'        => $request->getSession()->get('user_name'),
            'is_guest'        => $request->getSession()->get('is_guest', false),
            'vraagStem'       => $vraagStem ? $vraagStem->getType() : null,
            'antwoordStemmen' => $antwoordStemmen,
        ]);
    }
    #[Route('/submit-question', name: 'submit-question', methods: ['POST'])]
    public function submitQuestion(request $request, EntityManagerInterface $em, ProfielRepository $profielRepository ): Response
    {
        if ($request->getSession()->get('is_guest')) {
            return $this->redirectToRoute('questions');
        }
        $profielId = $request->getSession()->get('profiel_id');
        $profiel = $profielRepository->find($profielId);

        $vraag = new vragen();
        $vraag->setTitel($request->request->get('titel'));
        $vraag->setBeschrijving($request->request->get('beschrijving'));
        $vraag->setStatus('Open');
        $vraag->setProfiel($profiel);
        $vraag->setDownvotes(0);
        $vraag->setUpvotes(0);
        $vraag->setViews(0);

        foreach ($request->request->all('tags') as $tagId) {
            $tag = $em->getRepository(Tags::class)->find((int) $tagId);
            if ($tag) $vraag->addTag($tag);
        }

        $em->persist($vraag);
        $em->flush();

        return $this->redirectToRoute('questions');
    }
    #[Route('/submit-anwser', name: 'submit-anwser', methods: ['POST'])]
    public function submitAnwser(Request $request, EntityManagerInterface $em, ProfielRepository $profielRepository, VragenRepository $vragenRepository): Response
    {
        if ($request->getSession()->get('is_guest')) {
            return $this->redirectToRoute('questions');
        }
        $profielId = $request->getSession()->get('profiel_id');
        $profiel = $profielRepository->find($profielId);
        $vraag = $vragenRepository->find($request->request->get('vraag_id'));

        $antwoord = new Antwoorden();
        $antwoord->setBeschrijving($request->request->get('antwoord'));
        $antwoord->setUpvotes(0);
        $antwoord->setDownvotes(0);
        $antwoord->setProfiel($profiel);
        $antwoord->setVraag($vraag);

        $em->persist($antwoord);

        $vraagEigenaar = $vraag->getProfiel();
        if ($vraagEigenaar && $vraagEigenaar->getId() !== $profiel->getId()) {
            $notificatie = new Notificatie();
            $notificatie->setProfiel($vraagEigenaar);
            $notificatie->setVraag($vraag);
            $bericht = $profiel->getName() . ' answered your question: ' . $vraag->getTitel();
            $notificatie->setBericht($bericht);
            $notificatie->setAangemaaktOp(new \DateTime());
            $em->persist($notificatie);
            $em->flush();

            try {
                $firebase = (new Factory)->withServiceAccount($_ENV['FIREBASE_CREDENTIALS_PATH']);
                $db = $firebase->createDatabase();
                $db->getReference('notifications/' . $vraagEigenaar->getId())->push([
                    'bericht' => $bericht,
                    'vraagId' => $vraag->getId(),
                    'tijd'    => (new \DateTime())->format('d M H:i'),
                    'gelezen' => false,
                ]);
            } catch (\Throwable) {}
        } else {
            $em->flush();
        }

        return $this->redirectToRoute('anwser-question', ['id' => $vraag->getId()]);
    }
}



