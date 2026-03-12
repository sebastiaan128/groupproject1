<?php

namespace App\Controller;

use App\Entity\Profiel;
use App\Entity\Tags;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NetworkController extends AbstractController
{
    private const PER_PAGE = 12;

    #[Route('/network', name: 'network')]
    public function index(EntityManagerInterface $em): Response
    {
        $tags = $em->getRepository(Tags::class)->findAll();

        return $this->render('network.html.twig', [
            'tags' => $tags,
        ]);
    }

    #[Route('/network/load', name: 'network_load')]
    public function load(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $page   = max(1, (int) $request->query->get('page', 1));
        $search = trim($request->query->get('search', ''));
        $tag    = trim($request->query->get('tag', ''));

        $idQb = $em->getRepository(Profiel::class)
            ->createQueryBuilder('p')
            ->select('p.id')
            ->leftJoin('p.tags', 't')
            ->orderBy('p.name', 'ASC')
            ->setFirstResult(($page - 1) * self::PER_PAGE)
            ->setMaxResults(self::PER_PAGE);

        if ($search !== '') {
            $idQb->andWhere('LOWER(p.name) LIKE :search')->setParameter('search', '%' . strtolower($search) . '%');
        }
        if ($tag !== '') {
            $idQb->andWhere('t.naam = :tag')->setParameter('tag', $tag);
        }

        $ids = array_column($idQb->getQuery()->getScalarResult(), 'id');

        $profielen = empty($ids) ? [] : $em->getRepository(Profiel::class)
            ->createQueryBuilder('p')
            ->leftJoin('p.tags', 't')
            ->addSelect('t')
            ->where('p.id IN (:ids)')
            ->setParameter('ids', $ids)
            ->orderBy('p.name', 'ASC')
            ->getQuery()
            ->getResult();

        $countQb = $em->getRepository(Profiel::class)
            ->createQueryBuilder('p')
            ->select('COUNT(DISTINCT p.id)')
            ->leftJoin('p.tags', 't');

        if ($search !== '') {
            $countQb->andWhere('LOWER(p.name) LIKE :search')->setParameter('search', '%' . strtolower($search) . '%');
        }
        if ($tag !== '') {
            $countQb->andWhere('t.naam = :tag')->setParameter('tag', $tag);
        }

        $total = (int) $countQb->getQuery()->getSingleScalarResult();

        $data = array_map(function (Profiel $p) {
            return [
                'name'  => $p->getName(),
                'email' => $p->getEmail(),
                'bio'   => $p->getBio(),
                'tags'  => array_map(fn($t) => $t->getNaam(), $p->getTags()->toArray()),
            ];
        }, $profielen);

        return $this->json([
            'profielen' => $data,
            'total'     => $total,
            'hasMore'   => ($page * self::PER_PAGE) < $total,
        ]);
    }
}
