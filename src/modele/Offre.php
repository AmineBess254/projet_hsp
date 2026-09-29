<?php
class Offre
{
    private $idOffre;
    private $typeOffre;
    private $titreOffre;
    private $dateOffre;
    private $descriptionOffre;
    private $missionsOffre;
    private $salaireOffre;
    private $etatOffre;
    private $refUtilisateur;

    /**
     * @param $idOffre
     * @param $typeOffre
     * @param $titreOffre
     * @param $dateOffre
     * @param $descriptionOffre
     * @param $missionsOffre
     * @param $salaireOffre
     * @param $etatOffre
     * @param $refUtilisateur
     */
    public function __construct($idOffre, $typeOffre, $titreOffre, $dateOffre, $descriptionOffre, $missionsOffre, $salaireOffre, $etatOffre, $refUtilisateur)
    {
        $this->idOffre = $idOffre;
        $this->typeOffre = $typeOffre;
        $this->titreOffre = $titreOffre;
        $this->dateOffre = $dateOffre;
        $this->descriptionOffre = $descriptionOffre;
        $this->missionsOffre = $missionsOffre;
        $this->salaireOffre = $salaireOffre;
        $this->etatOffre = $etatOffre;
        $this->refUtilisateur = $refUtilisateur;
    }


    public function getIdOffre()
    {
        return $this->idOffre;
    }

    public function setIdOffre($idOffre)
    {
        $this->idOffre = $idOffre;
    }


    public function getTypeOffre()
    {
        return $this->typeOffre;
    }

    public function setTypeOffre($typeOffre)
    {
        $this->typeOffre = $typeOffre;
    }


    public function getTitreOffre()
    {
        return $this->titreOffre;
    }

    public function setTitreOffre($titreOffre)
    {
        $this->titreOffre = $titreOffre;
    }


    public function getDateOffre()
    {
        return $this->dateOffre;
    }

    public function setDateOffre($dateOffre)
    {
        $this->dateOffre = $dateOffre;
    }


    public function getDescriptionOffre()
    {
        return $this->descriptionOffre;
    }

    public function setDescriptionOffre($descriptionOffre)
    {
        $this->descriptionOffre = $descriptionOffre;
    }


    public function getMissionsOffre()
    {
        return $this->missionsOffre;
    }

    public function setMissionsOffre($missionsOffre)
    {
        $this->missionsOffre = $missionsOffre;
    }


    public function getSalaireOffre()
    {
        return $this->salaireOffre;
    }

    public function setSalaireOffre($salaireOffre)
    {
        $this->salaireOffre = $salaireOffre;
    }


    public function getEtatOffre()
    {
        return $this->etatOffre;
    }

    public function setEtatOffre($etatOffre)
    {
        $this->etatOffre = $etatOffre;
    }


    public function getRefUtilisateur()
    {
        return $this->refUtilisateur;
    }

    public function setRefUtilisateur($refUtilisateur)
    {
        $this->refUtilisateur = $refUtilisateur;
    }
}