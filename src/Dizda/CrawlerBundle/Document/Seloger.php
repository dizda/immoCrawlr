<?php
namespace Dizda\CrawlerBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Dizda\CrawlerBundle\Document\Accommodation;
use JMS\Serializer\Annotation as JMS;

/**
 * Store every ads of Seloger.com into our MongoDB
 *
 * @MongoDB\Document
 *
 * @JMS\XmlRoot("annonce")
 */
class Seloger extends Accommodation
{
    const WS_TYPE    = 'xml';
    const HOST       = 'http://ws.seloger.com/';
    const URL_SEARCH = 'search.xml';

    const USER_AGENT = 'Dalvik/1.6.0 (Linux; U; Android 4.2.1; GT-I9300 Build/JOP40D)';

    /**
     *  @JMS\Type("string")
     *  @JMS\SerializedName("idAnnonce") */
    protected $remoteId;

    /**
     *  @JMS\Type("string")
     *  @JMS\SerializedName("titre") */
    protected $title;

    /**
     *  @JMS\Type("string")
     *  @JMS\SerializedName("libelle") */
    protected $title2;

    /**
     *  @JMS\Type("string")
     *  @JMS\SerializedName("proximite") */
    protected $district;

    /**
     *  @JMS\Type("string")
     *  @JMS\SerializedName("descriptif") */
    protected $description;





    /**
     *  @JMS\Type("double")
     *  @JMS\SerializedName("prix") */
    protected $price;

    /**
     *  @JMS\Type("string")
     *  @JMS\SerializedName("prixUnite")
     *  @JMS\Accessor(setter="setIsChargesIncluded") */
    protected $isChargesIncluded;

    /**
     *  @JMS\Type("integer")
     *  @JMS\SerializedName("nbPiece") */
    protected $rooms;

    /**
     *  @JMS\Type("integer")
     *  @JMS\SerializedName("nbChambre") */
    protected $bedrooms;

    /**
     *  @JMS\Type("integer")
     *  @JMS\SerializedName("nbsallesdebain") */
    protected $bathroom;

    /**
     *  @JMS\Type("integer")
     *  @JMS\SerializedName("nbtoilettes") */
    protected $wcroom;

    /**
     *  @JMS\Type("double")
     *  @JMS\SerializedName("surface") */
    protected $surface;




    /**
     *  @JMS\Type("string")
     *  @JMS\SerializedName("cp") */
    protected $postalcode;

    /**
     *  @JMS\Type("string")
     *  @JMS\SerializedName("codeInsee") */
    protected $inseecode;

    /**
     *  @JMS\Type("string")
     *  @JMS\SerializedName("ville") */
    protected $city;


    /**
     *  @JMS\Type("string")
     *  @JMS\SerializedName("permaLien") */
    protected $permalink;

    /**
     *  @JMS\Type("double")
     *  @JMS\SerializedName("latitude") */
    protected $geoLat;

    /**
     *  @JMS\Type("double")
     *  @JMS\SerializedName("longitude") */
    protected $geoLong;






    /*protected $contactName;

    protected $contactPhone;

    protected $contactEmail;

    protected $contactAddress;

    protected $contactGeoLat;

    protected $contactGeoLong;*/







    /** @JMS\Type("DateTime<'Y-m-d\TH:i:s'>")
     *  @JMS\SerializedName("dtCreation") */
    protected $remoteCreatedAt;

    /** @JMS\Type("DateTime<'Y-m-d\TH:i:s'>")
     *  @JMS\SerializedName("dtFraicheur") */
    protected $remoteUpdatedAt;








    /**
     * @param bool $isChargesIncluded can be "€cc*"|"€+ch"
     *
     * @return $this|\Accommodation
     */
    public function setIsChargesIncluded($isChargesIncluded)
    {
        $this->isChargesIncluded = (mb_strpos($isChargesIncluded, '€cc') !== false) ? true : false;

        return $this;
    }

    /**
     * Push in array big pictures, or fallin' back to standard pictures :-]
     *
     * @param SimpleXMLElement $photos
     *
     * @return $this|\Accommodation
     */
    public function setPhotos($photos)
    {
        $collection = array();
        foreach ($photos->photo as $photo) {

            if (strlen((string) $photo->bigUrl) > 0) {
                $collection[] = (string) $photo->bigUrl;
            } else {
                $collection[] = (string) $photo->stdUrl;
            }

        }
        $this->photos = $collection;

        return $this;
    }

    /**
     * Get REST URL to search on
     *
     * @return string
     */
    final public static function getSearchUrl()
    {
        return static::HOST . static::URL_SEARCH;
    }

}
