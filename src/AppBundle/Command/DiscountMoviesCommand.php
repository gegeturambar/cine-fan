<?php

namespace AppBundle\Command;


use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

use Symfony\Component\Console\Helper\DialogHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;


class DiscountMoviesCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('app:movies:discount')
            ->setDescription('update movie(s) reducing price')
            ->setHelp('this command allow you to update movies price')
            ->addArgument('discountRate', InputArgument::OPTIONAL,'discount rate')
        ;

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $discount_rate = (integer)$input->getArgument('discountRate');
        $discount_rate = $discount_rate ? $discount_rate : 10;
        $doctrine = $this->getContainer()->get('doctrine');
        $em = $doctrine->getManager();
        $rc = $doctrine->getRepository('AppBundle:Movie');
        $count = $rc->updateMoviesPrice($discount_rate);
        $output->writeln("You have updated all movies, reducing these prices by $discount_rate % " );
    }


}
