<?php

namespace App\Command;

use App\Repository\OrderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class RemoveExpiredCartsCommand extends Command
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * @var OrderRepository
     */
    private $orderRepository;

    protected static $defaultName = 'app:remove-expired-carts';

    /**
     * RemoveExpiredCartsCommand constructor.
     */
    public function __construct(EntityManagerInterface $entityManager, OrderRepository $orderRepository)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
        $this->orderRepository = $orderRepository;
    }

    protected function configure()
    {
        $this
            ->setDescription('Retirer les paniers inactifs depuis une longue période')
            ->addArgument(
                'jours',
                InputArgument::OPTIONAL,
                'Nombre de jours où un panier peut rester inactif avant d\'être supprimé',
                2
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $days = $input->getArgument('days');

        if ($days <= 0) {
            $io->error('Le nombre de jours doit être supérieur à 0.');

            return Command::FAILURE;
        }

        // Subtracts the number of days from the current date.
        $limitDate = new \DateTime("- $days jours");
        $expiredCartsCount = 0;

        while ($carts = $this->orderRepository->findCartsNotModifiedSince($limitDate)) {
            foreach ($carts as $cart) {
                // Items will be deleted on cascade
                $this->entityManager->remove($cart);
            }

            $this->entityManager->flush(); // Executes all deletions
            $this->entityManager->clear(); // Detaches all object from Doctrine

            $expiredCartsCount += count($carts);
        }

        if ($expiredCartsCount) {
            $io->success("$expiredCartsCount panier(s) ont été supprimé(s).");
        } else {
            $io->info('Aucun panier à supprimer');
        }

        return Command::SUCCESS;
    }
}