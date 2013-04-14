<?php
namespace Dizda\CrawlerBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;
use Dizda\CrawlerBundle\Document\Accommodation;
use JMS\Serializer\Annotation as JMS;

/**
 * Store every ads of Pap into our MongoDB
 *
 * @MongoDB\Document
 *
 * @JMS\XmlRoot("annonces")
 */
class Pap extends Accommodation
{
    const WS_FORMAT  = 'json';
    const HOST       = 'http://www.pap.fr/';
    const URL_SEARCH = 'iphone/v1/recherche.php';
    const URL_DETAIL = 'iphone/v1/detail.php';

    const USER_AGENT = 'Apache-HttpClient/Android';


    /**
     *  @JMS\Type("string")
     *  @JMS\SerializedName("id") */
    protected $remoteId;

    /**
     *  @JMS\Type("string")
     *  @JMS\SerializedName("soustitre") */
    protected $title;

    /**
     *  @JMS\Type("string")
     *  @JMS\SerializedName("detail2") */
    protected $title2;

    /**
     *  @JMS\Type("string")
     *  @JMS\SerializedName("localisation") */
    protected $district;

    /**
     *  @JMS\Type("string")
     *  @JMS\SerializedName("texte") */
    protected $description;





    /**
     *  @JMS\Type("string")
     *  @JMS\SerializedName("titre")
     *  @JMS\Accessor(setter="setPrice") */
    protected $price;

    /**
     *  @JMS\Type("double")
     *  @JMS\SerializedName("charges") */
    protected $chargesAmount;

    /**
     *  @JMS\Type("boolean")
     *  @JMS\SerializedName("chargesIncluded") */
    protected $isChargesIncluded;

    /**
     *  @JMS\Type("double")
     *  @JMS\SerializedName("fees") */
    protected $agencyFees;

    /**
     *  @JMS\Type("double")
     *  @JMS\SerializedName("deposit") */
    protected $depositFees;

    /**
     *  @JMS\Type("string")
     *  @JMS\SerializedName("heating") */
    protected $heating;







    /**
     *  @JMS\Type("string")
     *  @JMS\SerializedName("detail2")
     *  @JMS\Accessor(setter="setRooms") */
    protected $rooms;

    /**
     *  @JMS\Type("string")
     *  @JMS\SerializedName("detail2")
     *  @JMS\Accessor(setter="setBedrooms") */
    protected $bedrooms;

    /**
     *  @JMS\Type("string")
     *  @JMS\SerializedName("detail1")
     *  @JMS\Accessor(setter="setSurface") */
    protected $surface;





    /**
     *  @JMS\Type("string")
     *  @JMS\SerializedName("metros")
     *  @JMS\Accessor(setter="setMetros") */
    protected $metros = array();

    /**
     *  @JMS\Type("string")
     *  @JMS\SerializedName("soustitre") */
    protected $city;

    /**
     *  @JMS\Type("double")
     *  @JMS\SerializedName("geo_lat") */
    protected $geoLat;

    /**
     *  @JMS\Type("double")
     *  @JMS\SerializedName("geo_lng") */
    protected $geoLong;

    /**
     *  @JMS\Type("array")
     *  @JMS\SerializedName("photo_urls") */
    protected $photos = array();






    /**
     *  @JMS\Type("array")
     *  @JMS\SerializedName("telephones")
     *  @JMS\Accessor(setter="setContactPhone") */
    protected $contactPhone;




    /**
     * @JMS\Exclude
     */
    protected $fullDetail = false;

    /** @JMS\Type("DateTime<'d/m/Y'>")
     *  @JMS\SerializedName("creationDate")
     *  @JMS\Accessor(setter="setRemoteCreatedAt") */
    protected $remoteCreatedAt;

    /** @JMS\Type("DateTime<'d/m/Y'>")
     *  @JMS\SerializedName("modificationDate")
     *  @JMS\Accessor(setter="setRemoteUpdatedAt") */
    protected $remoteUpdatedAt;


    /**
     * {@inheritdoc}
     */
    public function generateId()
    {
        return sha1('pap_' . $this->remoteId);
    }

    /**
     * In (string) : 'Appartement, 66 m²'
     * Out         : 66
     *
     * {@inheritdoc}
     */
    public function setSurface($surface)
    {
        preg_match('/ (\d+) m²/', $surface, $matches);

        if (count($matches) > 0) {
            $this->surface = (float) $matches[1];

            return $this;
        }
        $this->surface = null;

        return $this;
    }

    /**
     * @param string $metros
     *
     * @return $this|\Accommodation
     */
    public function setMetros($metros)
    {
        $this->metros = explode(', ', $metros);

        return $this;
    }

    /**
     * @param \DateTime $remoteCreatedAt
     *
     * @return $this|\Accommodation
     */
    public function setRemoteCreatedAt($remoteCreatedAt)
    {
        $remoteCreatedAt->setTime(0, 0, 0);
        $this->remoteCreatedAt = $remoteCreatedAt;

        return $this;
    }

    /**
     * @param \DateTime $remoteUpdatedAt
     *
     * @return $this|\Accommodation
     */
    public function setRemoteUpdatedAt($remoteUpdatedAt)
    {
        $remoteUpdatedAt->setTime(0, 0, 0);
        $this->remoteUpdatedAt = $remoteUpdatedAt;

        return $this;
    }

    /**
     * IN : string(23) "5 pièces, 3 chambres"
     *      string(31) "3 pièces, de 1 à 2 chambres"
     *
     * out : 5
     *
     * @param string $rooms
     *
     * @return $this|\Accommodation
     */
    public function setRooms($rooms)
    {
        preg_match('/^(\d+) pièces,/', $rooms, $matches);

        if (count($matches) > 0) {
            $this->rooms = (int) $matches[1];

            return $this;
        }
        $this->rooms = null;

        return $this;
    }

    /**
     * Lookup setRooms
     *
     * {@inheritdoc}
     */
    public function setBedrooms($rooms)
    {
        preg_match('/, (\d+) chambres/', $rooms, $matches);

        if (count($matches) > 0) {
            $this->bedrooms = (int) $matches[1];

            return $this;
        }
        $this->bedrooms = null;

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

    /**
     * Get REST URL to find details about accomodation
     *
     * @return string
     */
    final public static function getDetailUrl()
    {
        return static::HOST . static::URL_DETAIL;
    }

    static public function getHeaders()
    {
        return array(
            'MDATA'   => '{"device":{"os":"android","model":"m0","udid":"'.uniqid(rand(100, 999)).'","language":"FR","version":"4.'.rand(0, 2).'.'.rand(0, 3).'","name":""},"bundle":{"identifier":"com.mobistep.pap","distribution":"com.mobistep.pap","version":"1.3"}}',
            'Cookie2' => '$Version=1',
            'Accept-Encoding'=>'gzip'
        );
    }

    public function setPrice($price)
    {
        $this->price = (double) str_replace(array(' ', '€'), array('', ''), $price);

        return $this;
    }

}
