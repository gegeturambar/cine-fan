<?php

namespace AppBundle\Command;


use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

use Symfony\Component\Console\Helper\DialogHelper;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;


class DeleteMoviesCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('app:movies:delete')
            ->setDescription('delete movie(s) by date')
            ->setHelp('this command allow you to delete movies by date')
            ->addArgument('year', InputArgument::REQUIRED,'minimum year')
            ->addOption('earlier','a',InputOption::VALUE_NONE,'delete movies earlier than the date given in input')
        ;

    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $year = $input->getArgument('year');
        $earlier = $input->getOption('earlier');
        $doctrine = $this->getContainer()->get('doctrine');
        $em = $doctrine->getManager();
        $rc = $doctrine->getRepository('AppBundle:Movie');
        $count = $rc->deleteMoviesByYear($year,!$earlier);
        $output->writeln("You have removed all movies ".($earlier ? "earlier" : "older" )." than $year wich is exactly $count movies" );
    }


}
