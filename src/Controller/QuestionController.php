<?php

namespace App\Controller;

use App\Entity\Antwoorden;
use App\Entity\Notificatie;
use App\Repository\ProfielRepository;
use App\Repository\VragenRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class QuestionController extends AbstractController
{
    #[Route('/questions', name: 'questions')]
    public function questions(VragenRepository $vragenRepository): Response
    {
        $vragen = $vragenRepository->findAll();

        return $this->render('questions.html.twig', [
            'vragen' => $vragen,
        ]);

    }

    #[Route('/create-question', name: 'create-question')]
    public function createQuestion(): Response
    {
        return $this->render('create-question.html.twig');
    }

    #[Route('/anwser-question/{id}', name: 'anwser-question')]
    public function anwserQuestion(int $id, VragenRepository $vragenRepository): Response
    {
        $vraag = $vragenRepository->find($id);

        return $this->render('components/anwser-question.html.twig', [
            'vraag' => $vraag,
        ]);
    }

    #[Route('/anwser-question/{id}/post', name: 'post-antwoord', methods: ['POST'])]
    public function postAntwoord(
        int $id,
        Request $request,
        VragenRepository $vragenRepository,
        ProfielRepository $profielRepository,
        EntityManagerInterface $em
    ): Response {
        $vraag     = $vragenRepository->find($id);
        $profielId = $request->getSession()->get('profiel_id');
        $profiel   = $profielRepository->find($profielId);
        $tekst     = trim($request->request->get('beschrijving', ''));

        if ($vraag && $profiel && $tekst !== '') {
            $antwoord = new Antwoorden();
            $antwoord->setVraag($vraag);
            $antwoord->setProfiel($profiel);
            $antwoord->setBeschrijving($tekst);
            $antwoord->setUpvotes(0);
            $antwoord->setDownvotes(0);
            $em->persist($antwoord);

            // Notificatie sturen naar vraagsteller (niet naar jezelf)
            $vraagsteller = $vraag->getProfiel();
            if ($vraagsteller && $vraagsteller->getId() !== $profiel->getId()) {
                $notificatie = new Notificatie();
                $notificatie->setProfiel($vraagsteller);
                $notificatie->setVraag($vraag);
                $notificatie->setBericht($profiel->getName() . ' heeft een antwoord geplaatst op jouw vraag "' . $vraag->getTitel() . '"');
                $notificatie->setAangemaaktOp(new \DateTime());
                $em->persist($notificatie);
            }

            $em->flush();
        }

        return $this->redirectToRoute('anwser-question', ['id' => $id]);
    }
}



