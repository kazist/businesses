<?php

namespace Businesses\Businesses\Code\Tables;

use Doctrine\ORM\Mapping as ORM;

/**
 * Businesses
 *
 * @ORM\Table(name="businesses_businesses", indexes={@ORM\Index(name="category_id_index", columns={"category_id"}), @ORM\Index(name="country_id_index", columns={"country_id"}), @ORM\Index(name="package_id_index", columns={"package_id"}), @ORM\Index(name="package_price_id_index", columns={"package_price_id"})})
 * @ORM\Entity
 * @ORM\HasLifecycleCallbacks
 */
class Businesses extends \Kazist\Table\BaseTable
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="category_id", type="integer", length=11, nullable=true)
     */
    protected $category_id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    protected $name;

    /**
     * @var string
     *
     * @ORM\Column(name="profile", type="text", nullable=false)
     */
    protected $profile;

    /**
     * @var string
     *
     * @ORM\Column(name="physical_location", type="text", nullable=true)
     */
    protected $physical_location;

    /**
     * @var string
     *
     * @ORM\Column(name="address", type="text", nullable=true)
     */
    protected $address;

    /**
     * @var string
     *
     * @ORM\Column(name="phone", type="string", length=255, nullable=true)
     */
    protected $phone;

    /**
     * @var string
     *
     * @ORM\Column(name="email", type="string", length=255, nullable=true)
     */
    protected $email;

    /**
     * @var string
     *
     * @ORM\Column(name="website", type="string", length=255, nullable=true)
     */
    protected $website;

    /**
     * @var integer
     *
     * @ORM\Column(name="country_id", type="integer", length=11, nullable=true)
     */
    protected $country_id;

    /**
     * @var integer
     *
     * @ORM\Column(name="location_id", type="integer", length=11, nullable=true)
     */
    protected $location_id;

    /**
     * @var string
     *
     * @ORM\Column(name="city", type="string", length=255, nullable=true)
     */
    protected $city;

    /**
     * @var string
     *
     * @ORM\Column(name="latitude", type="string", length=255, nullable=true)
     */
    protected $latitude;

    /**
     * @var string
     *
     * @ORM\Column(name="longitude", type="string", length=255, nullable=true)
     */
    protected $longitude;

    /**
     * @var integer
     *
     * @ORM\Column(name="logo", type="integer", length=11, nullable=true)
     */
    protected $logo;

    /**
     * @var integer
     *
     * @ORM\Column(name="package_id", type="integer", length=11, nullable=true)
     */
    protected $package_id;

    /**
     * @var integer
     *
     * @ORM\Column(name="package_price_id", type="integer", length=11, nullable=true)
     */
    protected $package_price_id;

    /**
     * @var integer
     *
     * @ORM\Column(name="payment_amount", type="integer", length=11, nullable=true)
     */
    protected $payment_amount;

    /**
     * @var string
     *
     * @ORM\Column(name="payment_stage", type="string", length=255, nullable=true)
     */
    protected $payment_stage;

    /**
     * @var integer
     *
     * @ORM\Column(name="payment_status", type="integer", length=11, nullable=true)
     */
    protected $payment_status;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="payment_date", type="datetime", nullable=true)
     */
    protected $payment_date;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="payment_expiry", type="datetime", nullable=true)
     */
    protected $payment_expiry;

    /**
     * @var integer
     *
     * @ORM\Column(name="hit", type="integer", length=11, nullable=true)
     */
    protected $hit;

    /**
     * @var integer
     *
     * @ORM\Column(name="published", type="integer", length=11, nullable=true)
     */
    protected $published;

    /**
     * @var integer
     *
     * @ORM\Column(name="featured", type="integer", length=11, nullable=true)
     */
    protected $featured;

    /**
     * @var integer
     *
     * @ORM\Column(name="created_by", type="integer", length=11, nullable=false)
     */
    protected $created_by;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_created", type="datetime", nullable=false)
     */
    protected $date_created;

    /**
     * @var integer
     *
     * @ORM\Column(name="modified_by", type="integer", length=11, nullable=false)
     */
    protected $modified_by;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date_modified", type="datetime", nullable=false)
     */
    protected $date_modified;


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set category_id
     *
     * @param integer $categoryId
     * @return Businesses
     */
    public function setCategoryId($categoryId)
    {
        $this->category_id = $categoryId;

        return $this;
    }

    /**
     * Get category_id
     *
     * @return integer 
     */
    public function getCategoryId()
    {
        return $this->category_id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Businesses
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set profile
     *
     * @param string $profile
     * @return Businesses
     */
    public function setProfile($profile)
    {
        $this->profile = $profile;

        return $this;
    }

    /**
     * Get profile
     *
     * @return string 
     */
    public function getProfile()
    {
        return $this->profile;
    }

    /**
     * Set physical_location
     *
     * @param string $physicalLocation
     * @return Businesses
     */
    public function setPhysicalLocation($physicalLocation)
    {
        $this->physical_location = $physicalLocation;

        return $this;
    }

    /**
     * Get physical_location
     *
     * @return string 
     */
    public function getPhysicalLocation()
    {
        return $this->physical_location;
    }

    /**
     * Set address
     *
     * @param string $address
     * @return Businesses
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string 
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set phone
     *
     * @param string $phone
     * @return Businesses
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string 
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Businesses
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set website
     *
     * @param string $website
     * @return Businesses
     */
    public function setWebsite($website)
    {
        $this->website = $website;

        return $this;
    }

    /**
     * Get website
     *
     * @return string 
     */
    public function getWebsite()
    {
        return $this->website;
    }

    /**
     * Set country_id
     *
     * @param integer $countryId
     * @return Businesses
     */
    public function setCountryId($countryId)
    {
        $this->country_id = $countryId;

        return $this;
    }

    /**
     * Get country_id
     *
     * @return integer 
     */
    public function getCountryId()
    {
        return $this->country_id;
    }

    /**
     * Set location_id
     *
     * @param integer $locationId
     * @return Businesses
     */
    public function setLocationId($locationId)
    {
        $this->location_id = $locationId;

        return $this;
    }

    /**
     * Get location_id
     *
     * @return integer 
     */
    public function getLocationId()
    {
        return $this->location_id;
    }

    /**
     * Set city
     *
     * @param string $city
     * @return Businesses
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string 
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set latitude
     *
     * @param string $latitude
     * @return Businesses
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return string 
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set longitude
     *
     * @param string $longitude
     * @return Businesses
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return string 
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set logo
     *
     * @param integer $logo
     * @return Businesses
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;

        return $this;
    }

    /**
     * Get logo
     *
     * @return integer 
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * Set package_id
     *
     * @param integer $packageId
     * @return Businesses
     */
    public function setPackageId($packageId)
    {
        $this->package_id = $packageId;

        return $this;
    }

    /**
     * Get package_id
     *
     * @return integer 
     */
    public function getPackageId()
    {
        return $this->package_id;
    }

    /**
     * Set package_price_id
     *
     * @param integer $packagePriceId
     * @return Businesses
     */
    public function setPackagePriceId($packagePriceId)
    {
        $this->package_price_id = $packagePriceId;

        return $this;
    }

    /**
     * Get package_price_id
     *
     * @return integer 
     */
    public function getPackagePriceId()
    {
        return $this->package_price_id;
    }

    /**
     * Set payment_amount
     *
     * @param integer $paymentAmount
     * @return Businesses
     */
    public function setPaymentAmount($paymentAmount)
    {
        $this->payment_amount = $paymentAmount;

        return $this;
    }

    /**
     * Get payment_amount
     *
     * @return integer 
     */
    public function getPaymentAmount()
    {
        return $this->payment_amount;
    }

    /**
     * Set payment_stage
     *
     * @param string $paymentStage
     * @return Businesses
     */
    public function setPaymentStage($paymentStage)
    {
        $this->payment_stage = $paymentStage;

        return $this;
    }

    /**
     * Get payment_stage
     *
     * @return string 
     */
    public function getPaymentStage()
    {
        return $this->payment_stage;
    }

    /**
     * Set payment_status
     *
     * @param integer $paymentStatus
     * @return Businesses
     */
    public function setPaymentStatus($paymentStatus)
    {
        $this->payment_status = $paymentStatus;

        return $this;
    }

    /**
     * Get payment_status
     *
     * @return integer 
     */
    public function getPaymentStatus()
    {
        return $this->payment_status;
    }

    /**
     * Set payment_date
     *
     * @param \DateTime $paymentDate
     * @return Businesses
     */
    public function setPaymentDate($paymentDate)
    {
        $this->payment_date = $paymentDate;

        return $this;
    }

    /**
     * Get payment_date
     *
     * @return \DateTime 
     */
    public function getPaymentDate()
    {
        return $this->payment_date;
    }

    /**
     * Set payment_expiry
     *
     * @param \DateTime $paymentExpiry
     * @return Businesses
     */
    public function setPaymentExpiry($paymentExpiry)
    {
        $this->payment_expiry = $paymentExpiry;

        return $this;
    }

    /**
     * Get payment_expiry
     *
     * @return \DateTime 
     */
    public function getPaymentExpiry()
    {
        return $this->payment_expiry;
    }

    /**
     * Set hit
     *
     * @param integer $hit
     * @return Businesses
     */
    public function setHit($hit)
    {
        $this->hit = $hit;

        return $this;
    }

    /**
     * Get hit
     *
     * @return integer 
     */
    public function getHit()
    {
        return $this->hit;
    }

    /**
     * Set published
     *
     * @param integer $published
     * @return Businesses
     */
    public function setPublished($published)
    {
        $this->published = $published;

        return $this;
    }

    /**
     * Get published
     *
     * @return integer 
     */
    public function getPublished()
    {
        return $this->published;
    }

    /**
     * Set featured
     *
     * @param integer $featured
     * @return Businesses
     */
    public function setFeatured($featured)
    {
        $this->featured = $featured;

        return $this;
    }

    /**
     * Get featured
     *
     * @return integer 
     */
    public function getFeatured()
    {
        return $this->featured;
    }

    /**
     * Get created_by
     *
     * @return integer 
     */
    public function getCreatedBy()
    {
        return $this->created_by;
    }

    /**
     * Get date_created
     *
     * @return \DateTime 
     */
    public function getDateCreated()
    {
        return $this->date_created;
    }

    /**
     * Get modified_by
     *
     * @return integer 
     */
    public function getModifiedBy()
    {
        return $this->modified_by;
    }

    /**
     * Get date_modified
     *
     * @return \DateTime 
     */
    public function getDateModified()
    {
        return $this->date_modified;
    }
    /**
     * @ORM\PreUpdate
     */
    public function onPreUpdate()
    {
        // Add your code here
    }
}
