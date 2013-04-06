<?php

namespace Dizda\CrawlerBundle\Document;


use JMS\Serializer\Annotation as JMS;

/** @JMS\XmlRoot("photo") */
class Photo
{
    /**
     * @JMS\Type("string")
     * @JMS\SerializedName("thbUrl")
     */
    public $thbUrl;

    /**
     * @JMS\Type("string")
     */
    public $carreurl;

    /**
     * @JMS\Type("string")
     */
    public $stdUrl;


    public function setThbUrl($lol){$this->thbUrl = $lol;}
}