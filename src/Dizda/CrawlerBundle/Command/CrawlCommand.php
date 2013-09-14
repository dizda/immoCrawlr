<?php
namespace Dizda\CrawlerBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Guzzle\Http\Client;
use Guzzle\Http\Message\Request;


class CrawlCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('dizda:crawl:go')
            ->setDescription('Crawling a gogo');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        //$this->getContainer()->get('crawler.seloger')->execute($this->getHelperSet()->get('progress'));
        $this->getContainer()->get('crawler.explorimmo')->execute($this->getHelperSet()->get('progress'));
        $this->getContainer()->get('crawler.pap')->execute($this->getHelperSet()->get('progress'));
    }

}