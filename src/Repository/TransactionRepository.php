<?php

namespace App\Repository;

use App\Entity\Transaction;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Transaction|null find($id, $lockMode = null, $lockVersion = null)
 * @method Transaction|null findOneBy(array $criteria, array $orderBy = null)
 * @method Transaction[]    findAll()
 * @method Transaction[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TransactionRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Transaction::class);
    }

//    /**
//     * @return Transaction[] Returns an array of Transaction objects
//     */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Transaction
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function transform(Transaction $transaction)
    {
        return [
                'id'    => (int) $transaction->getId(),
                'reditorId' => (int) $transaction->getCreditorId(),
                'debitorId' => (int) $transaction->getDebitorId(),
                'amount' => (int) $transaction->getAmount(),
                'reason' => (string) $transaction->getReason(),
                'transactionDate' => $transaction->getTransactionDate(),
                'createdAt' => $transaction->getCreatedAt(),
                'stateId' => (int) $transaction->getStateId(),
                'state' => $transaction->getState()
        ];
    }

    public function transformAll()
    {
     $transactions = $this->findAll();
     $transactionsArray = [];
     foreach ($transactions as $transaction) {
         $transactionsArray[] = $this->transform($transaction);
     }
     return $transactionsArray;
    }
}
