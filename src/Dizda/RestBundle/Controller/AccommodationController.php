<?php

namespace Dizda\RestBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;

// these import the "@Route" and "@Template" annotations
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use FOS\RestBundle\Controller\Annotations;

use Dizda\RestBundle\Controller\CoreRESTController;

/**
 * @Route("/api")
 */
class AccommodationController extends CoreRESTController
{

    /**
     * @Route("/hello/{name}.{_format}")
     */
    public function helloAction($name)
    {
        return array('name' => $name);
    }

}