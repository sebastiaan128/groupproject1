<?php

namespace App\Controller;

use App\Repository\NotificatieRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class NotificatieController extends AbstractController
{
    #[Route('/notificaties/count', name: 'notificaties_count', methods: ['GET'])]
    public function count(Request $request, NotificatieRepository $repo): JsonResponse
    {
        $profielId = $request->getSession()->get('profiel_id');
        if (!$profielId) {
            return new JsonResponse(['count' => 0, 'items' => []]);
        }

        $notificaties = $repo->createQueryBuilder('n')
            ->join('n.profiel', 'p')
            ->join('n.vraag', 'v')
            ->where('p.id = :pid')
            ->andWhere('n.isGelezen = false')
            ->setParameter('pid', $profielId)
            ->orderBy('n.aangemaaktOp', 'DESC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();

        $items = array_map(fn($n) => [
            'id'      => $n->getId(),
            'bericht' => $n->getBericht(),
            'vraagId' => $n->getVraag()->getId(),
            'tijd'    => $n->getAangemaaktOp()->format('d M H:i'),
        ], $notificaties);

        return new JsonResponse(['count' => count($notificaties), 'items' => $items]);
    }

    #[Route('/notificaties/lees/{id}', name: 'notificatie_lees', methods: ['POST'])]
    public function markRead(int $id, Request $request, NotificatieRepository $repo, EntityManagerInterface $em): JsonResponse
    {
        $profielId = $request->getSession()->get('profiel_id');
        $notificatie = $repo->find($id);

        if ($notificatie && $notificatie->getProfiel()->getId() === $profielId) {
            $notificatie->setIsGelezen(true);
            $em->flush();
        }

        return new JsonResponse(['ok' => true]);
    }

    #[Route('/notificaties/lees-alles', name: 'notificaties_lees_alles', methods: ['POST'])]
    public function markAllRead(Request $request, NotificatieRepository $repo, EntityManagerInterface $em): JsonResponse
    {
        $profielId = $request->getSession()->get('profiel_id');
        if (!$profielId) return new JsonResponse(['ok' => false]);

        $notificaties = $repo->createQueryBuilder('n')
            ->join('n.profiel', 'p')
            ->where('p.id = :pid')
            ->andWhere('n.isGelezen = false')
            ->setParameter('pid', $profielId)
            ->getQuery()
            ->getResult();

        foreach ($notificaties as $n) {
            $n->setIsGelezen(true);
        }
        $em->flush();

        return new JsonResponse(['ok' => true]);
    }
}
