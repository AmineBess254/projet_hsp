<?php

class Etablissement
{
    private $idEtablissement;
    private $nomEtablissement;
    private $adresseEtablissement;
    private $siteWebEtablissement;

    public function __construct($idEtablissement, $nomEtablissement, $adresseEtablissement, $siteWebEtablissement)
    {
        $this->idEtablissement = $idEtablissement;
        $this->nomEtablissement = $nomEtablissement;
        $this->adresseEtablissement = $adresseEtablissement;
        $this->siteWebEtablissement = $siteWebEtablissement;
    }

    public function getIdEtablissement()
    {
        return $this->idEtablissement;
    }

    public function getNomEtablissement()
    {
        return $this->nomEtablissement;
    }

    public function getAdresseEtablissement()
    {
        return $this->adresseEtablissement;
    }

    public function getSiteWebEtablissement()
    {
        return $this->siteWebEtablissement;
    }
}