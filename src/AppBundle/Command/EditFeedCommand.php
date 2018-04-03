<?php
/**
 * Created by PhpStorm.
 */

namespace AppBundle\Command;

use AppBundle\Entity\Feed;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;


class EditFeedCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('minhvb:feed:edit')
            ->setDescription('Dump all mookeen config')
            ->addArgument('id', InputArgument::REQUIRED, 'Feed ID')
            ->addOption(
                'title',
                null,
                InputOption::VALUE_REQUIRED,
                'New feed title',
                ''
            )
            ->addOption(
                'url',
                null,
                InputOption::VALUE_REQUIRED,
                'New feed title',
                ''
            )
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

        if ($title = $input->getOption('title')) {
            $feed->setTitle($title);
        }

        if ($url = $input->getOption('url')) {
            $feed->setUrl($url);
        }

        $em->persist($feed);
        $em->flush();

        echo "Edit feed successfully\n";
    }
}