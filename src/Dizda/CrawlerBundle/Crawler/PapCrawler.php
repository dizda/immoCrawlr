<?php
namespace Dizda\CrawlerBundle\Crawler;

use Dizda\CrawlerBundle\Crawler\AbstractCrawler;
use JMS\DiExtraBundle\Annotation as DI;

/**
 * Class SelogerCrawler
 *
 * @package Dizda\CrawlerBundle\Crawler
 * @DI\Service("crawler.pap")
 */
class PapCrawler extends AbstractCrawler
{
    const HTTP_METHOD = 'POST';

    static protected $documentClass = 'Dizda\CrawlerBundle\Document\Pap';

    protected $params = ['produit'               => 'location',
                         'typesbien[0]'          => 'appartement',
                         'prix[max]'             => '2800',
                         'surface[min]'          => '30',
                         'nb_pieces[min]'        => '3',
                         'nb_chambres[min]'      => '2',
                         'geoobjets[0]'          => '37770;0',
                         'plateforme'            => 'android',
                         'type'                  => 'create',
                         'nb_resultats_par_page' => '40',
                         'page'                  => '1',
                         'tri'                   => 'date-desc',
    ];

    // ne pas oublier les headers qui changent

    /**
     * {@inheritdoc}
     */
    public function execute($progress)
    {
        $this->progress = $progress;
        parent::getAccommodationsList();
    }

}