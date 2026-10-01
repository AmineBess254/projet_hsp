<?php

class Evenement
{
    private $id_evenement;
    private $type_evenement;
    private $titre_evenement;
    private $description_evenement;
    private $adresse_evenement;
    private $elementRequis_evenement;
    private $nbPlace_evenement;



    /**
     * @param $id_evenement
     * @param $type_evenement
     * @param $titre_evenement
     * @param $description_evenement
     * @param $adresse_evenement
     * @param $elementRequis_evenement
     * @param $nbPlace_evenement
     */
    public function __construct($id_evenement, $type_evenement, $titre_evenement, $description_evenement, $adresse_evenement, $elementRequis_evenement, $nbPlace_evenement)
    {
        $this->id_evenement = $id_evenement;
        $this->type_evenement = $type_evenement;
        $this->titre_evenement = $titre_evenement;
        $this->description_evenement = $description_evenement;
        $this->adresse_evenement = $adresse_evenement;
        $this->elementRequis_evenement = $elementRequis_evenement;
        $this->nbPlace_evenement = $nbPlace_evenement;
    }

    /**
     * @return mixed
     */
    public function getIdEvenement(){
        return $this->id_evenement;
    }
    /**
     * @return mixed
     */
    public function getTypeEvenement(){
        return $this->type_evenement;
    }
    /**
     * @return mixed
     */
    public function getAdresseEvenement(){
        return $this->adresse_evenement;
    }
    /**
     * @return mixed
     */
    public function getTitreEvenement(){
        return $this->titre_evenement;
    }
    public function getDescriptionEvenement(){
        return $this->description_evenement;
    }
    /**
     * @return mixed
     */
    public function getElementRequisEvenement(){
        return $this->elementRequis_evenement;
    }
    /**
     * @return mixed
     */
    public function getNbPlaceEvenement(){
        return $this->nbPlace_evenement;
    }
    /**
     * @param mixed $id_evenement
     */
    public function setIdEvenement($id_evenement){
        $this->id_evenement= $id_evenement;

    }
    /**
     * @param mixed $type_evenement
     */
    public function setTypeEvenement($type_evenement){
        $this->type_evenement = $type_evenement;
    }
    /**
     * @param mixed $titre_evenement
     */

    public function setTitreEvenement($titre_evenement){
        $this->titre_evenement = $titre_evenement;
    }
    /**
     ** @param mixed $adresse_evenement
     */
    public function setAdresseEvenement($adresse_evenement){
        $this->adresse_evenement = $adresse_evenement;
    }
    /**
     ** @param mixed $elementRequis_evenement
     */
    public function setElementRequis_evenement($elementRequis_evenement){
        $this->elementRequis_evenement = $elementRequis_evenement;
    }
    /**
     ** @param mixed $nbPlace_evenement
     */
    public function setNbPlaceEvenement($nbPlace_evenement){
        $this->nbPlace_evenement = $nbPlace_evenement;
    }
    /**
     ** @param mixed $description_evenement
     */
    public function setDescriptionEvenement($description_evenement){
        $this->description_evenement = $description_evenement;
    }


}