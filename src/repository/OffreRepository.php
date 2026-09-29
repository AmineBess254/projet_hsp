<?php

namespace repository;


class OffreRepository
{
    private $connexionBdd;

    public function __construct()
    {
        $this->connexionBdd = (new Bdd())->getConnexionBdd();
    }

    public function getOffre($idOffre)
    {
        $sql = "SELECT * FROM offre WHERE id_offre = :id_offre";
        $req = $this->connexionBdd->prepare($sql);
        $req->bindValue(':id_offre', $idOffre);
        $req->execute();
        $result = $req->fetch();

        if (!$result) {
            return null;
        }

        $offre = new Offre($result["id_offre"], $result["type_offre"], $result["titre"], $result["date"], $result["description"], $result["missions"], $result["salaire"], $result["etat"], $result["ref_utilisateur"]);
        return $offre;
    }

    public function getAllOffre()
    {
        $sql = "SELECT * FROM offre";
        $req = $this->connexionBdd->prepare($sql);
        $req->execute();
        $results = $req->fetchAll();
        $tabOffre = array();
        foreach ($results as $result) {
            $offre = new Offre($result["id_offre"], $result["type_offre"], $result["titre"], $result["date"], $result["description"], $result["missions"], $result["salaire"], $result["etat"], $result["ref_utilisateur"]);
            $tabOffre[] = $offre;
        }
        return $tabOffre;
    }

    public function ajouterOffre(Offre $offre)
    {
        $sql = "INSERT INTO offre (type_offre, titre, `date`, description, missions, salaire, etat, ref_utilisateur)
                VALUES (:type_offre, :titre, :date, :description, :missions, :salaire, :etat, :ref_utilisateur)";
        $req = $this->connexionBdd->prepare($sql);
        $req->bindValue(':type_offre', $offre->getTypeOffre());
        $req->bindValue(':titre', $offre->getTitreOffre());
        $req->bindValue(':date', $offre->getDateOffre());
        $req->bindValue(':description', $offre->getDescriptionOffre());
        $req->bindValue(':missions', $offre->getMissionsOffre());
        $req->bindValue(':salaire', $offre->getSalaireOffre());
        $req->bindValue(':etat', $offre->getEtatOffre());
        $req->bindValue(':ref_utilisateur', $offre->getRefUtilisateur());
        $req->execute();
    }

    public function modifierOffre(Offre $offre)
    {
        $sql = "UPDATE offre SET type_offre = :type_offre, titre = :titre, `date` = :date, description = :description, missions = :missions, salaire = :salaire, etat = :etat, ref_utilisateur = :ref_utilisateur
                WHERE id_offre = :id_offre";
        $req = $this->connexionBdd->prepare($sql);
        $req->bindValue(':id_offre', $offre->getIdOffre());
        $req->bindValue(':type_offre', $offre->getTypeOffre());
        $req->bindValue(':titre', $offre->getTitreOffre());
        $req->bindValue(':date', $offre->getDateOffre());
        $req->bindValue(':description', $offre->getDescriptionOffre());
        $req->bindValue(':missions', $offre->getMissionsOffre());
        $req->bindValue(':salaire', $offre->getSalaireOffre());
        $req->bindValue(':etat', $offre->getEtatOffre());
        $req->bindValue(':ref_utilisateur', $offre->getRefUtilisateur());
        $req->execute();
    }

    public function supprimerOffre($idOffre)
    {
        $sql = "DELETE FROM offre WHERE id_offre = :id_offre";
        $req = $this->connexionBdd->prepare($sql);
        $req->bindValue(':id_offre', $idOffre);
        $req->execute();
    }
}