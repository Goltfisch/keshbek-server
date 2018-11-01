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

    public function getTransactionsByUserId($userId)
    {
        return $this->createQueryBuilder('transaction')
            ->where('transaction.creditorId = :userId')
            ->orWhere('transaction.debitorId = :userId')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getArrayResult();
    }

    public function transform(Transaction $transaction)
    {
        return [
                'id'    => (int) $transaction->getId(),
                'creditorId' => (int) $transaction->getCreditorId(),
                'debitorId' => (int) $transaction->getDebitorId(),
                'amount' => (int) $transaction->getAmount(),
                'reason' => (string) $transaction->getReason(),
                'transactionDate' => $transaction->getTransactionDate(),
                'stateId' => (int) $transaction->getStateId(),
                'state' => (string) $transaction->getState()->getLabel()
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
