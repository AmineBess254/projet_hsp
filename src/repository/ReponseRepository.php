<?php

namespace repository;

class ReponseRepository
{
    private $connexionBdd;
    public function __construct()
    {
        $this->connexionBdd = (new Bdd())->getConnexionBdd();
    }
    public function getReponse($idReponse)
    {
        $sql = "SELECT * FROM reponse WHERE id_reponse = :id_reponse";
        $req = $this->connexionBdd->prepare($sql);
        $req->bindValue(':id_reponse', $idReponse);
        $req->execute();
        $result = $req->fetch();
        if (!$result) {
            return null;
        }
        $reponse = new Reponse($result["id_reponse"], $result["contenu"], $result["date_heure"],
            $result["id_utilisateur"], $result["id_post"]);
        return $reponse;
    }

    public function getAllReponse()
    {
        $sql = "SELECT * FROM reponse ORDER BY date_heure";
        $req = $this->connexionBdd->prepare($sql);
        $req->execute();
        $results = $req->fetchAll();
        $tabReponse = array();
        foreach ($results as $result) {
            $reponse = new Reponse($result["id_reponse"], $result["contenu"], $result["date_heure"],
                $result["id_utilisateur"], $result["id_post"]);
            $tabReponse[] = $reponse;
        }
        return $tabReponse;
    }


    public function getReponseParPost($idPost)
    {
        $sql = "SELECT * FROM reponse WHERE id_post = :id_post ORDER BY date_heure";
        $req = $this->connexionBdd->prepare($sql);
        $req->bindValue(':id_post', $idPost);
        $req->execute();
        $results = $req->fetchAll();
        $tabReponse = array();
        foreach ($results as $result) {
            $reponse = new Reponse($result["id_reponse"], $result["contenu"], $result["date_heure"],
                $result["id_utilisateur"], $result["id_post"]);
            $tabReponse[] = $reponse;
        }
        return $tabReponse;
    }


    public function getReponseParUtilisateur($idUtilisateur)
    {
        $sql = "SELECT * FROM reponse WHERE id_utilisateur = :id_utilisateur ORDER BY date_heure DESC";
        $req = $this->connexionBdd->prepare($sql);
        $req->bindValue(':id_utilisateur', $idUtilisateur);
        $req->execute();
        $results = $req->fetchAll();
        $tabReponse = array();
        foreach ($results as $result) {
            $reponse = new Reponse($result["id_reponse"], $result["contenu"], $result["date_heure"],
                $result["id_utilisateur"], $result["id_post"]);
            $tabReponse[] = $reponse;
        }
        return $tabReponse;
    }

    public function ajouterReponse(Reponse $reponse)
    {
        $sql = "INSERT INTO reponse (contenu, id_utilisateur, id_post) VALUES (:contenu, :id_utilisateur, :id_post)";
        $req = $this->connexionBdd->prepare($sql);
        $req->bindValue(':contenu', $reponse->getContenu());
        $req->bindValue(':id_utilisateur', $reponse->getIdUtilisateur());
        $req->bindValue(':id_post', $reponse->getIdPost());
        $req->execute();
        return $this->connexionBdd->lastInsertId();
    }

    public function modifierReponse(Reponse $reponse)
    {
        $sql = "UPDATE reponse SET contenu = :contenu WHERE id_reponse = :id_reponse";
        $req = $this->connexionBdd->prepare($sql);
        $req->bindValue(':id_reponse', $reponse->getIdReponse());
        $req->bindValue(':contenu', $reponse->getContenu());
        $req->execute();
    }

    public function supprimerReponse(Reponse $reponse)
    {
        $sql = "DELETE FROM reponse WHERE id_reponse = :id_reponse";
        $req = $this->connexionBdd->prepare($sql);
        $req->bindValue(':id_reponse', $reponse->getIdReponse());
        $req->execute();
    }

}