<?php
namespace Dizda\CrawlerBundle\Crawler;

use Dizda\CrawlerBundle\Crawler\AbstractCrawler;
use Symfony\Component\Console\Output\ConsoleOutput;

use Guzzle\Http\Client;
use Guzzle\Http\Message\Request;

/**
 * Class SelogerAbstract
 *
 * @package Dizda\CrawlerBundle\Crawler
 */
class SelogerCrawler extends AbstractCrawler
{
    protected $documentClass = 'Dizda\CrawlerBundle\Document\Seloger';


    public function execute()
    {
        $xml = $this->getAccommodationsList();

        return $xml;
    }

}