<?php

namespace Dizda\RestBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;

// these import the "@Route" and "@Template" annotations
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use FOS\RestBundle\Controller\Annotations as REST;

use Dizda\RestBundle\Controller\CoreRESTController;



class AccommodationController extends CoreRESTController
{

    /**
     * @REST\View(serializerGroups={"rest"})
     *
     * @return array
     */
    public function getAccommodationsAction()
    {
        //$accommodations = $this->getRepo('CrawlerBundle:Accommodation')->findUntrashed($this->getUser());

        $accommodations2 = $this->getRepo('CrawlerBundle:Accommodation')->findBy([], [], 1);
        //$accommodations2 = $this->getRepo('UserBundle:User')->find('5172c3b31bc8349505000000');
        //$accommodations3 = ;

        return iterator_to_array($accommodations2, false);
    }

}