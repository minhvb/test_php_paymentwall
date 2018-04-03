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


class DeleteFeedCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('minhvb:feed:delete')
            ->setDescription('Dump all mookeen config')
            ->addArgument('id', InputArgument::REQUIRED, 'Feed ID')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getEntityManager();

        $feed = $em->getRepository(Feed::class)->find($input->getArgument('id'));

        if (!$feed) {
            echo "No feed found with ID: {$input->getArgument('id')} \n";
            return false;
        }

        $em->remove($feed);
        $em->flush();

        echo "Delete feed successfully\n";
    }
}