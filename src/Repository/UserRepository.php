<?php

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, User::class);
    }

    public function transform(User $user)
    {
        return [
            'id' => (int) $user->getId(),
            'email' => (string) $user->getEmail(),
            'firstname' => (string) $user->getFirstname(),
            'lastname' => (string) $user->getLastname(),
            'paypalMeLink' => (string) $user->getPayPalMeLink(),
            'avatarLink' => (string) $user->getAvatarLink()
        ];
    }
}
