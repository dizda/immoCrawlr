<?php
namespace Dizda\CrawlerBundle\Crawler;

use Dizda\CrawlerBundle\Crawler\AbstractCrawler;
use JMS\DiExtraBundle\Annotation as DI;


/**
 * Class ExplorimmoCrawler
 *
 * @package Dizda\CrawlerBundle\Crawler
 * @DI\Service("crawler.explorimmo")
 */
class ExplorimmoCrawler extends AbstractCrawler
{
    static protected $documentClass = 'Dizda\CrawlerBundle\Document\Explorimmo';

    protected $annoncesNode     = '//classified'; // XPath

    protected $params = ['resultNumber'     => '50',
                         'orderBy'          => 'dateDesc',
                         'page'             => '1',
                         'transaction'      => 'LOCATION',
                         'localisation'     => 'PARIS 1ER (75001),PARIS 2EME (75002),PARIS 3EME (75003),PARIS 4EME (75004),PARIS 5EME (75005),PARIS 6EME (75006),PARIS 9EME (75009),PARIS 10EME (75010),PARIS 11EME (75011)',
                         'roomMin'          => '3',
                         'priceMin'         => '1000',
                         'priceMax'         => '1800',
                         'surfaceMin'       => '56',
                         'surfaceMax'       => '2147483647',
                         'withPictures'     => 'false',
                         'newOnly'          => 'false' ];


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
        return str_replace($this->class->getStaticPropertyValue('xmlSearch'),
            $this->class->getStaticPropertyValue('xmlReplace'),
            $response->asXML());
    }

    protected function getPhotoNode($response)
    {
        return $response->photos->photo;
    }
}