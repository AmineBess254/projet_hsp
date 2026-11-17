<?php

class ReponseRepository
{
    private $connexionBdd;

    public function __construct()
    {
        $this->connexionBdd = (new Bdd())->getConnexionBdd();
    }

    private function creerReponse($result)
    {
        return new Reponse($result["id_reponse"], $result["contenu"], $result["date_heure"],
            $result["ref_utilisateur"], $result["ref_post"]);
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
        return $this->creerReponse($result);
    }

    public function getAllReponse()
    {
        $sql = "SELECT * FROM reponse ORDER BY date_heure";
        $req = $this->connexionBdd->prepare($sql);
        $req->execute();
        $tabReponse = array();
        foreach ($req->fetchAll() as $result) {
            $tabReponse[] = $this->creerReponse($result);
        }
        return $tabReponse;
    }

    public function getReponseParPost($idPost)
    {
        $sql = "SELECT * FROM reponse WHERE ref_post = :ref_post ORDER BY date_heure";
        $req = $this->connexionBdd->prepare($sql);
        $req->bindValue(':ref_post', $idPost);
        $req->execute();
        $tabReponse = array();
        foreach ($req->fetchAll() as $result) {
            $tabReponse[] = $this->creerReponse($result);
        }
        return $tabReponse;
    }

    public function getReponseParUtilisateur($idUtilisateur)
    {
        $sql = "SELECT * FROM reponse WHERE ref_utilisateur = :ref_utilisateur ORDER BY date_heure DESC";
        $req = $this->connexionBdd->prepare($sql);
        $req->bindValue(':ref_utilisateur', $idUtilisateur);
        $req->execute();
        $tabReponse = array();
        foreach ($req->fetchAll() as $result) {
            $tabReponse[] = $this->creerReponse($result);
        }
        return $tabReponse;
    }

    public function ajouterReponse(Reponse $reponse)
    {
        $sql = "INSERT INTO reponse (contenu, ref_utilisateur, ref_post)
                VALUES (:contenu, :ref_utilisateur, :ref_post)";
        $req = $this->connexionBdd->prepare($sql);
        $req->bindValue(':contenu', $reponse->getContenu());
        $req->bindValue(':ref_utilisateur', $reponse->getIdUtilisateur());
        $req->bindValue(':ref_post', $reponse->getIdPost());
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