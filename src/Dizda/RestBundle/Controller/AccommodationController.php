<?php

namespace Dizda\RestBundle\Controller;

use Symfony\Component\HttpFoundation\RedirectResponse;

// these import the "@Route" and "@Template" annotations
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

use FOS\RestBundle\Controller\Annotations as REST;

use Dizda\RestBundle\Controller\CoreRESTController;
use Symfony\Component\HttpFoundation\Request;
use Dizda\SiteBundle\Document\Note;

class AccommodationController extends CoreRESTController
{

    /**
     * @REST\View(serializerGroups={"rest"})
     *
     * @return array
     */
    public function getAccommodationsAction()
    {
        $accommodations = $this->getRepo('CrawlerBundle:Accommodation')->findUntrashed($this->getUser(), 5);

        return iterator_to_array($accommodations, false);
    }

    /**
     * AJAX: Add or remove Favorite
     *
     * @param string $id Accommodation id
     *
     * @return array
     */
    public function favoriteAccommodationAction($id)
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

        return $result;
    }

    /**
     * AJAX: Setting a thumbnail viewed at 'onClick' event
     *
     * @param string $id Accommodation id
     *
     * @return array
     */
    public function viewedAccommodationAction($id)
    {
        $acco = $this->getRepo('CrawlerBundle:Accommodation')->find($id);

        if (!$acco->getViewed()->contains($this->getUser())) {
            $acco->addViewed($this->getUser());

            $this->getDm()->persist($acco);
            $this->getDm()->flush();
        }

        return ['success' => true];
    }


    /**
     * @param string  $id      Accommodation hash
     * @param Request $request Request container
     *
     * @return mixed
     */
    public function postAccommodationCommentAction($id, Request $request)
    {
        $acco = $this->getRepo('CrawlerBundle:Accommodation')->find($id);

        // If current user already created a note, we just update it
        foreach ($acco->getNotes() as $note) {
            if ($note->getUser() == $this->getUser()) {
                //$acco->getNotes()->removeElement($note);
                $note->setText($request->get('text'));


                $this->getDm()->persist($note);
                $this->getDm()->flush();

                return $acco->getNotes();
            }
        }

        // Then is a new note
        $note = new Note();
        $note->setUser($this->getUser());
        $note->setText($request->get('text'));
        $acco->addNote($note);

        $this->getDm()->persist($acco);
        $this->getDm()->flush();

        return $acco->getNotes();
    }

    /**
     * An user can choose to hide which accommodation he don't want to see anymore
     *
     * @param string $id
     *
     * @return JsonResponse
     */
    public function deleteAccommodationAction($id)
    {
        $acco = $this->getRepo('CrawlerBundle:Accommodation')->find($id);

        if (!$acco->getHidden()->contains($this->getUser())) {
            $acco->addHidden($this->getUser());
            $result = ['hidden' => true];
        } else {
            $acco->removeHidden($this->getUser());
            $result = ['hidden' => false];
        }

        $this->getDm()->persist($acco);
        $this->getDm()->flush();

        return $result;
    }

}