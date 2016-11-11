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
     *
     * @var string
     *
     * @ORM\Column(name="regne", type="string", nullable=true)
     */
    private $regne;

    /**
     *
     * @var string
     *
     * @ORM\Column(name="phylum", type="string", nullable=true)
     */
    private $phylum;

    /**
     *
     * @var string
     *
     * @ORM\Column(name="classe", type="string", nullable=false)
     */
    private $classe;

    /**
     * Numéro unique TAXREF
     * @var int
     *
     * @ORM\Column(name="cdNom", type="integer", nullable=false)
     */
    private $cdNom;

    /**
     *
     * @var int
     *
     * @ORM\Column(name="cdTaxSUp", type="integer", nullable=false)
     */
    private $cdTaxSup;

    /**
     *
     * @var int
     *
     * @ORM\Column(name="cdRef", type="string", nullable=true)
     */
    private $cdRef;

    /**
     *
     * @var string
     *
     * @ORM\Column(name="regne", type="string", nullable=true)
     */
    private $rang;
    private $lbNom;
    private $lbAuteur;
    private $nomComplet;
    private $nomValide;
    private $fr;
    private $gf;
    private $mar;
    private $gua;
    private $sm;
    private $sb;
    private $spm;
    private $may;
    private $epa;
    private $reu;
    private $taaf;
    private $nc;
    private $wf;
    private $pf;
    private $cli;

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
     * @ORM\Column(name="nameLatin", type="string", length=255)
     */
    private $nameLatin;

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
     * @var int
     *
     * @ORM\Column(name="observations", type="integer", nullable=true)
     */
    private $nbObservations;



    /**
     * Constructor
     */
    public function __construct()
    {
        $this->nbObservations = 0;
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
     * Set nameLatin
     *
     * @param string $nameLatin
     *
     * @return Piaf
     */
    public function setNameLatin($nameLatin)
    {
        $this->nameLatin = $nameLatin;

        return $this;
    }

    /**
     * Get nameLatin
     *
     * @return string
     */
    public function getNameLatin()
    {
        return $this->nameLatin;
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

    /**
     * Set nbObservations
     *
     * @param integer $nbObservations
     *
     * @return Piaf
     */
    public function setNbObservations($nbObservations)
    {
        $this->nbObservations = $nbObservations;

        return $this;
    }

    /**
     * Get nbObservations
     *
     * @return integer
     */
    public function getNbObservations()
    {
        return $this->nbObservations;
    }

    /**
     * @return int
     */
    public function getCdNom()
    {
        return $this->cdNom;
    }

    /**
     * @param int $cdNom
     */
    public function setCdNom($cdNom)
    {
        $this->cdNom = $cdNom;
    }

    /**
     * @return string
     */
    public function getRegne()
    {
        return $this->regne;
    }

    /**
     * @param string $regne
     */
    public function setRegne($regne)
    {
        $this->regne = $regne;
    }
}
