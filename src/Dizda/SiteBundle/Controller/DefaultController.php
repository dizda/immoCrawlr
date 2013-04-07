<?php

namespace Dizda\SiteBundle\Controller;

use Dizda\CoreBundle\Controller\CoreController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Class DefaultController
 *
 * @package Dizda\SiteBundle\Controller
 */
class DefaultController extends CoreController
{
    /**
     * @Route("/")
     * @Template()
     */
    public function indexAction()
    {
        $accommodations = $this->getRepo('CrawlerBundle:Accommodation')->findBy([], ['remoteUpdatedAt' => 'DESC']);

        return array('accommodations' => $accommodations);
    }
}
