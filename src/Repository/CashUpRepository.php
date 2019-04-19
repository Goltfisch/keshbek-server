<?php

namespace App\Repository;

use App\Entity\CashUp;
use App\Entity\State;
use App\Repository\StateRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method CashUp|null find($id, $lockMode = null, $lockVersion = null)
 * @method CashUp|null findOneBy(array $criteria, array $orderBy = null)
 * @method CashUp[]    findAll()
 * @method CashUp[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CashUpRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, CashUp::class);
    }

    public function getNewCashUpIds()
    {
        $openState = $this->getEntityManager()->getRepository(State::class)->findOneBy(['id' => StateRepository::STATE_OPEN_ID]);
        $cashUps = $this->findBy(['state' => $openState]);

        $newCachUpIds = [];

        foreach ($cashUps as $cashUp) {
            $newCachUpIds[] = $cashUp->getId();
        }

        $newCachUpIds = array_filter($newCachUpIds);

        return $newCachUpIds;
    }

    public function getDueCashUpIds()
    {
        // returns ids of all due cash ups
    }
}
