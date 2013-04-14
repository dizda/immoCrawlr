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
                         'prix[max]'             => '2000',
                         'surface[min]'          => '55',
                         'nb_pieces[min]'        => '3',
                         'nb_chambres[min]'      => '2',
                         'geoobjets[0]'          => '37768;0', // Paris 1er
                         'geoobjets[1]'          => '37769;0', // Paris 2e
                         'geoobjets[2]'          => '37770;0', // Paris 3e
                         'geoobjets[3]'          => '37771;0', // Paris 4e
                         'geoobjets[4]'          => '37772;0', // Paris 5e
                         'geoobjets[5]'          => '37776;0', // Paris 9e
                         'geoobjets[6]'          => '37777;0', // Paris 10e
                         'geoobjets[7]'          => '37778;0', // Paris 11e
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

    protected function getNode($response)
    {
        return $response['annonces'];
    }

    protected function getDetailNode($response)
    {
        return json_encode($response);
    }

    protected function getPhotoNode($response)
    {
        return null;
    }

}