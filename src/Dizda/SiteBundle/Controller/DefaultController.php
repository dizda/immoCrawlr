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
    public function setViewedAction($id)
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
    public function setFavoriteAction($id)
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

    /**
     * @Route("/accommodation/note.{_format}", defaults={"_format"="json"}, options={"expose"=true})
     * @Method({"POST"})
     *
     * @return JsonResponse
     */
    public function setNoteAction(Request $request)
    {
        //$request = json_decode(file_get_contents('php://input')); // handle json request
        $content = json_decode($request->getContent()); // handle json request
        $accoId  = $content->id;
        $noteTxt = str_replace("'", "\\'", $content->text); // escaping ' (single quotes), because it wont be inserted into db
        //var_dump(json_encode($noteTxt));die();


        $acco = $this->getRepo('CrawlerBundle:Accommodation')->find($accoId);

        // If current user already created a note, we just update it
        foreach ($acco->getNotes() as $note) {
            if ($note->getUser() == $this->getUser()) {
                //$acco->getNotes()->removeElement($note);
                $note->setText($noteTxt);


                $this->getDm()->persist($note);
                $this->getDm()->flush();

                return new JsonResponse(['updated' => true]);
            }
        }

        // Then is a new note
        $note = new Note();
        $note->setUser($this->getUser());
        $note->setText($noteTxt);
        $acco->addNote($note);

        $this->getDm()->persist($acco);
        $this->getDm()->flush();

        return new JsonResponse(['updated' => false]);
    }

    /**
     * An user can choose to hide which accommodation he dont want to see anymore
     *
     * @param string $id
     *
     * @Route("/accommodation/hidden/{id}", options={"expose"=true})
     *
     * @return JsonResponse
     */
    public function setHiddenAction($id)
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

        return new JsonResponse($result);
    }
}
