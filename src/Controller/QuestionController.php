<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\VragenRepository;


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
}



