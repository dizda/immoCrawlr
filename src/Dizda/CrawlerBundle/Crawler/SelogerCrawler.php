<?php
namespace Dizda\CrawlerBundle\Crawler;

use Dizda\CrawlerBundle\Crawler\AbstractCrawler;
use Symfony\Component\Console\Output\ConsoleOutput;
use JMS\DiExtraBundle\Annotation as DI;


/**
 * Class SelogerAbstract
 *
 * @package Dizda\CrawlerBundle\Crawler
 * @DI\Service("crawler.seloger")
 */
class SelogerCrawler extends AbstractCrawler
{
    static protected $documentClass = 'Dizda\CrawlerBundle\Document\Seloger';

    protected $params = ['idtt'             => '1',
                         'ci'               => '750101,750102,750103,750104,750105,750109,750109,750111',
                         'idtypebien'       => '1,2',
                         'nb_pieces'        => '3',
                         'nb_chambres'      => '2',
                         'px_loyermin'      => '1000',
                         'px_loyermax'      => '1400',
                         'surfacemin'       => '55',
                         'tri'              => 'd_dt_crea',
                         'getDtCreationMax' => '1' ];
    //$query->add('pg', '22'); // pas bonne pagination..


    public function execute()
    {
        $xml = parent::getAccommodationsList();

        var_dump($xml);

        if (parent::saveAccomodationsList($xml->annonces->annonce))
        {

        }
    }

}