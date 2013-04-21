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
 * @JMS\XmlRoot("classified")
 */
class Explorimmo extends Accommodation
{
    const WS_FORMAT  = 'xml';
    const HOST       = 'http://www.explorimmo.com/';
    const URL_SEARCH = 'rest/iClassifieds';
    const URL_DETAIL = 'rest/iClassified';

    const USER_AGENT = 'Apache-HttpClient/UNAVAILABLE (java 1.4)';

    /**
     * @JMS\Exclude
     */
    public static $xmlSearch = array('<annonce>', '</annonce>', '<summary>', '</summary>', '<characteristics>',
                                     '</characteristics>', '<budget>', '</budget>', '<proximity>', '</proximity>', '<localisation>',
                                     '</localisation>', '<contact>', '</contact>');

    /**
     * @JMS\Exclude
     */
    public static $xmlReplace = array('<classified>', '</classified>', '', '', '', '', '', '', '', '', '', '', '', '');

    /**
     *  @JMS\Type("string")
     *  @JMS\SerializedName("id") */
    protected $remoteId;

    /**
     *  @JMS\Type("string")
     *  @JMS\SerializedName("misc") */
    protected $title;

    /**
     *  @JMS\Type("string")
     *  @JMS\SerializedName("localisation") */
    protected $district;

    /**
     *  @JMS\Type("string")
     *  @JMS\SerializedName("description") */
    protected $description;





    /**
     *  @JMS\Type("double")
     *  @JMS\SerializedName("amount") */
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
     *  @JMS\Type("integer")
     *  @JMS\SerializedName("nbRooms") */
    protected $rooms;

    /**
     *  @JMS\Type("integer")
     *  @JMS\SerializedName("nbBedrooms") */
    protected $bedrooms;

    /**
     *  @JMS\Type("double")
     *  @JMS\SerializedName("surface") */
    protected $surface;

    /**
     *  @JMS\Type("boolean")
     *  @JMS\SerializedName("surfaceCertification") */
    protected $isSurfaceCertificated;

    /**
     *  @JMS\Type("string")
     *  @JMS\SerializedName("postalCode") */
    protected $postalcode;

    /**
     *  @JMS\Type("string")
     *  @JMS\SerializedName("codeInsee") */
    protected $inseecode;

    /**
     *  @JMS\Type("string")
     *  @JMS\SerializedName("cityLabel") */
    protected $city;

    /**
     *  @JMS\Type("string")
     *  @JMS\SerializedName("permaLien") */
    protected $permalink;

    /**
     *  @JMS\Type("double")
     *  @JMS\SerializedName("latitudeCity") */
    protected $geoLat;

    /**
     *  @JMS\Type("double")
     *  @JMS\SerializedName("longitudeCity") */
    protected $geoLong;





    /**
     *  @JMS\Type("string")
     *  @JMS\SerializedName("name") */
    protected $contactName;

    /**
     *  @JMS\Type("string")
     *  @JMS\SerializedName("tel") */
    protected $contactPhone;

    /**
     *  @JMS\Type("string")
     *  @JMS\SerializedName("mobile") */
    protected $contactPhone2;

    /**
     *  @JMS\Type("string")
     *  @JMS\SerializedName("email") */
    protected $contactEmail;




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
     * Push every photos in collections
     *
     * @param collection $photos
     *
     * @return $this|\Accommodation
     */
    public function setPhotos($photos)
    {
        $collection = array();
        foreach ($photos as $photo) {

            if (strlen((string) $photo['url']) > 0) {
                $collection[] = (string) $photo['url'];
            }

        }
        $this->photos = $collection;

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

    public function getPermalink()
    {
        return 'http://www.explorimmo.com/annonce-'.$this->remoteId.'.html';
    }
}
