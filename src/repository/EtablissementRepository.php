<?php

namespace repository;
class EtablissementRepository
{
    public function getAllEtablissements(){
        $sql = "SELECT * FROM etablissement";
        $req = $this->connexionBdd->prepare($sql);
        $req->execute();
        $results = $req->fetchAll();
        $tabEtablissement = array();
        foreach ($results as $result) {
            $etablissement = new Etablissement($result["id_etablissement"],$result["nom"],$result["adresse"],$result["site_web"]);
            $tabEtablissement[] = $etablissement;
        }
        return $tabEtablissement;
    }

    public function ajouterEtablissement(Etablissement $etablissement){
        $sql= "INSERT INTO etablissement (etablissement, nom, adresse, site_web) 
                Values (:etablissement,:nom,:adresse,:site_web)";
        $req = $this->connexionBdd->prepare($sql);
        $req->bindValue(':etablissement', $etablissement->getEtablissement());
        $req->bindValue(':nom', $etablissement->getNomEtablissement());
        $req->bindValue(':adresse', $etablissement->getAdresse());
        $req->bindValue(':site_web', $etablissement->getSiteWeb());
        $req->execute();
    }
    public function modifierEtablissement(Etablissement $etablissement){
        $sql= "UPDATE etablissement SET etablissement = :etablissement, nom = :nom, adresse = :adresse, site_web = :site_web";
        $req = $this->connexionBdd->prepare($sql);
        $req->bindValue(':etablissement', $etablissement->getetablissement());
        $req->bindValue(':nom', $etablissement->getNomEtablissement());
        $req->bindValue(':adresse', $etablissement->getAdresse());
        $req->bindValue(':site_web', $etablissement->getSiteWeb());
        $req->execute();
    }
    public function supprimerEtablissement($id_etablissement){
        $sql= "DELETE FROM etablissement WHERE id_etablissement = :id_etablissement";
        $req = $this->connexionBdd->prepare($sql);
        $req->bindValue(':id_etablissement', $id_etablissement);
        $req->execute();
    }


}