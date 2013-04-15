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

    /** @DI\Inject("doctrine.odm.mongodb.document_manager") */
    public $dm;
    /** @DI\Inject("jms_serializer") */
    public $serializer;
    private $client;
    protected $class;
    protected $output;
    protected $request;
    protected $params;
    protected $query;
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
    private function addRequest($url, $params = false, $proxy = false)
    {
        $method = strtolower(static::HTTP_METHOD);
        if ($params) {
            $url = call_user_func(array(static::$documentClass, $url));
        }

        $this->client->setUserAgent($this->class->getConstant('USER_AGENT'));
        $this->request = $this->client->$method($url, call_user_func(array(static::$documentClass, 'getHeaders')), $params);

        // TODO: ADD FACTORY ONLY SUR GET METHOD ?!
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
        $format = $this->class->getConstant('WS_FORMAT');

        if ($nextPage) {
            $this->addRequest($nextPage);
        } else {
            $this->addRequest('getSearchUrl', $this->params);
        }

        $response = $this->request->send()->$format();

        $this->saveAccomodationsList($response);
    }

    abstract protected function getNode($response);

    /**
     * Saving list of accommodations w/ or w/o details, it's depends which website showing enougth
     * informations or not.
     * Ex. Seloger don't need to fetch detail, but Explorimmo yes
     *
     * @param \XMLSimpleElement $xml
     */
    public function saveAccomodationsList($response)
    {
        $cpt = 0;
        $announces = $this->getNode($response);

        foreach ($announces as $announce) {

            $photos   = $announce;
            $announce = ($announce instanceof \SimpleXMLElement) ? $announce->asXML() : json_encode($announce);
            $entity   = $this->serializer->deserialize($announce, static::$documentClass, $this->class->getConstant('WS_FORMAT'));

            if (!$this->dm->find(static::$documentClass, $entity->generateId())) {
                /* If we dont have to fetch detail for each announce, we can save photos now */
                if (!$this->class->hasConstant('URL_DETAIL')) {
                    $entity->setPhotos($this->getPhotoNode($photos));
                }
                $this->dm->persist($entity);
                $cpt++;
            } // we can check here if the announce was updated comparing $remoteUpdatedAt fields of both
        }
        $this->dm->flush();

        $this->output->writeln('[<info>'.$this->class->getShortName().'</info>] ('.$cpt.'/'.count($announces).') accommodations added.');


        /* If following pagination is activated and if 'nextPage' link exist, we follow the link */
        /*if ($this->followPagination && count($xml->xpath($this->nextPageNode)) > 0) {
            $this->getAccommodationsList((string) $xml->xpath($this->nextPageNode)[0]);

            return;
        }*/


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
        $format   = $this->class->getConstant('WS_FORMAT');
        $entities = $this->dm->getRepository(static::$documentClass)->findBy(array('fullDetail' => false));
        $total    = count($entities);

        $this->output->writeln('[<info>'.$this->class->getShortName().'</info>] Fetching '.count($entities).' announces details...');


        $this->progress->start($this->output, $total);
        foreach ($entities as $annonceLight) {

            $this->addRequest('getDetailUrl', array('id' => $annonceLight->getRemoteId()));
            $announce = $this->request->send()->$format();
            $photos   = $this->getPhotoNode($announce);

            $announce = $this->serializer->deserialize($this->getDetailNode($announce), static::$documentClass, $this->class->getConstant('WS_FORMAT'));

            if ($photos) {
                $announce->setPhotos($photos);
            }
            $announce->setFullDetail(true);

            $this->dm->persist($announce);
            $this->progress->advance();
        }
        $this->dm->flush();
        $this->progress->finish();

        $this->output->writeln('[<info>'.$this->class->getShortName().'</info>] <comment>'.$total.' announces added.</comment>');
    }

    /**
     * Get target node to get more details, and cleaning flux if needed
     *
     * @param \SimpleXMLElement|JSON $response
     *
     * @return mixed
     */
    abstract protected function getDetailNode($response);

    /**
     * If photos cannot be fetch automatically with JMS, we indicate the node
     * to help photos to be pushed in DB
     *
     * @param \SimpleXMLElement|JSON $response
     *
     * @return mixed
     */
    abstract protected function getPhotoNode($response);

}