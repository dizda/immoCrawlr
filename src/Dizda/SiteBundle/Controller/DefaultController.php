<?php

namespace Dizda\SiteBundle\Controller;

use Dizda\CoreBundle\Controller\CoreController;
use Dizda\CrawlerBundle\Document\Accommodation;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

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
        $accommodations = $this->getRepo('CrawlerBundle:Accommodation')->findBy([], [//'priority'        => 'DESC',
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

    /**
     * AJAX: Setting a thumbnail viewed at 'onClick' event
     *
     * @param string $id
     *
     * @Route("/accommodation/viewed/{id}", options={"expose"=true})
     *
     * @return JsonResponse
     */
    public function setViewed($id)
    {
        $acco = $this->getRepo('CrawlerBundle:Accommodation')->find($id);

        if (!$acco->getViewed()->contains($this->getUser())) {
            $acco->addViewed($this->getUser());

            $this->getDm()->persist($acco);
            $this->getDm()->flush();
        }

        return new JsonResponse(['success' => true]);
    }

    /**
     * AJAX: Add or remove Favorite
     *
     * @param string $id
     *
     * @Route("/accommodation/favorite/{id}", options={"expose"=true})
     *
     * @return JsonResponse
     */
    public function setFavorite($id)
    {
        $acco = $this->getRepo('CrawlerBundle:Accommodation')->find($id);

        if (!$acco->getFavorites()->contains($this->getUser())) {
            $acco->addFavorite($this->getUser());
            $result = ['favorite' => true];
        } else {
            $acco->removeFavorite($this->getUser());
            $result = ['favorite' => false];
        }

        $this->getDm()->persist($acco);
        $this->getDm()->flush();

        return new JsonResponse($result);
    }
}
