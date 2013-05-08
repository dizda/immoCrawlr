<?php

namespace Dizda\SiteBundle\Controller;

use Dizda\CoreBundle\Controller\CoreController;
use Dizda\CrawlerBundle\Document\Accommodation;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Dizda\SiteBundle\Document\Note;
use Doctrine\Common\Collections\Criteria;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class DefaultController
 *
 * @package Dizda\SiteBundle\Controller
 */
class AnnounceController extends CoreController
{
    /**
     * @Route("/announces")
     * @Template()
     */
    public function indexAction()
    {
        return [];
    }


}
