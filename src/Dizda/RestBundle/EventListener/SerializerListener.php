<?php
namespace Dizda\RestBundle\EventListener;

use JMS\DiExtraBundle\Annotation as DI;
use JMS\Serializer\EventDispatcher\PreSerializeEvent;
use JMS\Serializer\EventDispatcher\ObjectEvent;
use JMS\Serializer\GraphNavigator;

class SerializerListener
{

    /**
     * @param PreSerializeEvent $event
     */
    public function onPreSerialization(PreSerializeEvent $event)
    {
        /*var_dump($event->getVisitor()->);
        die('BOOM');*/
    }

    public function onPostSerialization(ObjectEvent $event)
    {
        /*var_dump($event->getContext()->getVisitor()->getNavigator());
        die('KABOOM');*/
    }
}