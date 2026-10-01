<?php

namespace repository;
class EvenementRepository
{
    public function getAllEvenements(){
        $sql = "SELECT * FROM evenement";
        $req = $this->connexionBdd->prepare($sql);
        $req->execute();
        $results = $req->fetchAll();
        $tabEvenement = array();
        foreach ($results as $result) {
            $evenement = new Evenement($result["id_evenement"],$result["type"],$result["titre"],$result["description"],$result["adresse"],$result["element_requis"],$result["nb_place"]);
            $tabEvenement[] = $evenement;
        }
        return $tabEvenement;
    }

    public function ajouterEvenement(Evenement $evenement){
        $sql= "INSERT INTO evenement (evenement, type, titre, description, adresse, element_requis, nb_place) 
                Values (:evenement,:type,:titre,:description,:adresse,:element_requis,:nb_place)";
        $req = $this->connexionBdd->prepare($sql);
        $req->bindValue(':evenement', $evenement->getEvenement());
        $req->bindValue(':type', $evenement->getTypeEvenement());
        $req->bindValue(':titre', $evenement->getTitreEvenement());
        $req->bindValue(':description', $evenement->getDescriptionEvenement());
        $req->bindValue(':adresse', $evenement->getAdresseEvenement());
        $req->bindValue(':element_requis', $evenement->getElementRequisEvenement());
        $req->bindValue(':nb_place', $evenement->getNbPlaceEvenement());
        $req->execute();
    }
    public function modifierEvenement(Evenement $evenement){
        $sql= "UPDATE evenement SET evenement = :evenement, type = :type, titre = :titre, description = :description, adresse = :adresse, element_requis = :element_requis, nb_place = :nb_place";
        $req = $this->connexionBdd->prepare($sql);
        $req->bindValue(':evenement', $evenement->getevenement());
        $req->bindValue(':type', $evenement->getTypeEvenement());
        $req->bindValue(':titre', $evenement->getTitreEvenement());
        $req->bindValue(':description', $evenement->getDescriptionEvenement());
        $req->bindValue(':adresse', $evenement->getAdresseEvenement());
        $req->bindValue(':element_requis', $evenement->getElementRequisEvenement());
        $req->bindValue(':nb_place', $evenement->getNbPlaceEvenement());
        $req->execute();
    }
    public function supprimerEvenement($id_evenement){
        $sql= "DELETE FROM evenement WHERE id_evenement = :id_evenement";
        $req = $this->connexionBdd->prepare($sql);
        $req->bindValue(':id_evenement', $id_evenement);
        $req->execute();
    }


}