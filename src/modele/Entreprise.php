<?php

class Entreprise
{
    private $idEntreprise;
    private $nomEntreprise;
    private $siteWebEntreprise;
    private $adresseEntreprise;

    public function __construct(
        $idEntreprise,
        $nomEntreprise,
        $siteWebEntreprise,
        $adresseEntreprise
    ) {
        $this->idEntreprise = $idEntreprise;
        $this->nomEntreprise = $nomEntreprise;
        $this->siteWebEntreprise = $siteWebEntreprise;
        $this->adresseEntreprise = $adresseEntreprise;
    }

    public function getIdEntreprise()
    {
        return $this->idEntreprise;
    }

    public function getNomEntreprise()
    {
        return $this->nomEntreprise;
    }

    public function getSiteWebEntreprise()
    {
        return $this->siteWebEntreprise;
    }

    public function getAdresseEntreprise()
    {
        return $this->adresseEntreprise;
    }

    public function setIdEntreprise($idEntreprise)
    {
        $this->idEntreprise = $idEntreprise;
    }

    public function setNomEntreprise($nomEntreprise)
    {
        $this->nomEntreprise = $nomEntreprise;
    }

    public function setSiteWebEntreprise($siteWebEntreprise)
    {
        $this->siteWebEntreprise = $siteWebEntreprise;
    }

    public function setAdresseEntreprise($adresseEntreprise)
    {
        $this->adresseEntreprise = $adresseEntreprise;
    }
}