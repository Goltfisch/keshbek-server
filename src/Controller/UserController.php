<?php
namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends ApiController
{
    /**
    * @Route("/api/user", methods="GET")
    */
    public function showUser(Request $request, UserRepository $userRepository)
    {
        $userId = (int) $this->getUser()->getId();

        if (!$userId) {
            return;
        }

        $user = $userRepository->findOneBy(['id' => $userId]);

        if (!$user) {
            return;
        }

        return $this->respondCreated($userRepository->transform($user));
    }

    /**
    * @Route("/api/user", methods="PUT")
    */
    public function update(Request $request, UserRepository $userRepository, EntityManagerInterface $em)
    {
        $userId = (int) $this->getUser()->getId();

        if (!$userId) {
            return;
        }
        
        $user = $userRepository->findOneBy(['id' => $userId]);

        if (!$user) {
            return;
        }

        $request = $this->transformJsonBody($request);

        $user->setFirstname($request->get('firstname'));
        $user->setLastname($request->get('lastname'));
        $user->setEmail($request->get('email'));
        $user->setPayPalMeLink($request->get('paypalMeLink'));
        $user->setAvatarLink($request->get('avatarLink'));

        $em->persist($user);
        $em->flush();

        return $this->respondCreated($userRepository->transform($user));
    }
}
