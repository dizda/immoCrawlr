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
                $this->dm->persist($entite);
                $entite->setPhotos($annonce->photos);
                $cpt++;
            }
        }
        $this->dm->flush();

        $this->output->writeln('[<info>'.$this->class->getShortName().'</info>] ('.$cpt.'/'.count($xml).') accomodations added.');

        return $cpt;
    }


    public function saveAccomodationsDetails()
    {
        $entities = $this->dm->getRepository(static::$documentClass)->findBy(array('fullDetail' => false));

        foreach ($entities as $annonceLight) {
            var_dump($annonceLight->getRemoteId());

            $this->query->replace(array('id', $annonceLight->getRemoteId()));

            $response = $this->request->send();
            $xml      = $response->xml();
            $photos   = $xml;
            $xml      = str_replace(Explorimmo::$xmlSearch, Explorimmo::$xmlReplace, $xml->asXML()); // clean XML..
            var_dump($xml);

            $entite = $this->serializer->deserialize($xml, static::$documentClass, 'xml');
            $entite->setPhotos($photos->photos);
            $entite->setFullDetail(true);
            $this->dm->persist($entite);

        }
        $this->dm->flush();
    }

}