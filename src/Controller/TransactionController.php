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
    public function index(TransactionRepository $transactionRepository)
    {
        if (! $this->isAuthorized()) {
            return $this->respondUnauthorized();
        }
        // $movies = $movieRepository->transformAll();
        // return $this->respond($movies);
    }

    /**
    * @Route("/transaction", methods="POST")
    */
    public function create(Request $request, TransactionRepository $transactionRepository, EntityManagerInterface $em)
    {
        $stateId = (int) $request->get('stateId');

        // $transactionDate = \DateTime::createFromFormat('Y-m-d H:i:s', '2013-08-14 11:45:45');
        $transactionDate = \DateTime::createFromFormat('Y-m-d H:i:s', $request->get('transactionDate'));
        $state = $em->getRepository('App\Entity\State')->findOneBy(['id' => $stateId]);

        // persist the new Transaction
        $transaction = new Transaction;
        $transaction->setCreditorId($request->get('creditorId'));
        $transaction->setDebitorId($request->get('debitorId'));
        $transaction->setAmount($request->get('amount'));
        $transaction->setReason($request->get('reason'));
        $transaction->setTransactionDate($transactionDate);
        $transaction->setCreatedAt();
        $transaction->setStateId($stateId);
        $transaction->setState($state);

        $em->persist($transaction);
        $em->flush();

        return $this->respondCreated($transactionRepository->transform($transaction));
    }

}
