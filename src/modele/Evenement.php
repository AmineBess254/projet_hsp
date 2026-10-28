<?php

class Evenement
{
    private $id_evenement;
    private $type_evenement;
    private $titre_evenement;
    private $description_evenement;
    private $adresse_evenement;
    private $date_evenement;
    private $elementRequis_evenement;
    private $nbPlace_evenement;
    // Nombre d'inscrits acceptés (calculé par la requête, pas une colonne de la table)
    private $nbInscrits_evenement;

    public function __construct($id_evenement, $type_evenement, $titre_evenement, $description_evenement, $adresse_evenement, $date_evenement, $elementRequis_evenement, $nbPlace_evenement, $nbInscrits_evenement = 0)
    {
        $this->id_evenement = $id_evenement;
        $this->type_evenement = $type_evenement;
        $this->titre_evenement = $titre_evenement;
        $this->description_evenement = $description_evenement;
        $this->adresse_evenement = $adresse_evenement;
        $this->date_evenement = $date_evenement;
        $this->elementRequis_evenement = $elementRequis_evenement;
        $this->nbPlace_evenement = $nbPlace_evenement;
        $this->nbInscrits_evenement = $nbInscrits_evenement;
    }

    public function getIdEvenement() { return $this->id_evenement; }
    public function getTypeEvenement() { return $this->type_evenement; }
    public function getTitreEvenement() { return $this->titre_evenement; }
    public function getDescriptionEvenement() { return $this->description_evenement; }
    public function getAdresseEvenement() { return $this->adresse_evenement; }
    public function getDateEvenement() { return $this->date_evenement; }
    public function getElementRequisEvenement() { return $this->elementRequis_evenement; }
    public function getNbPlaceEvenement() { return $this->nbPlace_evenement; }
    public function getNbInscritsEvenement() { return $this->nbInscrits_evenement; }

    // Places restantes = places totales - inscrits acceptés (jamais en dessous de 0)
    public function getPlacesRestantes()
    {
        return max(0, $this->nbPlace_evenement - $this->nbInscrits_evenement);
    }

    // Vrai si la date de l'événement est déjà passée
    public function estPasse()
    {
        return $this->date_evenement < date('Y-m-d');
    }

    public function setIdEvenement($id_evenement) { $this->id_evenement = $id_evenement; }
    public function setTypeEvenement($type_evenement) { $this->type_evenement = $type_evenement; }
    public function setTitreEvenement($titre_evenement) { $this->titre_evenement = $titre_evenement; }
    public function setDescriptionEvenement($description_evenement) { $this->description_evenement = $description_evenement; }
    public function setAdresseEvenement($adresse_evenement) { $this->adresse_evenement = $adresse_evenement; }
    public function setDateEvenement($date_evenement) { $this->date_evenement = $date_evenement; }
    public function setElementRequisEvenement($elementRequis_evenement) { $this->elementRequis_evenement = $elementRequis_evenement; }
    public function setNbPlaceEvenement($nbPlace_evenement) { $this->nbPlace_evenement = $nbPlace_evenement; }
}
