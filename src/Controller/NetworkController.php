<?php

namespace App\Controller;

use App\Entity\Profiel;
use App\Entity\Tags;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NetworkController extends AbstractController
{
    #[Route('/network', name: 'network')]
    public function index(EntityManagerInterface $em): Response
    {
        $profielen = $em->getRepository(Profiel::class)
            ->createQueryBuilder('p')
            ->leftJoin('p.tags', 't')
            ->addSelect('t')
            ->orderBy('p.name', 'ASC')
            ->getQuery()
            ->getResult();

        $tags = $em->getRepository(Tags::class)->findAll();

        return $this->render('network.html.twig', [
            'profielen' => $profielen,
            'tags' => $tags,
        ]);
    }
}
