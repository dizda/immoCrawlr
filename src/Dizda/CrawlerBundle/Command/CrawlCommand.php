<?php
namespace Dizda\CrawlerBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use Guzzle\Http\Client;
use Guzzle\Http\Message\Request;

use Dizda\CrawlerBundle\Document\Seloger;

class CrawlCommand extends ContainerAwareCommand
{

    protected function configure()
    {
        $this
            ->setName('dizda:crawl:go')
            ->setDescription('lol')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->writeln('Hello');
        $dm = $this->getContainer()->get('doctrine.odm.mongodb.document_manager');

        $client  = new Client();
        $client->setUserAgent(Seloger::USER_AGENT);

        $request = new Request('GET', 'http://ws.seloger.com/search.xml');
        $request->setClient($client);
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
        $query->add('px_loyermax', '2500');
        $query->add('surfacemin', '55');
        $query->add('tri', 'd_dt_crea');
        $query->add('getDtCreationMax', '1');
        //$query->add('pg', '22'); // pas bonne pagination..

        $response = $request->send();
        $xml = $response->xml();

        var_dump('nb trouvees '.$xml->nbTrouvees.' nbAffichables '.$xml->nbAffichables. ' count '.count($xml->annonces->annonce));

        foreach ($xml->annonces->annonce as $annonce) {
            $entite = $this->getContainer()->get('jms_serializer')->deserialize($annonce->asXML(), 'Dizda\CrawlerBundle\Document\Seloger', 'xml');
            $entite->setPhotos($annonce->photos);
            $dm->persist($entite);
        }
        $dm->flush();

    }
}