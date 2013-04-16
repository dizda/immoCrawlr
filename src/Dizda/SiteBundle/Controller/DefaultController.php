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
        $accommodations = $this->getRepo('CrawlerBundle:Accommodation')->findBy([], ['priority'        => 'DESC',
                                                                                     'remoteUpdatedAt' => 'DESC',
                                                                                     'localUpdatedAt'  => 'DESC']);

        /*$pagination = $this->get('knp_paginator')->paginate(
            $accommodations,
            $this->getRequest()->query->get('page', 1),
            10
        );*/

        $count = function($name)
        {
            $result = $this->getRepo('CrawlerBundle:Accommodation')->countDiscriminator($name);

            return $result;

        };


        return ['accommodations' => $accommodations,
                'sites'          => ['pap'        => $count('pap'),
                                     'seloger'    => $count('seloger'),
                                     'explorimmo' => $count('explorimmo')]];
    }
}
