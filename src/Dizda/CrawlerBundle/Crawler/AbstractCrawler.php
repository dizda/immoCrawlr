<?php
namespace Dizda\CrawlerBundle\Crawler;

use Doctrine\ODM\MongoDB\DocumentManager;
use JMS\DiExtraBundle\Annotation as DI;
use JMS\Serializer\Serializer;
use Symfony\Component\Console\Output\ConsoleOutput;

use Guzzle\Http\Client;
use Guzzle\Http\Message\Request;

/**
 * Class AbstractCrawler
 *
 * @package Dizda\CrawlerBundle\Crawler
 * @DI\Service
 */
abstract class AbstractCrawler
{
    const HTTP_METHOD = 'GET';

    static protected $documentClass;

    protected $class;
    protected $output;
    /** @DI\Inject("doctrine.odm.mongodb.document_manager") */
    public $dm;
    protected $client;
    protected $request;
    protected $params;
    protected $query;
    /** @DI\Inject("jms_serializer") */
    public $serializer;

    public function __construct()
    {
        $this->output     = new ConsoleOutput;
        $this->client     = new Client();
        $this->class      = new \ReflectionClass(static::$documentClass);

        $this->request = new Request(static::HTTP_METHOD, call_user_func(array(static::$documentClass, 'getSearchUrl')));
        $this->request->setClient($this->client);
        $this->request->getCurlOptions()->set(CURLOPT_USERAGENT, $this->class->getConstant('USER_AGENT'));

        $this->query = $this->request->getQuery();
    }

    abstract public function execute();

    public function getAccommodationsList()
    {
        $this->query->replace($this->params);
        $response = $this->request->send();
        $xml      = $response->xml();

        return $xml;
    }

    public function saveAccomodationsList($xml)
    {
        $cpt = 0;

        foreach ($xml as $annonce) {
            $entite = $this->serializer->deserialize($annonce->asXML(), static::$documentClass, 'xml');

            if (!$this->dm->find(static::$documentClass, $entite->generateId())) {
                /* If we dont have to fetch detail for each announce, we can save photos now */
                if (!$this->class->hasConstant('URL_DETAIL')) {
                    $entite->setPhotos($annonce->photos);
                }
                $this->dm->persist($entite);
                $cpt++;
            }
        }
        $this->dm->flush();

        $this->output->writeln('[<info>'.$this->class->getShortName().'</info>] ('.$cpt.'/'.count($xml).') accomodations added.');

        // if new announces and if url is setted in the document concerned
        if ($cpt && $this->class->hasConstant('URL_DETAIL')) {
            $this->saveAccomodationsDetails();
        }
    }

    /**
     * If there is new announces,
     * and if announce have to reach Detail url to get more informations
     */
    public function saveAccomodationsDetails()
    {
        $entities = $this->dm->getRepository(static::$documentClass)->findBy(array('fullDetail' => false));

        $this->output->writeln('[<info>'.$this->class->getShortName().'</info>] Fetching '.count($entities).' announces...');

        foreach ($entities as $annonceLight) {
            var_dump($annonceLight->getRemoteId());

            $this->query->replace(array('id', $annonceLight->getRemoteId()));

            $response = $this->request->send();
            $xml      = $response->xml();
            $photos   = $xml;
            $xml      = str_replace($this->class->getStaticPropertyValue('xmlSearch'),
                                    $this->class->getStaticPropertyValue('xmlReplace'),
                                    $xml->asXML()); // clean XML..
            var_dump($xml);

            $entite = $this->serializer->deserialize($xml, static::$documentClass, 'xml');
            $entite->setPhotos($photos->photos);
            $entite->setFullDetail(true);
            $this->dm->persist($entite);

        }
        $this->dm->flush();
    }

}