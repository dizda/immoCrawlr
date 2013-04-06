<?php
namespace Dizda\CrawlerBundle\Command;

use Dizda\CrawlerBundle\Crawler\SelogerCrawler;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Guzzle\Http\Client;
use Guzzle\Http\Message\Request;

use Dizda\CrawlerBundle\Document\Seloger;
use Dizda\CrawlerBundle\Document\Explorimmo;
use Symfony\Component\DependencyInjection\SimpleXMLElement;

class CrawlCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('dizda:crawl:go')
            ->setDescription('Crawling a gogo');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Hello');

        //$this->crawlSeloger();
        //$this->crawlExplorimmo();

        $seloger = new SelogerCrawler();
    }

    public function crawlSeloger()
    {
        $dm = $this->getContainer()->get('doctrine.odm.mongodb.document_manager');

        $client  = new Client();
        $client->setUserAgent(Seloger::USER_AGENT);

        $request = new Request('GET', Seloger::getSearchUrl());
        $request->setClient($client);
        $request->getCurlOptions()->set(CURLOPT_USERAGENT, Seloger::USER_AGENT);
        // to watch with Charles
        /*$request->getCurlOptions()->set(CURLOPT_PROXY, 'localhost:8888');
        $request->getCurlOptions()->set(CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
        $request->getCurlOptions()->set(CURLOPT_USERAGENT, Seloger::USER_AGENT);*/ // override UA when use Request()

        $query = $request->getQuery();
        $query->add('idtt', '1');
        $query->add('ci', '750101,750102,750103,750109,750109,750111'); //750111,750103
        $query->add('idtypebien', '1,2');
        $query->add('nb_pieces', '3');
        $query->add('nb_chambres', '2');
        $query->add('px_loyermin', '1000');
        $query->add('px_loyermax', '1400');
        $query->add('surfacemin', '55');
        $query->add('tri', 'd_dt_crea');
        $query->add('getDtCreationMax', '1');
        //$query->add('pg', '22'); // pas bonne pagination..

        $response = $request->send();
        $xml = $response->xml();

        echo $xml->resume . PHP_EOL;
        var_dump('nb trouvees '.$xml->nbTrouvees.' nbAffichables '.$xml->nbAffichables. ' count '.count($xml->annonces->annonce));

        foreach ($xml->annonces->annonce as $annonce) {
            $entite = $this->getContainer()->get('jms_serializer')->deserialize($annonce->asXML(), 'Dizda\CrawlerBundle\Document\Seloger', 'xml');
            $entite->setPhotos($annonce->photos);
            $dm->persist($entite);
        }
        $dm->flush();
    }

    public function crawlExplorimmo()
    {
        $dm = $this->getContainer()->get('doctrine.odm.mongodb.document_manager');

        $client  = new Client();

        $request = new Request('GET', Explorimmo::getSearchUrl());
        $request->setClient($client);
        $request->getCurlOptions()->set(CURLOPT_USERAGENT, Explorimmo::USER_AGENT);
        // to watch with Charles
        /*$request->getCurlOptions()->set(CURLOPT_PROXY, 'localhost:8888');
        $request->getCurlOptions()->set(CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
        $request->getCurlOptions()->set(CURLOPT_USERAGENT, Seloger::USER_AGENT);*/ // override UA when use Request()

        // Search accomodations
        $query = $request->getQuery();
        /*$query->add('resultNumber', 50);
        $query->add('orderBy', 'dateDesc');
        $query->add('page', '1');
        $query->add('transaction', 'LOCATION');
        $query->add('localisation', 'PARIS 1ER (75001),PARIS 2EME (75002),PARIS 3EME (75003),PARIS 4EME (75004),PARIS 9EME (75009),PARIS 10EME (75010),PARIS 11EME (75011)');
        $query->add('roomMin', '3');
        $query->add('priceMin', '1000');
        $query->add('priceMax', '1800');
        $query->add('surfaceMin', '56');
        $query->add('surfaceMax', '2147483647');
        $query->add('withPictures', 'false');
        $query->add('newOnly', 'false');

        $response = $request->send();
        $xml = $response->xml();

        foreach ($xml->classified as $annonce) {
            $entite = $this->getContainer()->get('jms_serializer')->deserialize($annonce->asXML(), 'Dizda\CrawlerBundle\Document\Explorimmo', 'xml');

            if (!$dm->find('CrawlerBundle:Explorimmo', $entite->generateId())) {
                $dm->persist($entite);
            }
        }
        $dm->flush();*/

        // And get more details about each accomodations
        $request = new Request('GET', Explorimmo::getDetailUrl());
        $request->setClient($client);
        $request->getCurlOptions()->set(CURLOPT_USERAGENT, Explorimmo::USER_AGENT);
        $query = $request->getQuery();

        $entities = $dm->getRepository('CrawlerBundle:Explorimmo')->findBy(array('fullDetail' => false));

        foreach ($entities as $annonceLight) {
            var_dump($annonceLight->getRemoteId());

            $query->replace(array());
            $query->add('id', $annonceLight->getRemoteId());

            $response = $request->send();
            $xml      = $response->xml();
            $photos   = $xml;
            $xml      = str_replace(Explorimmo::$xmlSearch, Explorimmo::$xmlReplace, $xml->asXML());
            var_dump($xml);

            $entite = $this->getContainer()->get('jms_serializer')->deserialize($xml, 'Dizda\CrawlerBundle\Document\Explorimmo', 'xml');
            $entite->setPhotos($photos->photos);
            $entite->setFullDetail(true);
            $dm->persist($entite);

        }
        $dm->flush();

    }
}