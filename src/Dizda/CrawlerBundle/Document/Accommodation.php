<?php
namespace Dizda\CrawlerBundle\Document;

use Doctrine\ODM\MongoDB\Mapping\Annotations as MongoDB;

use JMS\Serializer\Annotation as JMS;

/**
 * Flat, house, loft, ...
 *
 * @MongoDB\Document(repositoryClass="Dizda\CrawlerBundle\Document\Repository\AccommodationRepository")
 * @MongoDB\InheritanceType("SINGLE_COLLECTION")
 * @MongoDB\DiscriminatorField(fieldName="discriminatorType")
 * @MongoDB\DiscriminatorMap({"accommodation"= "Accommodation",
 *                            "seloger"      = "Seloger",
 *                            "explorimmo"   = "Explorimmo",
 *                            "pap"          = "Pap"})
 */
class Accommodation
{
    const WS_FORMAT           = null;
    const USER_AGENT          = null;

    const TYPE_TRANS_SELL     = 1;
    const TYPE_TRANS_LOCATION = 2;

    const TYPE_GOOD_HOUSE     = 1;
    const TYPE_GOOD_FLAT      = 2;
    const TYPE_GOOD_LOFT      = 3;

    /**
     * @JMS\Exclude
     */
    static protected $headers  = [];

    /**
     *  @JMS\Exclude
     *  @MongoDB\Id(strategy="none")
     */
    protected $id;
    /** @JMS\Groups({"rest"}) @JMS\SerializedName("id") @JMS\Accessor(getter="getId") @JMS\ReadOnly */
    protected $restId;

    /**
     *  Remote Foreign key to Query remote WS about accomodation id
     *  @JMS\Exclude
     *  @MongoDB\String */
    protected $remoteId;

    /**
     *  @JMS\Exclude
     *  @MongoDB\Int */
    protected $transactionType = self::TYPE_TRANS_LOCATION;

    /**
     *  @JMS\Exclude
     *  @MongoDB\Int */
    protected $goodType = self::TYPE_GOOD_FLAT;

    /**
     *  @JMS\Exclude
     *  @MongoDB\String */
    protected $title;
    /** @JMS\Groups({"rest"}) @JMS\SerializedName("title") @JMS\Accessor(getter="getTitle") @JMS\ReadOnly */
    protected $restTitle;

    /**
     *  @JMS\Exclude
     *  @MongoDB\String */
    protected $title2;
    /** @JMS\Groups({"rest"}) @JMS\SerializedName("title2") @JMS\Accessor(getter="getTitle2") @JMS\ReadOnly */
    protected $restTitle2;

    /**
     *  @JMS\Exclude
     *  @MongoDB\String */
    protected $district;
    /** @JMS\Groups({"rest"}) @JMS\SerializedName("district") @JMS\Accessor(getter="getDistrict") @JMS\ReadOnly */
    protected $restDistrict;

    /**
     *  @JMS\Exclude
     *  @MongoDB\String */
    protected $description;
    /** @JMS\Groups({"rest"}) @JMS\SerializedName("description") @JMS\Accessor(getter="getDescription") @JMS\ReadOnly */
    protected $restDescription;




    /**
     *  @JMS\Exclude
     *  @MongoDB\Float */
    protected $price;
    /** @JMS\Groups({"rest"}) @JMS\SerializedName("price") @JMS\Accessor(getter="getPrice") @JMS\ReadOnly */
    protected $restPrice;

    /**
     *  @JMS\Exclude
     *  @MongoDB\Float */
    protected $chargesAmount;
    /** @JMS\Groups({"rest"}) @JMS\SerializedName("chargesAmount") @JMS\Accessor(getter="getChargesAmount") @JMS\ReadOnly */
    protected $restChargesAmount;

    /**
     *  @JMS\Exclude
     *  @MongoDB\Boolean */
    protected $isChargesIncluded = false;

    /**
     *  @JMS\Exclude
     *  @MongoDB\Float */
    protected $agencyFees;

    /**
     *  @JMS\Exclude
     *  @MongoDB\Float */
    protected $depositFees;




    /**
     *  @JMS\Exclude
     *  @MongoDB\Int */
    protected $floor;

    /**
     *  @JMS\Exclude
     *  @MongoDB\Int */
    protected $rooms;

    /**
     *  @JMS\Exclude
     *  @MongoDB\Int */
    protected $bedrooms;

    /**
     *  @JMS\Exclude
     *  @MongoDB\Int */
    protected $bathroom;

    /**
     *  @JMS\Exclude
     *  @MongoDB\Int */
    protected $wcroom;

    /**
     *  @JMS\Exclude
     *  @MongoDB\Int */
    protected $terrasse;

    /**
     *  @JMS\Exclude
     *  @MongoDB\Float */
    protected $surface;
    /** @JMS\Groups({"rest"}) @JMS\SerializedName("surface") @JMS\Accessor(getter="getSurface") @JMS\ReadOnly */
    protected $restSurface;

    /**
     *  @JMS\Exclude
     *  @MongoDB\Boolean */
    protected $isSurfaceCertificated;

    /**
     *  @JMS\Exclude
     *  @MongoDB\String */
    protected $heating;

    /**
     *  @JMS\Exclude
     *  @MongoDB\Boolean */
    protected $cave;

    /**
     *  @JMS\Exclude
     *  @MongoDB\Boolean */
    protected $isRefurbished;





    /**
     *  @JMS\Exclude
     *  @MongoDB\Collection(strategy="pushAll") */
    protected $metros = array();

    /**
     *  @JMS\Exclude
     *  @MongoDB\String */
    protected $postalcode;

    /**
     *  @JMS\Exclude
     *  @MongoDB\String */
    protected $inseecode;

    /**
     *  @JMS\Exclude
     *  @MongoDB\String */
    protected $city;
    /** @JMS\Groups({"rest"}) @JMS\SerializedName("city") @JMS\Accessor(getter="getCity") @JMS\ReadOnly */
    protected $restCity;

    /**
     *  @JMS\Exclude
     *  @MongoDB\Collection(strategy="pushAll") */
    protected $photos = array();
    /** @JMS\Groups({"rest"}) @JMS\SerializedName("photos") @JMS\Accessor(getter="getPhotos") @JMS\ReadOnly */
    protected $restPhotos;

    /**
     *  @JMS\Exclude
     *  @MongoDB\String */
    protected $permalink;
    /** @JMS\Groups({"rest"}) @JMS\SerializedName("permalink") @JMS\Accessor(getter="getPermalink") @JMS\ReadOnly */
    protected $restPermalink;

    /**
     *  @JMS\Exclude
     *  @MongoDB\Float */
    protected $geoLat;

    /**
     *  @JMS\Exclude
     *  @MongoDB\Float */
    protected $geoLong;





    /**
     *  @JMS\Exclude
     *  @MongoDB\String */
    protected $contactName;

    /**
     *  @JMS\Exclude
     *  @MongoDB\String */
    protected $contactPhone;

    /**
     *  @JMS\Exclude
     *  @MongoDB\String */
    protected $contactPhone2;

    /**
     *  @JMS\Exclude
     *  @MongoDB\String */
    protected $contactEmail;

    /**
     *  @JMS\Exclude
     *  @MongoDB\String */
    protected $contactAddress;

    /**
     *  @JMS\Exclude
     *  @MongoDB\String */
    protected $contactGeoLat;

    /**
     *  @JMS\Exclude
     *  @MongoDB\String */
    protected $contactGeoLong;

    /** @JMS\Groups({"rest"}) @JMS\SerializedName("type") @JMS\Accessor(getter="getType") @JMS\ReadOnly */
    protected $restType;


    /**
     *  @JMS\Exclude
     *  @MongoDB\Int */
    protected $priority = 0;

    /**
     *  @JMS\Exclude
     *  @MongoDB\Boolean */
    protected $parent = true;

    /**
     *  @JMS\Exclude
     *  @MongoDB\Boolean */
    protected $fullDetail = true;

    /**
     *  @JMS\Exclude
     *  @MongoDB\Date */
    protected $remoteCreatedAt;

    /**
     *  @JMS\Exclude
     *  @MongoDB\Date */
    protected $remoteUpdatedAt;
    /** @JMS\Groups({"rest"}) @JMS\SerializedName("remoteUpdatedAt") @JMS\Accessor(getter="getRemoteUpdatedAt") @JMS\ReadOnly */
    protected $restRemoteUpdatedAt;

    /**
     *  @JMS\Exclude
     *  @MongoDB\Date */
    protected $localCreatedAt;

    /**
     *  @JMS\Exclude
     *  @MongoDB\Date */
    protected $localUpdatedAt;
    /** @JMS\Groups({"rest"}) @JMS\SerializedName("localUpdatedAt") @JMS\Accessor(getter="getLocalUpdatedAt") @JMS\ReadOnly */
    protected $restLocalUpdatedAt;

    /**
     * @JMS\Exclude
     * @MongoDB\ReferenceMany(targetDocument="Dizda\UserBundle\Document\User") */
    protected $viewed;
    /** @JMS\Groups({"rest"}) @JMS\SerializedName("viewed") @JMS\Accessor(getter="getRestViewed") @JMS\ReadOnly */
    protected $restViewed;

    /**
     * @JMS\Exclude
     * @MongoDB\ReferenceMany(targetDocument="Dizda\UserBundle\Document\User") */
    protected $hidden;

    /**
     * @JMS\Exclude
     * @MongoDB\ReferenceMany(targetDocument="Dizda\UserBundle\Document\User") */
    protected $favorites;
    /** @JMS\Groups({"rest"}) @JMS\SerializedName("favorites") @JMS\Accessor(getter="getRestFavorites") @JMS\ReadOnly */
    protected $restFavorites;


    /** @JMS\Exclude
     *  @MongoDB\EmbedMany(targetDocument="Dizda\SiteBundle\Document\Note") */
    protected $notes = array();
    /** @JMS\Groups({"rest"}) @JMS\SerializedName("notes") @JMS\Accessor(getter="getNotes") @JMS\ReadOnly */
    protected $restNotes;

    /**
     * @JMS\Exclude
     * @MongoDB\ReferenceMany(targetDocument="Dizda\CrawlerBundle\Document\Accommodation",
     *                        sort={"remoteUpdatedAt": "desc"}) */
    protected $versions;
    /** @JMS\Groups({"rest"}) @JMS\SerializedName("versions") @JMS\Accessor(getter="getVersions") @JMS\ReadOnly */
    protected $restVersions;

    /*
     * DON'T FORGET THE FOREIGN KEY TO WEBSITE AGENCY :-)
     */


    /** @MongoDB\PrePersist */
    public function prePersist()
    {
        // if getParent is false, so is a versioning, and we generate id with updatedDate value
        if ($this->getParent()) {
            $this->id             = $this->generateId();
        } else {
            $this->id             = $this->generateId(true);
        }
        $this->localCreatedAt = new \DateTime();
        $this->localUpdatedAt = new \DateTime();
    }

    /** @MongoDB\PreUpdate */
    public function preUpdated()
    {
        $this->localUpdatedAt = new \DateTime();
    }

    /**
     * Generate custom local id to store in Mongo, with remote creation date + remote id to be sure it's unique
     * And we hash-it
     *
     * @param bool $versioning Can generate id with 'updatedDate' to store few times same documents
     *
     * @return string
     */
    public function generateId($versioning = false)
    {
        $update       = '';
        $currentClass = explode('\\', get_class($this));

        if ($versioning) {
            $update = $this->getRemoteUpdatedAt()->format('YmdHis');
        }

        /**
         * BC-Break [before 04/05/2013] !! new id generation
         */
        if ( $this->remoteCreatedAt && ($this->remoteCreatedAt > (new \DateTime('2013-05-04'))->setTime(0, 0, 0)) ) {
            //     seloger _ 20130504203011 2131242312 (20130504203012)    : seloger_2013050418170078218825
            return sha1(strtolower(end($currentClass)) . '_' . $this->remoteCreatedAt->format('YmdHis') . $this->remoteId . $update);
        }

        /**
         * 04/05/2013
         * BC-Break for Pap
         */
        if (end($currentClass) == 'Pap') {
            return sha1('pap_' . $this->remoteId . $update);
        }

        return sha1($this->remoteCreatedAt->format('YmdHis') . $this->remoteId . $update . $update);
    }



    /*public function __toString() {
        return $this->date_transaction->format('d/m/Y') . ' - ' . $this->label . ' ' . $this->label2 . ' : ' . $this->amount;
    }*/


    /**
     * Set id
     *
     * @param custom_id $id
     * @return \Accommodation
     */
    public function setId($id)
    {
        $this->id = $id;
        return $this;
    }

    /**
     * Get id
     *
     * @return custom_id $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set transactionType
     *
     * @param int $transactionType
     * @return \Accommodation
     */
    public function setTransactionType($transactionType)
    {
        $this->transactionType = $transactionType;
        return $this;
    }

    /**
     * Get transactionType
     *
     * @return int $transactionType
     */
    public function getTransactionType()
    {
        return $this->transactionType;
    }

    /**
     * Set goodType
     *
     * @param int $goodType
     * @return \Accommodation
     */
    public function setGoodType($goodType)
    {
        $this->goodType = $goodType;
        return $this;
    }

    /**
     * Get goodType
     *
     * @return int $goodType
     */
    public function getGoodType()
    {
        return $this->goodType;
    }

    /**
     * Set title
     *
     * @param string $title
     * @return \Accommodation
     */
    public function setTitle($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * Get title
     *
     * @return string $title
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set title2
     *
     * @param string $title2
     * @return \Accommodation
     */
    public function setTitle2($title2)
    {
        $this->title2 = $title2;
        return $this;
    }

    /**
     * Get title2
     *
     * @return string $title2
     */
    public function getTitle2()
    {
        return $this->title2;
    }

    /**
     * Set district
     *
     * @param string $district
     * @return \Accommodation
     */
    public function setDistrict($district)
    {
        $this->district = $district;
        return $this;
    }

    /**
     * Get district
     *
     * @return string $district
     */
    public function getDistrict()
    {
        return $this->district;
    }

    /**
     * Set description
     *
     * @param string $description
     * @return \Accommodation
     */
    public function setDescription($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * Get description
     *
     * @return string $description
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set price
     *
     * @param float $price
     * @return \Accommodation
     */
    public function setPrice($price)
    {
        $this->price = $price;
        return $this;
    }

    /**
     * Get price
     *
     * @return float $price
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * Set chargesAmount
     *
     * @param float $chargesAmount
     * @return \Accommodation
     */
    public function setChargesAmount($chargesAmount)
    {
        $this->chargesAmount = $chargesAmount;
        return $this;
    }

    /**
     * Get chargesAmount
     *
     * @return float $chargesAmount
     */
    public function getChargesAmount()
    {
        return $this->chargesAmount;
    }

    /**
     * Set isChargesIncluded
     *
     * @param boolean $isChargesIncluded
     * @return \Accommodation
     */
    public function setIsChargesIncluded($isChargesIncluded)
    {
        $this->isChargesIncluded = $isChargesIncluded;
        return $this;
    }

    /**
     * Get isChargesIncluded
     *
     * @return boolean $isChargesIncluded
     */
    public function getIsChargesIncluded()
    {
        return $this->isChargesIncluded;
    }

    /**
     * Set agencyFees
     *
     * @param float $agencyFees
     * @return \Accommodation
     */
    public function setAgencyFees($agencyFees)
    {
        $this->agencyFees = $agencyFees;
        return $this;
    }

    /**
     * Get agencyFees
     *
     * @return float $agencyFees
     */
    public function getAgencyFees()
    {
        return $this->agencyFees;
    }

    /**
     * Set depositFees
     *
     * @param float $depositFees
     * @return \Accommodation
     */
    public function setDepositFees($depositFees)
    {
        $this->depositFees = $depositFees;
        return $this;
    }

    /**
     * Get depositFees
     *
     * @return float $depositFees
     */
    public function getDepositFees()
    {
        return $this->depositFees;
    }

    /**
     * Set floor
     *
     * @param int $floor
     * @return \Accommodation
     */
    public function setFloor($floor)
    {
        $this->floor = $floor;
        return $this;
    }

    /**
     * Get floor
     *
     * @return int $floor
     */
    public function getFloor()
    {
        return $this->floor;
    }

    /**
     * Set rooms
     *
     * @param int $rooms
     * @return \Accommodation
     */
    public function setRooms($rooms)
    {
        $this->rooms = $rooms;
        return $this;
    }

    /**
     * Get rooms
     *
     * @return int $rooms
     */
    public function getRooms()
    {
        return $this->rooms;
    }

    /**
     * Set bedrooms
     *
     * @param int $bedrooms
     * @return \Accommodation
     */
    public function setBedrooms($bedrooms)
    {
        $this->bedrooms = $bedrooms;
        return $this;
    }

    /**
     * Get bedrooms
     *
     * @return int $bedrooms
     */
    public function getBedrooms()
    {
        return $this->bedrooms;
    }

    /**
     * Set bathroom
     *
     * @param int $bathroom
     * @return \Accommodation
     */
    public function setBathroom($bathroom)
    {
        $this->bathroom = $bathroom;
        return $this;
    }

    /**
     * Get bathroom
     *
     * @return int $bathroom
     */
    public function getBathroom()
    {
        return $this->bathroom;
    }

    /**
     * Set wcroom
     *
     * @param int $wcroom
     * @return \Accommodation
     */
    public function setWcroom($wcroom)
    {
        $this->wcroom = $wcroom;
        return $this;
    }

    /**
     * Get wcroom
     *
     * @return int $wcroom
     */
    public function getWcroom()
    {
        return $this->wcroom;
    }

    /**
     * Set terrasse
     *
     * @param int $terrasse
     * @return \Accommodation
     */
    public function setTerrasse($terrasse)
    {
        $this->terrasse = $terrasse;
        return $this;
    }

    /**
     * Get terrasse
     *
     * @return int $terrasse
     */
    public function getTerrasse()
    {
        return $this->terrasse;
    }

    /**
     * Set surface
     *
     * @param float $surface
     * @return \Accommodation
     */
    public function setSurface($surface)
    {
        $this->surface = $surface;
        return $this;
    }

    /**
     * Get surface
     *
     * @return float $surface
     */
    public function getSurface()
    {
        return $this->surface;
    }

    /**
     * Set isSurfaceCertificated
     *
     * @param boolean $isSurfaceCertificated
     * @return \Accommodation
     */
    public function setIsSurfaceCertificated($isSurfaceCertificated)
    {
        $this->isSurfaceCertificated = $isSurfaceCertificated;
        return $this;
    }

    /**
     * Get isSurfaceCertificated
     *
     * @return boolean $isSurfaceCertificated
     */
    public function getIsSurfaceCertificated()
    {
        return $this->isSurfaceCertificated;
    }

    /**
     * Set heating
     *
     * @param string $heating
     * @return \Accommodation
     */
    public function setHeating($heating)
    {
        $this->heating = $heating;
        return $this;
    }

    /**
     * Get heating
     *
     * @return string $heating
     */
    public function getHeating()
    {
        return $this->heating;
    }

    /**
     * Set cave
     *
     * @param boolean $cave
     * @return \Accommodation
     */
    public function setCave($cave)
    {
        $this->cave = $cave;
        return $this;
    }

    /**
     * Get cave
     *
     * @return boolean $cave
     */
    public function getCave()
    {
        return $this->cave;
    }

    /**
     * Set isRefurbished
     *
     * @param boolean $isRefurbished
     * @return \Accommodation
     */
    public function setIsRefurbished($isRefurbished)
    {
        $this->isRefurbished = $isRefurbished;
        return $this;
    }

    /**
     * Get isRefurbished
     *
     * @return boolean $isRefurbished
     */
    public function getIsRefurbished()
    {
        return $this->isRefurbished;
    }

    /**
     * Set metros
     *
     * @param collection $metros
     * @return \Accommodation
     */
    public function setMetros($metros)
    {
        $this->metros = $metros;
        return $this;
    }

    /**
     * Get metros
     *
     * @return collection $metros
     */
    public function getMetros()
    {
        return $this->metros;
    }

    /**
     * Set postalcode
     *
     * @param string $postalcode
     * @return \Accommodation
     */
    public function setPostalcode($postalcode)
    {
        $this->postalcode = $postalcode;
        return $this;
    }

    /**
     * Get postalcode
     *
     * @return string $postalcode
     */
    public function getPostalcode()
    {
        return $this->postalcode;
    }

    /**
     * Set inseecode
     *
     * @param string $inseecode
     * @return \Accommodation
     */
    public function setInseecode($inseecode)
    {
        $this->inseecode = $inseecode;
        return $this;
    }

    /**
     * Get inseecode
     *
     * @return string $inseecode
     */
    public function getInseecode()
    {
        return $this->inseecode;
    }

    /**
     * Set city
     *
     * @param string $city
     * @return \Accommodation
     */
    public function setCity($city)
    {
        $this->city = $city;
        return $this;
    }

    /**
     * Get city
     *
     * @return string $city
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set photos
     *
     * @param collection $photos
     * @return \Accommodation
     */
    public function setPhotos($photos)
    {
        $this->photos = $photos;
        return $this;
    }

    /**
     * Get photos
     *
     * @return collection $photos
     */
    public function getPhotos()
    {
        return $this->photos;
    }

    /**
     * Set permalink
     *
     * @param string $permalink
     * @return \Accommodation
     */
    public function setPermalink($permalink)
    {
        $this->permalink = $permalink;
        return $this;
    }

    /**
     * Get permalink
     *
     * @return string $permalink
     */
    public function getPermalink()
    {
        return $this->permalink;
    }

    /**
     * Set geoLat
     *
     * @param string $geoLat
     * @return \Accommodation
     */
    public function setGeoLat($geoLat)
    {
        $this->geoLat = $geoLat;
        return $this;
    }

    /**
     * Get geoLat
     *
     * @return string $geoLat
     */
    public function getGeoLat()
    {
        return $this->geoLat;
    }

    /**
     * Set geoLong
     *
     * @param string $geoLong
     * @return \Accommodation
     */
    public function setGeoLong($geoLong)
    {
        $this->geoLong = $geoLong;
        return $this;
    }

    /**
     * Get geoLong
     *
     * @return string $geoLong
     */
    public function getGeoLong()
    {
        return $this->geoLong;
    }

    /**
     * Set contactName
     *
     * @param string $contactName
     * @return \Accommodation
     */
    public function setContactName($contactName)
    {
        $this->contactName = $contactName;
        return $this;
    }

    /**
     * Get contactName
     *
     * @return string $contactName
     */
    public function getContactName()
    {
        return $this->contactName;
    }

    /**
     * Set contactPhone
     *
     * @param string|array $contactPhone
     *
     * @return \Accommodation
     */
    public function setContactPhone($contactPhone)
    {
        if (is_array($contactPhone)) {
            $this->contactPhone = implode(',', $contactPhone);

            return $this;
        }
        $this->contactPhone = $contactPhone;

        return $this;
    }

    /**
     * Get contactPhone
     *
     * @return string $contactPhone
     */
    public function getContactPhone()
    {
        return $this->contactPhone;
    }

    /**
     * Set contactPhone2
     *
     * @param string $contactPhone
     * @return \Accommodation
     */
    public function setContactPhone2($contactPhone)
    {
        $this->contactPhone2 = $contactPhone;
        return $this;
    }

    /**
     * Get contactPhone2
     *
     * @return string $contactPhone
     */
    public function getContactPhone2()
    {
        return $this->contactPhone2;
    }

    /**
     * Set contactEmail
     *
     * @param string $contactEmail
     * @return \Accommodation
     */
    public function setContactEmail($contactEmail)
    {
        $this->contactEmail = $contactEmail;
        return $this;
    }

    /**
     * Get contactEmail
     *
     * @return string $contactEmail
     */
    public function getContactEmail()
    {
        return $this->contactEmail;
    }

    /**
     * Set contactAddress
     *
     * @param string $contactAddress
     * @return \Accommodation
     */
    public function setContactAddress($contactAddress)
    {
        $this->contactAddress = $contactAddress;
        return $this;
    }

    /**
     * Get contactAddress
     *
     * @return string $contactAddress
     */
    public function getContactAddress()
    {
        return $this->contactAddress;
    }

    /**
     * Set contactGeoLat
     *
     * @param string $contactGeoLat
     * @return \Accommodation
     */
    public function setContactGeoLat($contactGeoLat)
    {
        $this->contactGeoLat = $contactGeoLat;
        return $this;
    }

    /**
     * Get contactGeoLat
     *
     * @return string $contactGeoLat
     */
    public function getContactGeoLat()
    {
        return $this->contactGeoLat;
    }

    /**
     * Set contactGeoLong
     *
     * @param string $contactGeoLong
     * @return \Accommodation
     */
    public function setContactGeoLong($contactGeoLong)
    {
        $this->contactGeoLong = $contactGeoLong;
        return $this;
    }

    /**
     * Get contactGeoLong
     *
     * @return string $contactGeoLong
     */
    public function getContactGeoLong()
    {
        return $this->contactGeoLong;
    }

    /**
     * Set remoteCreatedAt
     *
     * @param date $remoteCreatedAt
     * @return \Accommodation
     */
    public function setRemoteCreatedAt($remoteCreatedAt)
    {
        $this->remoteCreatedAt = $remoteCreatedAt;
        return $this;
    }

    /**
     * Get remoteCreatedAt
     *
     * @return date $remoteCreatedAt
     */
    public function getRemoteCreatedAt()
    {
        return $this->remoteCreatedAt;
    }

    /**
     * Set remoteUpdatedAt
     *
     * @param date $remoteUpdatedAt
     * @return \Accommodation
     */
    public function setRemoteUpdatedAt($remoteUpdatedAt)
    {
        $this->remoteUpdatedAt = $remoteUpdatedAt;
        return $this;
    }

    /**
     * Get remoteUpdatedAt
     *
     * @return date $remoteUpdatedAt
     */
    public function getRemoteUpdatedAt()
    {
        return $this->remoteUpdatedAt;
    }

    /**
     * Set localCreatedAt
     *
     * @param date $localCreatedAt
     * @return \Accommodation
     */
    public function setLocalCreatedAt($localCreatedAt)
    {
        $this->localCreatedAt = $localCreatedAt;
        return $this;
    }

    /**
     * Get localCreatedAt
     *
     * @return date $localCreatedAt
     */
    public function getLocalCreatedAt()
    {
        return $this->localCreatedAt;
    }

    /**
     * Set localUpdatedAt
     *
     * @param date $localUpdatedAt
     * @return \Accommodation
     */
    public function setLocalUpdatedAt($localUpdatedAt)
    {
        $this->localUpdatedAt = $localUpdatedAt;
        return $this;
    }

    /**
     * Get localUpdatedAt
     *
     * @return date $localUpdatedAt
     */
    public function getLocalUpdatedAt()
    {
        return $this->localUpdatedAt;
    }

    /**
     * Set remoteId
     *
     * @param string $remoteId
     * @return \Accommodation
     */
    public function setRemoteId($remoteId)
    {
        $this->remoteId = $remoteId;
        return $this;
    }

    /**
     * Get remoteId
     *
     * @return string $remoteId
     */
    public function getRemoteId()
    {
        return $this->remoteId;
    }

    /**
     * Set fullDetail
     *
     * @param boolean $fullDetail
     * @return \Accommodation
     */
    public function setFullDetail($fullDetail)
    {
        $this->fullDetail = $fullDetail;
        return $this;
    }

    /**
     * Get fullDetail
     *
     * @return boolean $fullDetail
     */
    public function getFullDetail()
    {
        return $this->fullDetail;
    }

    static public function getHeaders()
    {
        return static::$headers;
    }

    /**
     * Get discriminator string and escape namespace name
     * obtain something like : "Seloger" or "Explorimmo", etc.
     *
     * @return mixed
     */
    public function getType()
    {
        $type = explode('\\', get_class($this));

        return end($type);
    }

    /**
     * Set priority
     *
     * @param int $priority
     * @return \Accommodation
     */
    public function setPriority($priority)
    {
        $this->priority = $priority;
        return $this;
    }

    /**
     * Get priority
     *
     * @return int $priority
     */
    public function getPriority()
    {
        return $this->priority;
    }

    public function __construct()
    {
        $this->viewed  = new \Doctrine\Common\Collections\ArrayCollection();
        $this->starred = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
 * Add viewed
 *
 * @param Dizda\UserBundle\Document\User $viewed
 */
    public function addViewed(\Dizda\UserBundle\Document\User $viewed)
    {
        $this->viewed[] = $viewed;
    }

    /**
     * Remove viewed
     *
     * @param <variableType$viewed
     */
    public function removeViewed(\Dizda\UserBundle\Document\User $viewed)
    {
        $this->viewed->removeElement($viewed);
    }

    /**
     * Get viewed
     *
     * @return Doctrine\Common\Collections\Collection $viewed
     */
    public function getViewed()
    {
        return $this->viewed;
    }

    public function clearViewed()
    {
        if ($this->getViewed()) {
            $this->viewed->clear();
        }
    }

    public function getRestViewed()
    {
        $views = [];
        foreach ($this->viewed as $view) {
            $views[] = $view->getId();
        }

        return $views;
    }

    /**
     * Add favorite
     *
     * @param Dizda\UserBundle\Document\User $starred
     */
    public function addFavorite(\Dizda\UserBundle\Document\User $favorite)
    {
        $this->favorites[] = $favorite;
    }

    /**
     * Remove favorite
     *
     * @param <variableType$favorites
     */
    public function removeFavorite(\Dizda\UserBundle\Document\User $favorite)
    {
        $this->favorites->removeElement($favorite);
    }

    /**
     * Get favorites
     *
     * @return Doctrine\Common\Collections\Collection $favorites
     */
    public function getFavorites()
    {
        return $this->favorites;
    }

    public function getRestFavorites()
    {
        $favs = [];
        foreach ($this->favorites as $favorite) {
            /*$favs[] = ['id' => $favorite->getId(),
                       'username' => $favorite->getUsername()];*/
            $favs[] = $favorite->getId();
        }

        return $favs;
    }

    /*public function getIsFavorite($user)
    {
        if (!$this->getFavorites()->contains($user)) {
            return false;
        } else {
            return true;
        }
    }*/

    /**
     * Add notes
     *
     * @param Dizda\SiteBundle\Document\Note $notes
     */
    public function addNote(\Dizda\SiteBundle\Document\Note $notes)
    {
        $this->notes[] = $notes;
    }

    /**
    * Remove notes
    *
    * @param <variableType$notes
    */
    public function removeNote(\Dizda\SiteBundle\Document\Note $notes)
    {
        $this->notes->removeElement($notes);
    }

    /**
     * Get notes
     *
     * @return Doctrine\Common\Collections\Collection $notes
     */
    public function getNotes()
    {
        return $this->notes;
    }




    /**
     * Add hidden
     *
     * @param Dizda\UserBundle\Document\User $hidden
     */
    public function addHidden(\Dizda\UserBundle\Document\User $hidden)
    {
        $this->hidden[] = $hidden;
    }

    /**
    * Remove hidden
    *
    * @param <variableType$hidden
    */
    public function removeHidden(\Dizda\UserBundle\Document\User $hidden)
    {
        $this->hidden->removeElement($hidden);
    }

    /**
     * Get hidden
     *
     * @return Doctrine\Common\Collections\Collection $hidden
     */
    public function getHidden()
    {
        return $this->hidden;
    }

    /**
     * Set parent
     *
     * @param boolean $parent
     * @return \Accommodation
     */
    public function setParent($parent)
    {
        $this->parent = $parent;
        return $this;
    }

    /**
     * Get parent
     *
     * @return boolean $parent
     */
    public function getParent()
    {
        return $this->parent;
    }

    /**
     * Add versions
     *
     * @param Dizda\CrawlerBundle\Document\Accommodation $versions
     */
    public function addVersion(\Dizda\CrawlerBundle\Document\Accommodation $versions)
    {
        $this->versions[] = $versions;
    }

    /**
    * Remove versions
    *
    * @param <variableType$versions
    */
    public function removeVersion(\Dizda\CrawlerBundle\Document\Accommodation $versions)
    {
        $this->versions->removeElement($versions);
    }

    /**
     * Get versions
     *
     * @return Doctrine\Common\Collections\Collection $versions
     */
    public function getVersions()
    {
        return $this->versions;
    }
}
