<?php
/**
 * Created by PhpStorm.
 */

namespace AppBundle\Command;

use AppBundle\Entity\Feed;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;


class ReadFeedCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('minhvb:feed:read')
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
            return;
        }

        $curl_handle=curl_init();
        curl_setopt($curl_handle, CURLOPT_URL, $feed->getUrl());
        curl_setopt($curl_handle, CURLOPT_CONNECTTIMEOUT, 2);
        curl_setopt($curl_handle, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl_handle, CURLOPT_USERAGENT, 'Your application name');
        $feed = curl_exec($curl_handle);
        curl_close($curl_handle);
        $xml = simplexml_load_string($feed);

        if ($xml) {
            $items = $xml->channel->item;
            $table = new Table($output);
            $table->setHeaders(array('Id', 'Title', 'Date'));
            $index = 1;
            foreach ($items as $key => $item) {
                $table->addRow(array($index, $item->title, $item->pubDate));
                $index++;
            }

            $table->render();
        } else {
            echo "Invalid URL \n";
        }
    }
}
