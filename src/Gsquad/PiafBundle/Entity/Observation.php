<?php

namespace Gsquad\PiafBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use DateTime;

/**
 * Piaf
 *
 * @ORM\Table(name="observation")
 * @ORM\Entity(repositoryClass="Gsquad\PiafBundle\Repository\ObservationRepository")
 */
class Observation
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(name="latitude", type="float", nullable=true)
     */
    private $latitude;

    /**
     * @ORM\Column(name="longitude", type="float", nullable=true)
     */
    private $longitude;

    /**
     * @ORM\Column(name="departement", type="string", length=255)
     */
    private $departement;

    /**
     * @ORM\Column(name="city", type="string", length=255)
     */
    private $city;

    /**
     * @ORM\Column(name="valid", type="boolean")
     */
    private $valid;

    /**
     * @ORM\Column(name="observateur", type="string", length=255, nullable=true)
     */
    private $observateur;

    /**
     * @ORM\Column(name="dateObservation", type="datetime", nullable=true)
     */
    private $dateObservation;

    /**
     * @ORM\Column(name="createdAt", type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(name="updatedAt", type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\OneToOne(targetEntity="Photo", cascade={"persist"})
     */
    private $photo;

    /**
     * @ORM\ManyToOne(targetEntity="Piaf", cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    protected $piaf;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->valid = false;
        $this->createdAt = new DateTime();
    }

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set latitude
     *
     * @param float $latitude
     *
     * @return Observation
     */
    public function setLatitude($latitude)
    {
        $this->latitude = $latitude;

        return $this;
    }

    /**
     * Get latitude
     *
     * @return float
     */
    public function getLatitude()
    {
        return $this->latitude;
    }

    /**
     * Set longitude
     *
     * @param float $longitude
     *
     * @return Observation
     */
    public function setLongitude($longitude)
    {
        $this->longitude = $longitude;

        return $this;
    }

    /**
     * Get longitude
     *
     * @return float
     */
    public function getLongitude()
    {
        return $this->longitude;
    }

    /**
     * Set departement
     *
     * @param string $departement
     *
     * @return Observation
     */
    public function setDepartement($departement)
    {
        $this->departement = $departement;

        return $this;
    }

    /**
     * Get departement
     *
     * @return string
     */
    public function getDepartement()
    {
        return $this->departement;
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
     * Set city
     *
     * @param string $city
     *
     * @return Observation
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get imgUrl
     *
     * @return string
     */
    public function getImgUrl()
    {
        return $this->imgUrl;
    }

    /**
     * Set imgUrl
     *
     * @param string $imgUrl
     *
     * @return Observation
     */
    public function setImgUrl($imgUrl)
    {
        $this->imgUrl = $imgUrl;

        return $this;
    }

    /**
     * Set valid
     *
     * @param boolean $valid
     *
     * @return Observation
     */
    public function setValid($valid)
    {
        $this->valid = $valid;

        return $this;
    }

    /**
     * Get valid
     *
     * @return boolean
     */
    public function getValid()
    {
        return $this->valid;
    }

    /**
     * Set observateur
     *
     * @param string $observateur
     *
     * @return Observation
     */
    public function setObservateur($observateur)
    {
        $this->observateur = $observateur;

        return $this;
    }

    /**
     * Get observateur
     *
     * @return string
     */
    public function getObservateur()
    {
        return $this->observateur;
    }

    /**
     * Set dateObservation
     *
     * @param \DateTime $dateObservation
     *
     * @return Observation
     */
    public function setDateObservation($dateObservation)
    {
        $this->dateObservation = $dateObservation;

        return $this;
    }

    /**
     * Get dateObservation
     *
     * @return \DateTime
     */
    public function getDateObservation()
    {
        return $this->dateObservation;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     *
     * @return Observation
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Observation
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Set piaf
     *
     * @param \Gsquad\PiafBundle\Entity\Piaf $piaf
     *
     * @return Observation
     */
    public function setPiaf(\Gsquad\PiafBundle\Entity\Piaf $piaf = null)
    {
        $this->piaf = $piaf;
        $piaf->setNbObservations($piaf->getNbObservations() + 1);

        return $this;
    }

    /**
     * Get piaf
     *
     * @return \Gsquad\PiafBundle\Entity\Piaf
     */
    public function getPiaf()
    {
        return $this->piaf;
    }

    /**
     * Set photo
     *
     * @param \Gsquad\PiafBundle\Entity\Photo $photo
     *
     * @return Observation
     */
    public function setPhoto(\Gsquad\PiafBundle\Entity\Photo $photo)
    {
        $this->photo = $photo;

        return $this;
    }

    /**
     * Get photo
     *
     * @return \Gsquad\PiafBundle\Entity\Photo
     */
    public function getPhoto()
    {
        return $this->photo;
    }
}
