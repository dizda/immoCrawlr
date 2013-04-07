<?php

namespace Dizda\CoreBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * Bootstrap CoreController
 *
 * @package Dizda\CoreBundle\Controller
 */
class CoreController extends Controller
{
    protected function getEm()
    {
        return $this->get('doctrine.odm.mongodb.document_manager');
    }

    protected function getRepo($repository)
    {
        return $this->getEm()->getRepository($repository);
    }
}
