<?php
namespace App\Controller;

use App\Entity\Transaction;
use App\Repository\TransactionRepository;
use App\Entity\State;
use App\Repository\StateRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class TransactionController extends ApiController
{
    /**
     * @Route("/api/transaction/demo", methods="GET")
     */
    public function generateDemoData(Request $request, TransactionRepository $transactionRepository, EntityManagerInterface $em)
    {        
        $demoTransactions = [
            [
                'amount' => 100,
                'reason' => 'Essen',
            ],
            [
                'amount' => 200,
                'reason' => 'Chillen',
            ],
            [
                'amount' => 1337,
                'reason' => 'Hacken',
            ],
            [
                'amount' => 7,
                'reason' => 'Zwiebeln',
            ]
        ];

        $transactionDate = \DateTime::createFromFormat('d.m.Y', '23.03.2019');
        $state = $em->getRepository('App\Entity\State')->findOneBy(['id' => 1]);
        $creditor = $em->getRepository('App\Entity\User')->findOneBy(['id' => 1]);
        $debitor = $em->getRepository('App\Entity\User')->findOneBy(['id' => 2]);

        foreach($demoTransactions as $demoTransaction) {
            $transaction = new Transaction();
            $transaction->setCreditor($creditor);
            $transaction->setDebitor($debitor);
            $transaction->setAmount($demoTransaction['amount']);
            $transaction->setReason($demoTransaction['reason']);
            $transaction->setTransactionDate($transactionDate);
            $transaction->setCreatedAt();
            $transaction->setState($state);

            $em->persist($transaction);
            $em->flush();
        }

        return $this->respond([ 'response' => 'Demo data was created successfully.' ]);
    }

    /**
    * @Route("/api/transaction", methods="GET")
    */
    public function index(Request $request, TransactionRepository $transactionRepository)
    {
        $userId = (int) $this->getUser()->getId();

        $userTransactions = $transactionRepository->getTransactionsByUserId($userId);

        return $this->respond($userTransactions);
    }

    /**
    * @Route("/api/transaction/{id}", methods="GET")
    */
    public function showTransaction(Request $request, TransactionRepository $transactionRepository)
    {
        $transactionId = (int) $request->get('id');
        $userId = (int) $this->getUser()->getId();

        $transaction = $transactionRepository->findOneBy(['id' => $transactionId]);
        $transaction = $transactionRepository->transform($transaction);

        if($transaction['creditorId'] == $userId || $transaction['debitorId'] == $userId)
        {
            return $this->respondCreated($transaction);
        }

        return $this->respond('Not allowed');
    }

    /**
    * @Route("/transaction", methods="POST")
    */
    public function create(Request $request, TransactionRepository $transactionRepository, EntityManagerInterface $em)
    {
        $request = $this->transformJsonBody($request);

        $transactionDate = \DateTime::createFromFormat('d.m.Y', $request->get('transactionDate'));

        $state = $em->getRepository('App\Entity\State')->findOneBy(['id' => 1]);
        $creditor = $em->getRepository('App\Entity\User')->findOneBy(['id' => $request->get('creditorId')]);
        $debitor = $em->getRepository('App\Entity\User')->findOneBy(['id' => $request->get('debitorId')]);

        // persist the new Transaction
        $transaction = new Transaction();
        $transaction->setCreditor($creditor);
        $transaction->setDebitor($debitor);
        $transaction->setAmount($request->get('amount'));
        $transaction->setReason($request->get('reason'));
        $transaction->setTransactionDate($transactionDate);
        $transaction->setCreatedAt();
        $transaction->setState($state);

        $em->persist($transaction);
        $em->flush();

        return $this->respondCreated($transactionRepository->transform($transaction));
    }

    /**
    * @Route("/api/transaction/{id}", methods="PUT")
    */
    public function update(Request $request, TransactionRepository $transactionRepository, EntityManagerInterface $em)
    {
        $stateId = 1;
        $transactionId = (int) $request->get('id');

        $request = $this->transformJsonBody($request);

        $transactionDate = \DateTime::createFromFormat('d.m.Y', $request->get('transactionDate'));
        $state = $em->getRepository('App\Entity\State')->findOneBy(['id' => $stateId]);
        $creditor = $em->getRepository('App\Entity\User')->findOneBy(['id' => $request->get('creditorId')]);
        $debitor = $em->getRepository('App\Entity\User')->findOneBy(['id' => $request->get('debitorId')]);

        // persist the new Transaction
        $transaction = $transactionRepository->findOneBy(['id' => $transactionId]);
        $transaction->setCreditor($creditor);
        $transaction->setDebitor($debitor);
        $transaction->setAmount($request->get('amount'));
        $transaction->setReason($request->get('reason'));
        $transaction->setTransactionDate($transactionDate);
        $transaction->setState($state);

        $em->persist($transaction);
        $em->flush();

        return $this->respondCreated($transactionRepository->transform($transaction));
    }

    /**
     * @Route("/api/transaction/{id}", methods="DELETE")
     */
    public function delete(Request $request, TransactionRepository $transactionRepository, EntityManagerInterface $em)
    {
        $transactionId = (int) $request->get('id');
        $transaction = $transactionRepository->findOneBy(['id' => $transactionId]);

        $em->remove($transaction);
        $em->flush();

        return $this->respond([ 'response' => 'Transaction was deleted successfully.' ]);
    }
}