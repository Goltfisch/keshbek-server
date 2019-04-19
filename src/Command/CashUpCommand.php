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
use App\Service\CashUpHelper;
use App\Service\CashUpMailer;

class CashUpCommand extends Command
{
    const STATE_OPEN_ID = 1;

    protected static $defaultName = 'keshbek:cash-up';

    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(
        EntityManagerInterface $entityManager,
        CashUpHelper $cashUpHelper,
        CashUpMailer $cashUpMailer
    ) {
        $this->entityManager = $entityManager;
        $this->cashUpHelper = $cashUpHelper;
        $this->cashUpMailer = $cashUpMailer;

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
            'Start of cash-up process',
            '============',
            '',
        ]);

        $transactionRepository = $this->entityManager->getRepository(Transaction::class);
        $cashUpRepository = $this->entityManager->getRepository(CashUp::class);

        $unassignedTransactionIds = $transactionRepository->getUnassignedTransactionIds();

        if ($unassignedTransactionIds) {
            $this->cashUpHelper->createFromTransactionIds($unassignedTransactionIds);
        } else {
            $output->writeln([
                'No open transactions.',
                '',
            ]);
        }

        $newCashUpIds = $cashUpRepository->getNewCashUpIds();
        $debitors = $this->cashUpHelper->prepareDebitors($newCashUpIds);

        foreach ($debitors as $debitorData) {
            $this->cashUpMailer->sendMail($debitorData, CashUpMailer::TEMPLATE_NEW);
        }

        $output->writeln([
            '============',
            'End cash-up process',
        ]);
    }
}
