<?php

namespace App\Controller;

use App\Entity\Profiel;
use App\Entity\Tags;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NetwerkController extends AbstractController
{
    #[Route('/netwerk', name: 'netwerk')]
    public function index(EntityManagerInterface $em): Response
    {
        $profielRepo = $em->getRepository(Profiel::class);
        $profielen = $profielRepo->createQueryBuilder('p')
            ->leftJoin('p.tags', 't')
            ->addSelect('t')
            ->orderBy('p.name', 'ASC')
            ->getQuery()
            ->getResult();

        $tagRepo = $em->getRepository(Tags::class);
        $tags = $tagRepo->findAll();

        return $this->render('netwerk.html.twig', [
            'profielen' => $profielen,
            'tags' => $tags,
        ]);
    }
}
