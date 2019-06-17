<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\DBAL\Connection;
use App\Entity\CashUp;
use App\Entity\Transaction;
use App\Entity\User;
use App\Entity\State;
use App\Repository\StateRepository;

class CashUpHelper
{
    /**
     * @var EntityManagerInterface $entityManager
     */
    private $entityManager;

    /**
     * @var Connection $connection
     */
    private $connection;

    /**
     * Constructor of method.
     *
     * @param EntityManagerInterface $entityManager
     * @param Connection             $connection
     *
     * @return void
     */
    public function __construct(
        EntityManagerInterface $entityManager,
        Connection $connection
    ) {
        $this->entityManager = $entityManager;
        $this->connection = $connection;
    }

    /**
     * Creates all cashups from the provided transaction-ids
     *
     * @param array $transactionIds
     *
     * @return void
     */
    public function createFromTransactionIds(array $transactionIds)
    {
        if (!$transactionIds) {
            return;
        }

        $transactionRepository = $this->entityManager->getRepository(Transaction::class);
        $userRepository = $this->entityManager->getRepository(User::class);
        $stateRepository = $this->entityManager->getRepository(State::class);

        $transactions = $this->connection->createQueryBuilder()
            ->select('*')
            ->from('transaction', 't')
            ->where('t.id IN (:transactionIds)')
            ->setParameter('transactionIds', $transactionIds, Connection::PARAM_INT_ARRAY)
            ->execute()
            ->fetchAll();

        $debitors = [];

        foreach ($transactions as $transaction) {
            if (!$transaction['debitorId'] || !$transaction['creditorId'] || !$transaction['id']) {
                continue;
            }

            $debitors[$transaction['debitorId']][$transaction['creditorId']][] = $transaction['id'];
        }

        $state = $stateRepository->findOneBy(['id' => StateRepository::STATE_OPEN_ID]);

        foreach ($debitors as $debitorId => $creditors) {
            $debitor = $userRepository->findOneBy(['id' => $debitorId]);

            if (!$debitor) {
                continue;
            }

            foreach ($creditors as $creditorId => $transactionIds) {
                $creditor = $userRepository->findOneBy(['id' => $creditorId]);

                if (!$creditor) {
                    continue;
                }

                $cashUp = new CashUp();

                $cashUp->setDebitor($debitor);
                $cashUp->setCreditor($creditor);
                $cashUp->setState($state);
                $cashUp->setCreatedAt();

                $this->entityManager->persist($cashUp);
                $this->entityManager->flush();

                foreach ($transactionIds as $transactionId) {
                    $transaction = $transactionRepository->findOneBy(['id' => $transactionId]);

                    if (!$transaction) {
                        continue;
                    }

                    $cashUp->addTransaction($transaction);
                    $this->entityManager->flush();
                }
            }
        }
    }

    public function prepareDebitors(array $cashUpIds)
    {
        $debitors = [];

        if (!$cashUpIds) {
            return $debitors;
        }

        $cashUps = $this->connection->createQueryBuilder()
            ->select([
                'c.id as cashUpId',
                'c.debitor_id as debitorId',
                'c.creditor_id as creditorId',

                //debitor data
                'debitor.firstname as debitorFirstname',
                'debitor.lastname as debitorLastname',
                'debitor.email as debitorEmail',

                //creditor data
                'creditor.firstname as creditorFirstname',
                'creditor.lastname as creditorLastname',
                'creditor.paypal_me_link as creditorPaypalMeLink'
            ])
            ->from('cash_up', 'c')
            ->leftJoin('c', 'user', 'debitor', 'debitor.id = c.debitor_id')
            ->leftJoin('c', 'user', 'creditor', 'creditor.id = c.creditor_id')
            ->where('c.id IN (:cashUpIds)')
            ->setParameter('cashUpIds', $cashUpIds, Connection::PARAM_INT_ARRAY)
            ->execute()
            ->fetchAll();

        foreach ($cashUps as $cashUp) {
            if (!$cashUp['debitorId'] || !$cashUp['creditorId']) {
                continue;
            }

            $debitors[$cashUp['debitorId']]['debitorData'] = [
                'firstname' => $cashUp['debitorFirstname'],
                'lastname' => $cashUp['debitorLastname'],
                'email' => $cashUp['debitorEmail']
            ];

            $amount = $this->connection->createQueryBuilder()
                ->select('SUM(t.amount)')
                ->from('transaction', 't')
                ->where('t.cash_up_id = :cashUpId')
                ->setParameter('cashUpId', $cashUp['cashUpId'])
                ->execute()
                ->fetch(\PDO::FETCH_COLUMN);

            $debitors[$cashUp['debitorId']]['creditors'][$cashUp['creditorId']] = [
                'firstname' => $cashUp['creditorFirstname'],
                'lastname' => $cashUp['creditorLastname'],
                'paypalMeLink' => $cashUp['creditorPaypalMeLink'],
                'amount' => $amount,
                'cashUpId' => $cashUp['cashUpId']
            ];
        }

        $debitors = array_filter($debitors);

        return $debitors;
    }
}
