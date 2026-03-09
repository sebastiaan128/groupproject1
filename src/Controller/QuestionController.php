<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuestionController extends AbstractController
{
    #[Route('/questions', name: 'questions')]
    public function questions(): Response
    {
        return $this->render('questions.html.twig');
    }
    

    #[Route('/create-question', name: 'create-question')]
    public function createQuestion(): Response
    {
        return $this->render('create-question.html.twig');
    }
}
