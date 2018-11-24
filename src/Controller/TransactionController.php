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
    * @Route("/transaction", methods="GET")
    */
    public function index(Request $request, TransactionRepository $transactionRepository)
    {
        $userId = (int) $request->get('userId');

        $userTransactions = $transactionRepository->getTransactionsByUserId($userId);

        return $this->respond($userTransactions);
    }

    /**
    * @Route("/transaction/{id}/", methods="GET")
    */
    public function showTransaction(Request $request, TransactionRepository $transactionRepository)
    {
        $transactionId = (int) $request->get('id');
        $userId = (int) $request->get('userId');

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

        // persist the new Transaction
        $transaction = new Transaction();
        $transaction->setCreditorId($request->get('creditorId'));
        $transaction->setDebitorId($request->get('debitorId'));
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
    * @Route("/transaction/{id}", methods="PUT")
    */
    public function update(Request $request, TransactionRepository $transactionRepository, EntityManagerInterface $em)
    {
        $stateId = (int) $request->get('id');
        $transactionId = (int) $request->get('transactionId');

        $transactionDate = \DateTime::createFromFormat('Y-m-d H:i:s', $request->get('transactionDate'));
        $state = $em->getRepository('App\Entity\State')->findOneBy(['id' => $stateId]);

        // persist the new Transaction
        $transaction = $transactionRepository->findOneBy(['id' => $transactionId]);
        $transaction->setCreditorId($request->get('creditorId'));
        $transaction->setDebitorId($request->get('debitorId'));
        $transaction->setAmount($request->get('amount'));
        $transaction->setReason($request->get('reason'));
        $transaction->setTransactionDate($transactionDate);
        $transaction->setStateId($stateId);
        $transaction->setState($state);

        $em->persist($transaction);
        $em->flush();

        return $this->respondCreated($transactionRepository->transform($transaction));
    }

    /**
     * @Route("/transaction/{id}", methods="DELETE")
     */
    public function delete(Request $request, TransactionRepository $transactionRepository, EntityManagerInterface $em)
    {
        $transactionId = (int) $request->get('id');
        $transaction = $transactionRepository->findOneBy(['id' => $transactionId]);

        $em->remove($transaction);
        $em->flush();

        return 'user deleted';
    }

}
