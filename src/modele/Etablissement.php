<?php

class Etablissement
{
    private $id_etablissement;
    private $nom_etablissement;
    private $adresse_etablissement;
    private $siteweb_etablissement;

    /**
     * @param $id_etablissement
     * @param $nom_etablissement
     * @param $adresse_etablissement
     * @param $siteweb_etablissement
     */
    public function __construct($id_etablissement, $nom_etablissement, $adresse_etablissement, $siteweb_etablissement)
    {
        $this->id_etablissement = $id_etablissement;
        $this->nom = $nom_etablissement;
        $this->adresse = $adresse_etablissement;
        $this->site_web = $siteweb_etablissement;
    }

    /**
     * @return mixed
     */
    public function getIdEtablissement(){
        return $this->id_etablissement;
    }
    /**
     * @return mixed
     */
    public function getNomEtablissement(){
        return $this->nom_etablissement;
    }
    /**
     * @return mixed
     */
    public function getAdresseEtablissement(){
        return $this->adresse_etablissement;
    }
    /**
     * @return mixed
     */
    public function getSitewebEtablissement(){
        return $this->siteweb_etablissement;
    }
    /**
     * @param mixed $id_etablissement
     */
public function setIdEtablissements($id_etablissement){
    $this->id_etablissement = $id_etablissement;

}
    /**
     * @param mixed $nom_etablissment
     */
public function setNomEtablissements($nom_etablissement){
        $this->nom_etablissement = $nom_etablissement;
}
/**
 * @param mixed $adresse_etablissement
 */

public function setAdresseEtablissements($adresse_etablissement){
        $this->adresse_etablissement = $adresse_etablissement;
}
/**
 ** @param mixed $siteweb_etablissement
 */
public function setSitewebEtablissements($siteweb_etablissement){
        $this->siteweb_etablissement = $siteweb_etablissement;
}
}