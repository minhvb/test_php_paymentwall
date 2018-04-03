<?php
/**
 * Created by PhpStorm.
 */

namespace AppBundle\Command;

use AppBundle\Entity\Feed;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class ListFeedCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('minhvb:feed:list')
            ->setDescription('Dump all mookeen config')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $em = $this->getContainer()->get('doctrine')->getEntityManager();

        $feeds = $em->getRepository(Feed::class)->findAll();

        $table = new Table($output);
        $table->setHeaders(array('Id', 'Title', 'Date'));

        foreach ($feeds as $key => $feed) {
            $table->addRow(array($key + 1, $feed->getTitle(), $feed->getUrl()));
        }

        $table->render();
    }
}