<?php
namespace Dizda\CrawlerBundle\Crawler;

use Dizda\CrawlerBundle\Crawler\AbstractCrawler;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * Class SelogerCrawler
 *
 * @package Dizda\CrawlerBundle\Crawler
 * @DI\Service("crawler.seloger")
 */
class SelogerCrawler extends AbstractCrawler
{
    static protected $documentClass = 'Dizda\CrawlerBundle\Document\Seloger';

    protected $followPagination = true;
    protected $annoncesNode     = '//annonce'; // XPath
    protected $nextPageNode     = '/recherche/pageSuivante'; // XPath
    protected $maxPageNode      = '/recherche/pageMax';      // XPath
    protected $currentPageNode  = '/recherche/pageCourante'; // XPath

    protected $params = ['idtt'             => '1',
                         'ci'               => '750101,750102,750103,750104,750105,750109,750109,750111',
                         'idtypebien'       => '1,2',
                         'nb_pieces'        => '3',
                         'nb_chambres'      => '2',
                         'px_loyermin'      => '1000',
                         'px_loyermax'      => '1800',
                         'surfacemin'       => '55',
                         'tri'              => 'd_dt_crea',
                         'getDtCreationMax' => '1',
                         //'SEARCHpg'         => '1'
    ];


    /**
     * {@inheritdoc}
     */
    public function execute($progress)
    {
        $this->progress = $progress;
        parent::getAccommodationsList();
    }

    protected function getNode($response)
    {
        return $response['annonces'];
    }

    protected function getDetailNode($response)
    {
        //return json_encode($response);
    }

    protected function getPhotoNode($response)
    {
        return null;
    }

}