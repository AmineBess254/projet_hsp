<?php
class SpecialiteRepository{

    private $connexionBdd;

    public function __construct(){
        $this->connexionBdd = (new Bdd())->getConnexionBdd();
    }

    public function getSpecialites($idSpecialite){
        $sql = "Select * from specialite where id_specialite = :id_specialite";
        $req = $this->connexionBdd->prepare($sql);
        $req->bindValue(':id_specialite', $idSpecialite);
        $req->execute();
        $result = $req->fetch();

        if (!$result) {
            return null;
        }
        $specialite = new Specialite($result["id_specialite"], $result["nom"]);
        return $specialite;

    }

    public function getAllSpecialites(){
        $sql = "select * from specialite";
        $req = $this->connexionBdd->prepare($sql);
        $req->execute();
        $result = $req->fetchAll();
        $tabSpecialites = array();
        foreach ($result as $specialite) {
            $specialite = new Specialite($specialite["id_specialite"], $specialite["nom"]);
            $tabSpecialites[]= $specialite;

        }

        return $tabSpecialites;
    }
    public function ajouterSpecialite($specialite){
        $sql = "Insert into specialite (id_specialite, nom) values (:id_specialite, :nom)";
        $req = $this->connexionBdd->prepare($sql);
        $req->bindValue(":id_specialite", $specialite->getIdSpecialite());
        $req->bindValue(":nom", $specialite->getNomSpecialite());
        $req->execute();
    }
    public function supprimerSpecialite($idSpecialite){
        $sql = "delete from specialite where id_specialite = :id_specialite";
        $req = $this->connexionBdd->prepare($sql);
        $req->bindValue(":id_specialite", $idSpecialite);
        $req->execute();
    }
    public function modifierSpecialite($specialite,$idSpecialite){
        $sql = "update specialite set nom = :nom where id_specialite = :id_specialite";
        $req = $this->connexionBdd->prepare($sql);
        $req->bindValue(":id_specialite", $idSpecialite);
        $req->bindValue(":nom", $specialite->getNomSpecialite());
        $req->execute();
    }



}
