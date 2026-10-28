<?php

class EvenementRepository
{
    private $connexionBdd;
    public function __construct()
    {
        $this->connexionBdd = (new Bdd())->getConnexionBdd();
    }

    public function getAllEvenements(){
        $sql = "SELECT * FROM evenement";
        $req = $this->connexionBdd->prepare($sql);
        $req->execute();
        $results = $req->fetchAll();
        $tabEvenement = array();
        foreach ($results as $result) {
            $evenement = new Evenement($result["id_evenement"],$result["titre"],$result["type"],$result["description"],$result["adresse"],$result["element_requis"],$result["nb_place"]);
            $tabEvenement[] = $evenement;
        }
        return $tabEvenement;
    }

    public function ajouterEvenement(Evenement $evenement){
        $sql = "INSERT INTO evenement (type, titre, description, adresse, date_evenement, element_requis, nb_place)
            VALUES (:type, :titre, :description, :adresse, :date, :element_requis, :nb_place)";
        $req = $this->connexionBdd->prepare($sql);
        $req->bindValue(':type', $evenement->getTypeEvenement());
        $req->bindValue(':titre', $evenement->getTitreEvenement());
        $req->bindValue(':description', $evenement->getDescriptionEvenement());
        $req->bindValue(':adresse', $evenement->getAdresseEvenement());
        $req->bindValue(':date', $evenement->getDateEvenement());
        $req->bindValue(':element_requis', $evenement->getElementRequisEvenement());
        $req->bindValue(':nb_place', $evenement->getNbPlaceEvenement());
        $req->execute();

        return $this->connexionBdd->lastInsertId();
    }

    public function ajouterOrganisateur($idEvenement, $idUtilisateur){
        $sql = "INSERT INTO organisateur (ref_evenement, ref_utilisateur) VALUES (:ref_evenement, :ref_utilisateur)";
        $req = $this->connexionBdd->prepare($sql);
        $req->bindValue(':ref_evenement', $idEvenement);
        $req->bindValue(':ref_utilisateur', $idUtilisateur);
        $req->execute();
    }
    public function peutCreerEvenement($idUtilisateur){
        $sql = "SELECT EXISTS(SELECT 1 FROM medecin WHERE id_utilisateur = :id1)
                OR EXISTS(SELECT 1 FROM partenaire WHERE id_utilisateur = :id2) AS autorise";
        $req = $this->connexionBdd->prepare($sql);
        $req->bindValue(':id1', $idUtilisateur);
        $req->bindValue(':id2', $idUtilisateur);
        $req->execute();
        return (bool) $req->fetch()['autorise'];
    }
    public function modifierEvenement(Evenement $evenement){
        $sql= "UPDATE evenement SET evenement = :evenement,  titre = :titre, type = :type, description = :description, adresse = :adresse, date_evenement=:date, element_requis = :element_requis, nb_place = :nb_place";
        $req = $this->connexionBdd->prepare($sql);
        $req->bindValue(':evenement', $evenement->getIdEvenement());
        $req->bindValue(':type', $evenement->getTypeEvenement());
        $req->bindValue(':titre', $evenement->getTitreEvenement());
        $req->bindValue(':description', $evenement->getDescriptionEvenement());
        $req->bindValue(':adresse', $evenement->getAdresseEvenement());
        $req->bindValue(':date', $evenement->getDateEvenement());
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