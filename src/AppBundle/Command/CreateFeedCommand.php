<?php
/**
 * Created by PhpStorm.
 */

namespace AppBundle\Command;

use AppBundle\Entity\Feed;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class CreateFeedCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('minhvb:feed:create')
            ->setDescription('Dump all mookeen config')
            ->addArgument('title', InputArgument::REQUIRED, 'Feed Title')
            ->addArgument('url', InputArgument::REQUIRED, 'Feed URL')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getEntityManager();

        $feed = new Feed();
        $feed->setTitle($input->getArgument('title'));
        $feed->setUrl($input->getArgument('url'));

        $em->persist($feed);
        $em->flush();

        echo "Create new feed successfully\n";
    }
}