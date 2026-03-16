<?php

namespace App\Controller;

use App\Entity\Profiel;
use App\Repository\ProfielRepository;
use Doctrine\ORM\EntityManagerInterface;
use Faker\Factory as FakerFactory;
use Kreait\Firebase\Factory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthController extends AbstractController
{
    #[Route('/auth/verify', name: 'auth_verify', methods: ['POST'])]
    public function verify(
        Request $request,
        ProfielRepository $profielRepository,
        EntityManagerInterface $em
    ): Response {
        $idToken = $request->request->get('idToken');

        try {
            $firebase = (new Factory)
                ->withServiceAccount($_ENV['FIREBASE_CREDENTIALS_PATH']);

            $verifiedToken = $firebase->createAuth()->verifyIdToken($idToken);

            $uid   = $verifiedToken->claims()->get('sub');
            $email = $verifiedToken->claims()->get('email') ?? '';
            $name  = $verifiedToken->claims()->get('name') ?? '';

            $profiel = $profielRepository->findOneBy(['firebaseUid' => $uid]);

            if (!$profiel && $email) {
                $profiel = $profielRepository->findOneBy(['email' => $email]);
                if ($profiel) {
                    $profiel->setFirebaseUid($uid);
                }
            }

            if (!$profiel) {
                $profiel = new Profiel();
                $profiel->setFirebaseUid($uid);
                $profiel->setCreatedAt(new \DateTime());
                $profiel->setEmail($email);
                $profiel->setName($name ?: $email);
                $profiel->setBio('');
                $profiel->setJaar(1);
                $em->persist($profiel);
            } else {
                $profiel->setFirebaseUid($uid);
                $profiel->setEmail($email);
                if ($name) $profiel->setName($name);
            }

            $em->flush();

            $session = $request->getSession();
            $session->set('user_uid',   $uid);
            $session->set('user_email', $email);
            $session->set('user_name',  $name);
            $session->set('profiel_id', $profiel->getId());
            return $this->redirectToRoute('home');
        } catch (\Throwable $e) {
            return $this->redirectToRoute('login', ['error' => 'verify_failed']);
        }
    }

    #[Route('/auth/guest', name: 'auth_guest', methods: ['GET'])]
    public function guest(
        Request $request,
        ProfielRepository $profielRepository,
        EntityManagerInterface $em
    ): Response {
        $faker = FakerFactory::create('nl_NL');

        $guestUid = 'guest_' . bin2hex(random_bytes(8));

        $studies = ['HBO-ICT', 'Business IT & Management', 'Communication & Multimedia Design', 'Applied Data Science'];

        $profiel = new Profiel();
        $profiel->setFirebaseUid($guestUid);
        $profiel->setName('Guest');
        $profiel->setEmail($faker->safeEmail());
        $profiel->setStudie($faker->randomElement($studies));
        $profiel->setJaar($faker->numberBetween(1, 4));
        $profiel->setBio($faker->sentence(12));
        $profiel->setCreatedAt(new \DateTime());
        $em->persist($profiel);
        $em->flush();

        $session = $request->getSession();
        $session->set('user_uid',   $guestUid);
        $session->set('user_email', $profiel->getEmail());
        $session->set('user_name',  'Guest');
        $session->set('profiel_id', $profiel->getId());
        $session->set('is_guest',   true);

        return $this->redirectToRoute('home');
    }

    #[Route('/logout', name: 'logout')]
    public function logout(Request $request): Response
    {
        $request->getSession()->invalidate();
        return $this->redirectToRoute('login');
    }
}
