<?php

namespace Gsquad\PiafBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Piaf
 *
 * @ORM\Table(name="piaf")
 * @ORM\Entity(repositoryClass="Gsquad\PiafBundle\Repository\PiafRepository")
 */
class Piaf
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
     * @var string
     *
     * @ORM\Column(name="ordre", type="string", length=255)
     */
    private $ordre;

    /**
     * @var string
     *
     * @ORM\Column(name="family", type="string", length=255)
     */
    private $family;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="nameVern", type="string", length=255)
     */
    private $nameVern;

    /**
     * @var string
     *
     * @ORM\Column(name="nameVernEng", type="string", length=255)
     */
    private $nameVernEng;

    /**
     * @var string
     *
     * @ORM\Column(name="habitat", type="string", length=255)
     */
    private $habitat;


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
     * Set ordre
     *
     * @param string $ordre
     *
     * @return Piaf
     */
    public function setOrdre($ordre)
    {
        $this->ordre = $ordre;

        return $this;
    }

    /**
     * Get ordre
     *
     * @return string
     */
    public function getOrdre()
    {
        return $this->ordre;
    }

    /**
     * Set family
     *
     * @param string $family
     *
     * @return Piaf
     */
    public function setFamily($family)
    {
        $this->family = $family;

        return $this;
    }

    /**
     * Get family
     *
     * @return string
     */
    public function getFamily()
    {
        return $this->family;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return Piaf
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
     * Set nameVern
     *
     * @param string $nameVern
     *
     * @return Piaf
     */
    public function setNameVern($nameVern)
    {
        $this->nameVern = $nameVern;

        return $this;
    }

    /**
     * Get nameVern
     *
     * @return string
     */
    public function getNameVern()
    {
        return $this->nameVern;
    }

    /**
     * Set nameVernEng
     *
     * @param string $nameVernEng
     *
     * @return Piaf
     */
    public function setNameVernEng($nameVernEng)
    {
        $this->nameVernEng = $nameVernEng;

        return $this;
    }

    /**
     * Get nameVernEng
     *
     * @return string
     */
    public function getNameVernEng()
    {
        return $this->nameVernEng;
    }

    /**
     * Set habitat
     *
     * @param string $habitat
     *
     * @return Piaf
     */
    public function setHabitat($habitat)
    {
        $this->habitat = $habitat;

        return $this;
    }

    /**
     * Get habitat
     *
     * @return string
     */
    public function getHabitat()
    {
        return $this->habitat;
    }
}

