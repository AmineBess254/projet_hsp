<?php

namespace repository;

class HopitalRepository
{
    private $connexionBdd;

    public function __construct()
    {
        $this->connexionBdd = (new Bdd())->getConnexionBdd();
    }

    public function getHopital($idHopital)
    {
        $sql = "SELECT * FROM hopital WHERE id_hopital = :id_hopital";
        $req = $this->connexionBdd->prepare($sql);
        $req->bindValue(':id_hopital', $idHopital);
        $req->execute();
        $result = $req->fetch();

        if (!$result) {
            return null;
        }

        $hopital = new Hopital($result["id_hopital"], $result["nom"], $result["adresse"]);
        return $hopital;
    }

    public function getAllHopital()
    {
        $sql = "SELECT * FROM hopital";
        $req = $this->connexionBdd->prepare($sql);
        $req->execute();
        $results = $req->fetchAll();
        $tabHopital = array();
        foreach ($results as $result) {
            $hopital = new Hopital($result["id_hopital"], $result["nom"], $result["adresse"]);
            $tabHopital[] = $hopital;
        }
        return $tabHopital;
    }

    public function ajouterHopital(Hopital $hopital)
    {
        $sql = "INSERT INTO hopital (nom, adresse) VALUES (:nom, :adresse)";
        $req = $this->connexionBdd->prepare($sql);
        $req->bindValue(':nom', $hopital->getNomHopital());
        $req->bindValue(':adresse', $hopital->getAdresseHopital());
        $req->execute();
    }

    public function modifierHopital(Hopital $hopital)
    {
        $sql = "UPDATE hopital SET nom = :nom, adresse = :adresse WHERE id_hopital = :id_hopital";
        $req = $this->connexionBdd->prepare($sql);
        $req->bindValue(':id_hopital', $hopital->getIdHopital());
        $req->bindValue(':nom', $hopital->getNomHopital());
        $req->bindValue(':adresse', $hopital->getAdresseHopital());
        $req->execute();
    }

    public function supprimerHopital($idHopital)
    {
        $sql = "DELETE FROM hopital WHERE id_hopital = :id_hopital";
        $req = $this->connexionBdd->prepare($sql);
        $req->bindValue(':id_hopital', $idHopital);
        $req->execute();
    }
}