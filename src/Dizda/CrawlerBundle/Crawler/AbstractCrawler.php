<?php
namespace Dizda\CrawlerBundle\Crawler;

use Symfony\Component\Console\Output\ConsoleOutput;

use Guzzle\Http\Client;
use Guzzle\Http\Message\Request;

/**
 * Class CrawlerAbstract
 *
 * @package Dizda\CrawlerBundle\Crawler
 */
class AbstractCrawler
{
    const HTTP_METHOD = 'GET';

    protected $documentClass;

    protected $output;
    protected $dm;
    protected $client;
    protected $request;
    protected $query;
    protected $serializer;

    public function __construct()
    {
        $this->output  = new ConsoleOutput;
        $this->client  = new Client();

        $this->request = new Request(static::HTTP_METHOD, $documentClass::getSearchUrl());
        $this->request->setClient($this->client);
        $this->request->getCurlOptions()->set(CURLOPT_USERAGENT, $documentClass::USER_AGENT);

        $this->query = $this->request->getQuery();
    }

    abstract public function execute();

    public function getAccommodationsList()
    {
        $response = $this->request->send();
        $xml      = $response->xml();

        return $xml;
    }

    public function saveAccomodationsList($xml)
    {
        $cpt = 0;

        foreach ($xml as $annonce) {
            $entite = $this->serializer->deserialize($annonce->asXML(), $documentClass, 'xml');

            if (!$this->dm->find($documentClass, $entite->generateId())) {
                $this->dm->persist($entite);
                $cpt++;
            }
        }
        $this->dm->flush();

        $this->output->writeln('[<info>name</info>] ('.$cpt.'/'.count($xml).') accomodations added.');

        return true;
    }

    /**
     * @DI\InjectParams({
     *     "serializer" = @DI\Inject("jms_serializer")
     * })
     */
    public function setSerializer($serializer)
    {
        $this->serializer = $serializer;
    }

    /**
     * @DI\InjectParams({
     *     "documentManager" = @DI\Inject("doctrine.odm.mongodb.document_manager")
     * })
     */
    public function setDocumentManager($documentManager)
    {
        $this->dm = $documentManager;
    }
}