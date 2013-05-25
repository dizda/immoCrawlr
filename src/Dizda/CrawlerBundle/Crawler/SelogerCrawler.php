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
                         'ci'               => '750101,750102,750103,750104,750105,750106,750109,750110,750111',
                         'idtypebien'       => '1,2',
                         'nb_pieces'        => '3',
                         'nb_chambres'      => '2',
                         'px_loyermin'      => '1200',
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
        return $response->xpath($this->annoncesNode);
    }

    protected function getDetailNode($response)
    {
        return null;
    }

    protected function getPhotoNode($response)
    {
        return $response->photos->photo;
    }

    /**
     * Detect if the next URL node is exist :
     *
     * public $pageSuivante =>
     *   string(238) "http://ws.seloger.com/search.xml?ci=750101,750102,750103,750104,750105,750106,750109,750110,750111&getdtcreationmax=1&idtt=1&idtypebien=1,2&nb_chambres=2&nb_pieces=3&px_loyermax=1800&px_loyermin=1200&surfacemin=55&tri=d_dt_crea&SEARCHpg=2"
     *
     * If true, we try to follow the link.
     *
     * @param string $response XML datas
     *
     * @return bool|string
     */
    protected function getNextNode($response)
    {
        if (count($response->xpath($this->nextPageNode)) != 1) {
            return false;
        }

        return (string) $response->xpath($this->nextPageNode)[0];
    }

}