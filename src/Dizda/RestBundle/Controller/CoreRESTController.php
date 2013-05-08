<?php

namespace Dizda\RestBundle\Controller;

use FOS\RestBundle\Controller\Annotations;
use FOS\RestBundle\Controller\FOSRestController;

use Symfony\Component\HttpFoundation\Request;


/**
 * Class CoreRESTController
 *
 * @package Dizda\RestBundle\Controller
 */
class CoreRESTController extends FOSRestController
{
    protected function getDm()
    {
        return $this->get('doctrine.odm.mongodb.document_manager');
    }

    protected function getRepo($repository)
    {
        return $this->getDm()->getRepository($repository);
    }


}