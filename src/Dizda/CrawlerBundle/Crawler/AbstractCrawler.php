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
    private $client;
    protected $request;
    protected $params;
    protected $query;
    /** @DI\Inject("jms_serializer") */
    public $serializer;
    protected $progress;
    protected $followPagination = false;
    protected $annoncesNode; // XPath
    protected $nextPageNode; // XPath

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->output     = new ConsoleOutput;
        $this->client     = new Client();
        $this->class      = new \ReflectionClass(static::$documentClass);
    }

    /**
     * Reset request to perform new one
     *
     * If there ain't $params, we follow the next page full url received by WS
     * or we takin base url from Document class and add referenced parameters.
     *
     * @param string      $url
     * @param array|bool  $params
     * @param bool        $proxy
     */
    private function addRequest($url, $params = false, $proxy = true)
    {
        if ($params) {
            $url = call_user_func(array(static::$documentClass, $url));
        }
        $this->request = new Request(static::HTTP_METHOD, $url);
        $this->request->setClient($this->client);
        $this->request->getCurlOptions()->set(CURLOPT_USERAGENT, $this->class->getConstant('USER_AGENT'));
        $this->addHeaders(call_user_func(array(static::$documentClass, 'getHeaders')));

        if ($params) {
            $this->query = $this->request->getQuery();
            $this->query->replace($params);
        }

        if ($proxy) {
            $this->request->getCurlOptions()->set(CURLOPT_PROXY, 'localhost:8888');
            $this->request->getCurlOptions()->set(CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
        }
    }

    /**
     * Add some headers to avoid being detected
     *
     * @param array $headers Associative array
     */
    private function addHeaders(array $headers)
    {
        if (count($headers) > 0) {
            foreach ($headers as $key => $value) {
                $this->request->getHeaders()->add($key, $value);
            }
        }
    }

    /**
     * @param Object $progress The progressbar
     *
     * @return mixed
     */
    abstract public function execute($progress);

    /**
     * Perform list of accommodations
     * or next page
     *
     * @param string|bool $nextPage Next page is false by default, but can contain the URL to follow if needed
     */
    public function getAccommodationsList($nextPage = false)
    {
        if ($nextPage) {
            $this->addRequest($nextPage);
        } else {
            $this->addRequest('getSearchUrl', $this->params);
        }

        $response = $this->request->send();var_dump($response->json());die();
        $xml      = $response->xml();


        $this->saveAccomodationsList($xml);
    }

    /**
     * Saving list of accommodations w/ or w/o details, it's depends which website showing enougth
     * informations or not.
     * Ex. Seloger don't need to fetch detail, but Explorimmo yes
     *
     * @param \XMLSimpleElement $xml
     */
    public function saveAccomodationsList($xml)
    {
        $cpt = 0;
        $announces = $xml->xpath($this->annoncesNode);

        foreach ($announces as $announce) {
            $entite = $this->serializer->deserialize($announce->asXML(), static::$documentClass, $this->class->getConstant('WS_TYPE'));

            if (!$this->dm->find(static::$documentClass, $entite->generateId())) {
                /* If we dont have to fetch detail for each announce, we can save photos now */
                if (!$this->class->hasConstant('URL_DETAIL')) {
                    $entite->setPhotos($announce->photos);
                }
                $this->dm->persist($entite);
                $cpt++;
            } // we can check here if the announce was updated comparing $remoteUpdatedAt fields of both
        }
        $this->dm->flush();

        $this->output->writeln('[<info>'.$this->class->getShortName().'</info>] ('.$cpt.'/'.count($announces).') accommodations added.');


        /* If following pagination is activated and if 'nextPage' link exist, we follow the link */
        if ($this->followPagination && count($xml->xpath($this->nextPageNode)) > 0) {
            $this->getAccommodationsList((string) $xml->xpath($this->nextPageNode)[0]);

            return;
        }


        // Once each pages are scrapped, if we need additional datas, we fetch every detailed pages
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
        $total = count($entities);

        $this->output->writeln('[<info>'.$this->class->getShortName().'</info>] Fetching '.count($entities).' announces details...');


        $this->progress->start($this->output, $total);
        foreach ($entities as $annonceLight) {

            $this->addRequest('getDetailUrl', array('id' => $annonceLight->getRemoteId())); // setting detail Url

            $response = $this->request->send();
            $xml      = $response->xml();
            $photos   = $xml;
            $xml      = str_replace($this->class->getStaticPropertyValue('xmlSearch'),
                                    $this->class->getStaticPropertyValue('xmlReplace'),
                                    $xml->asXML()); // clean XML..

            $announce = $this->serializer->deserialize($xml, static::$documentClass, 'xml');
            $announce->setPhotos($photos->photos->photo);
            $announce->setFullDetail(true);
            $this->dm->persist($announce);
            $this->progress->advance();
        }
        $this->dm->flush();
        $this->progress->finish();

        $this->output->writeln('[<info>'.$this->class->getShortName().'</info>] <comment>'.$total.' announces added.</comment>');
    }

}