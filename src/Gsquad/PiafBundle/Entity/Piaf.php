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
     * @ORM\Column(name="cdRef", type="integer", nullable=true)
     */
    private $cdRef;

    /**
     *
     * @var string
     *
     * @ORM\Column(name="rang", type="string", nullable=true)
     */
    private $rang;

    /**
     *
     * @var string
     *
     * @ORM\Column(name="lbNom", type="string", nullable=true)
     */
    private $lbNom;

    /**
     *
     * @var string
     *
     * @ORM\Column(name="lbAuteur", type="string", nullable=true)
     */
    private $lbAuteur;

    /**
     *
     * @var string
     *
     * @ORM\Column(name="nomComplet", type="string", nullable=true)
     */
    private $nomComplet;

    /**
     *
     * @var string
     *
     * @ORM\Column(name="nomValide", type="string", nullable=true)
     */
    private $nomValide;

    /**
     *
     * @var string
     *
     * @ORM\Column(name="fr", type="string", nullable=true)
     */
    private $fr;

    /**
     *
     * @var string
     *
     * @ORM\Column(name="gf", type="string", nullable=true)
     */
    private $gf;

    /**
     *
     * @var string
     *
     * @ORM\Column(name="mar", type="string", nullable=true)
     */
    private $mar;

    /**
     *
     * @var string
     *
     * @ORM\Column(name="gua", type="string", nullable=true)
     */
    private $gua;

    /**
     *
     * @var string
     *
     * @ORM\Column(name="sm", type="string", nullable=true)
     */
    private $sm;

    /**
     *
     * @var string
     *
     * @ORM\Column(name="sb", type="string", nullable=true)
     */
    private $sb;

    /**
     *
     * @var string
     *
     * @ORM\Column(name="spm", type="string", nullable=true)
     */
    private $spm;

    /**
     *
     * @var string
     *
     * @ORM\Column(name="may", type="string", nullable=true)
     */
    private $may;

    /**
     *
     * @var string
     *
     * @ORM\Column(name="epa", type="string", nullable=true)
     */
    private $epa;

    /**
     *
     * @var string
     *
     * @ORM\Column(name="reu", type="string", nullable=true)
     */
    private $reu;

    /**
     *
     * @var string
     *
     * @ORM\Column(name="taaf", type="string", nullable=true)
     */
    private $taaf;

    /**
     *
     * @var string
     *
     * @ORM\Column(name="nc", type="string", nullable=true)
     */
    private $nc;

    /**
     *
     * @var string
     *
     * @ORM\Column(name="wf", type="string", nullable=true)
     */
    private $wf;

    /**
     *
     * @var string
     *
     * @ORM\Column(name="pf", type="string", nullable=true)
     */
    private $pf;

    /**
     *
     * @var string
     *
     * @ORM\Column(name="cli", type="string", nullable=true)
     */
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
     * @ORM\OneToOne(targetEntity="Photo")
     */
    private $photo;



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

    /**
     * Set phylum
     *
     * @param string $phylum
     *
     * @return Piaf
     */
    public function setPhylum($phylum)
    {
        $this->phylum = $phylum;

        return $this;
    }

    /**
     * Get phylum
     *
     * @return string
     */
    public function getPhylum()
    {
        return $this->phylum;
    }

    /**
     * Set classe
     *
     * @param string $classe
     *
     * @return Piaf
     */
    public function setClasse($classe)
    {
        $this->classe = $classe;

        return $this;
    }

    /**
     * Get classe
     *
     * @return string
     */
    public function getClasse()
    {
        return $this->classe;
    }

    /**
     * Set cdTaxSup
     *
     * @param integer $cdTaxSup
     *
     * @return Piaf
     */
    public function setCdTaxSup($cdTaxSup)
    {
        $this->cdTaxSup = $cdTaxSup;

        return $this;
    }

    /**
     * Get cdTaxSup
     *
     * @return integer
     */
    public function getCdTaxSup()
    {
        return $this->cdTaxSup;
    }

    /**
     * Set cdRef
     *
     * @param string $cdRef
     *
     * @return Piaf
     */
    public function setCdRef($cdRef)
    {
        $this->cdRef = $cdRef;

        return $this;
    }

    /**
     * Get cdRef
     *
     * @return string
     */
    public function getCdRef()
    {
        return $this->cdRef;
    }

    /**
     * Set rang
     *
     * @param string $rang
     *
     * @return Piaf
     */
    public function setRang($rang)
    {
        $this->rang = $rang;

        return $this;
    }

    /**
     * Get rang
     *
     * @return string
     */
    public function getRang()
    {
        return $this->rang;
    }

    /**
     * Set lbNom
     *
     * @param string $lbNom
     *
     * @return Piaf
     */
    public function setLbNom($lbNom)
    {
        $this->lbNom = $lbNom;

        return $this;
    }

    /**
     * Get lbNom
     *
     * @return string
     */
    public function getLbNom()
    {
        return $this->lbNom;
    }

    /**
     * Set lbAuteur
     *
     * @param string $lbAuteur
     *
     * @return Piaf
     */
    public function setLbAuteur($lbAuteur)
    {
        $this->lbAuteur = $lbAuteur;

        return $this;
    }

    /**
     * Get lbAuteur
     *
     * @return string
     */
    public function getLbAuteur()
    {
        return $this->lbAuteur;
    }

    /**
     * Set nomComplet
     *
     * @param string $nomComplet
     *
     * @return Piaf
     */
    public function setNomComplet($nomComplet)
    {
        $this->nomComplet = $nomComplet;

        return $this;
    }

    /**
     * Get nomComplet
     *
     * @return string
     */
    public function getNomComplet()
    {
        return $this->nomComplet;
    }

    /**
     * Set nomValide
     *
     * @param string $nomValide
     *
     * @return Piaf
     */
    public function setNomValide($nomValide)
    {
        $this->nomValide = $nomValide;

        return $this;
    }

    /**
     * Get nomValide
     *
     * @return string
     */
    public function getNomValide()
    {
        return $this->nomValide;
    }

    /**
     * Set fr
     *
     * @param string $fr
     *
     * @return Piaf
     */
    public function setFr($fr)
    {
        $this->fr = $fr;

        return $this;
    }

    /**
     * Get fr
     *
     * @return string
     */
    public function getFr()
    {
        return $this->fr;
    }

    /**
     * Set gf
     *
     * @param string $gf
     *
     * @return Piaf
     */
    public function setGf($gf)
    {
        $this->gf = $gf;

        return $this;
    }

    /**
     * Get gf
     *
     * @return string
     */
    public function getGf()
    {
        return $this->gf;
    }

    /**
     * Set mar
     *
     * @param string $mar
     *
     * @return Piaf
     */
    public function setMar($mar)
    {
        $this->mar = $mar;

        return $this;
    }

    /**
     * Get mar
     *
     * @return string
     */
    public function getMar()
    {
        return $this->mar;
    }

    /**
     * Set gua
     *
     * @param string $gua
     *
     * @return Piaf
     */
    public function setGua($gua)
    {
        $this->gua = $gua;

        return $this;
    }

    /**
     * Get gua
     *
     * @return string
     */
    public function getGua()
    {
        return $this->gua;
    }

    /**
     * Set sm
     *
     * @param string $sm
     *
     * @return Piaf
     */
    public function setSm($sm)
    {
        $this->sm = $sm;

        return $this;
    }

    /**
     * Get sm
     *
     * @return string
     */
    public function getSm()
    {
        return $this->sm;
    }

    /**
     * Set sb
     *
     * @param string $sb
     *
     * @return Piaf
     */
    public function setSb($sb)
    {
        $this->sb = $sb;

        return $this;
    }

    /**
     * Get sb
     *
     * @return string
     */
    public function getSb()
    {
        return $this->sb;
    }

    /**
     * Set spm
     *
     * @param string $spm
     *
     * @return Piaf
     */
    public function setSpm($spm)
    {
        $this->spm = $spm;

        return $this;
    }

    /**
     * Get spm
     *
     * @return string
     */
    public function getSpm()
    {
        return $this->spm;
    }

    /**
     * Set may
     *
     * @param string $may
     *
     * @return Piaf
     */
    public function setMay($may)
    {
        $this->may = $may;

        return $this;
    }

    /**
     * Get may
     *
     * @return string
     */
    public function getMay()
    {
        return $this->may;
    }

    /**
     * Set epa
     *
     * @param string $epa
     *
     * @return Piaf
     */
    public function setEpa($epa)
    {
        $this->epa = $epa;

        return $this;
    }

    /**
     * Get epa
     *
     * @return string
     */
    public function getEpa()
    {
        return $this->epa;
    }

    /**
     * Set reu
     *
     * @param string $reu
     *
     * @return Piaf
     */
    public function setReu($reu)
    {
        $this->reu = $reu;

        return $this;
    }

    /**
     * Get reu
     *
     * @return string
     */
    public function getReu()
    {
        return $this->reu;
    }

    /**
     * Set taaf
     *
     * @param string $taaf
     *
     * @return Piaf
     */
    public function setTaaf($taaf)
    {
        $this->taaf = $taaf;

        return $this;
    }

    /**
     * Get taaf
     *
     * @return string
     */
    public function getTaaf()
    {
        return $this->taaf;
    }

    /**
     * Set nc
     *
     * @param string $nc
     *
     * @return Piaf
     */
    public function setNc($nc)
    {
        $this->nc = $nc;

        return $this;
    }

    /**
     * Get nc
     *
     * @return string
     */
    public function getNc()
    {
        return $this->nc;
    }

    /**
     * Set wf
     *
     * @param string $wf
     *
     * @return Piaf
     */
    public function setWf($wf)
    {
        $this->wf = $wf;

        return $this;
    }

    /**
     * Get wf
     *
     * @return string
     */
    public function getWf()
    {
        return $this->wf;
    }

    /**
     * Set pf
     *
     * @param string $pf
     *
     * @return Piaf
     */
    public function setPf($pf)
    {
        $this->pf = $pf;

        return $this;
    }

    /**
     * Get pf
     *
     * @return string
     */
    public function getPf()
    {
        return $this->pf;
    }

    /**
     * Set cli
     *
     * @param string $cli
     *
     * @return Piaf
     */
    public function setCli($cli)
    {
        $this->cli = $cli;

        return $this;
    }

    /**
     * Get cli
     *
     * @return string
     */
    public function getCli()
    {
        return $this->cli;
    }

    /**
     * Set photo
     *
     * @param \Gsquad\PiafBundle\Entity\Photo $photo
     *
     * @return Piaf
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
