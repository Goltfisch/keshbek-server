<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Transaction;
use App\Repository\TransactionRepository;
use App\Entity\CashUp;
use App\Entity\User;
use App\Entity\State;

class CashUpCommand extends Command
{
    protected static $defaultName = 'keshbek:cash-up';

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;

        parent::__construct();
    }

    protected function configure()
    {
        $this
            ->setDescription('Creates a cash-up')
            ->setHelp('This command allows you to create a cash-up')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln([
            'Start of cash-up',
            '============',
            '',
        ]);

        $transactionRepository = $this->entityManager->getRepository(Transaction::class);

        if (!$transactionRepository) {
            $output->writeln('Transaction-Repository not found!');
            return;
        }

        $openTransactions = $transactionRepository->findBy(['cashUp' => null]);

        if (!$openTransactions) {
            $output->writeln('No open transactions.');
            return;
        }

        $openTransactionsArray = [];
        foreach ($openTransactions as $transaction) {
            $openTransactionsArray[] = $transactionRepository->transform($transaction);
        }

        $debitors = [];

        foreach ($openTransactionsArray as $openTransaction) {
            $debitors[$openTransaction['debitorId']][$openTransaction['creditorId']][] = $openTransaction['id'];
        }

        $userRepository = $this->entityManager->getRepository(User::class);
        $stateRepository = $this->entityManager->getRepository(State::class);

        // TODO: Implement a cleaner way
        $state = $stateRepository->findOneBy(['id' => 1]);

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
                }
            }
        }

        $output->writeln('Cashups succesfully created!');

        $output->writeln([
            '',
            '============',
            'End cash-up',
        ]);

        //Für jede debitor-kreditor Verknüpfung einen neuen Kassensturz erstellen; Status wird auf “Offen” gestellt

        //alle kassenstürze für einen Debitor zusammenfassen und in eine Email packen

        //Kassensturz-Status wird auf “Aussenstehend” gestellt
    }
}
