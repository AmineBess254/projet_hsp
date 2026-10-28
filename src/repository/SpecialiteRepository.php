<?php

class SpecialiteRepository
{
    private $connexionBdd;

    public function __construct()
    {
        $this->connexionBdd = (new Bdd())->getConnexionBdd();
    }

    // Liste de toutes les spécialités (pour le menu déroulant de l'inscription)
    public function getAllSpecialite()
    {
        $req = $this->connexionBdd->prepare("SELECT * FROM specialite ORDER BY nom");
        $req->execute();
        $liste = array();
        foreach ($req->fetchAll() as $result) {
            $liste[] = new Specialite($result["id_specialite"], $result["nom"]);
        }
        return $liste;
    }
}
