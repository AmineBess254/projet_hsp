<?php

class EtablissementRepository
{
    private $connexionBdd;

    public function __construct()
    {
        $this->connexionBdd = (new Bdd())->getConnexionBdd();
    }

    // Transforme une ligne de la base en objet Etablissement
    // (ordre du constructeur : id, nom, adresse, site_web)
    private function creerObjet($result)
    {
        return new Etablissement($result["id_etablissement"], $result["nom"], $result["adresse"], $result["site_web"]);
    }

    // Un établissement par son id
    public function getEtablissement($idEtablissement)
    {
        $req = $this->connexionBdd->prepare("SELECT * FROM etablissement WHERE id_etablissement = :id");
        $req->bindValue(':id', $idEtablissement);
        $req->execute();
        $result = $req->fetch();
        if (!$result) {
            return null;
        }
        return $this->creerObjet($result);
    }

    // Liste de tous les établissements (pour le menu déroulant du rattachement)
    public function getAllEtablissement()
    {
        $req = $this->connexionBdd->prepare("SELECT * FROM etablissement ORDER BY nom");
        $req->execute();
        $liste = array();
        foreach ($req->fetchAll() as $result) {
            $liste[] = $this->creerObjet($result);
        }
        return $liste;
    }

    // Cherche un établissement par son site web (contrôle des doublons)
    public function getEtablissementParSiteWeb($siteWeb)
    {
        $req = $this->connexionBdd->prepare("SELECT * FROM etablissement WHERE site_web = :site_web");
        $req->bindValue(':site_web', $siteWeb);
        $req->execute();
        $result = $req->fetch();
        if (!$result) {
            return null;
        }
        return $this->creerObjet($result);
    }

    // Ajoute un établissement et renvoie son id
    public function ajouterEtablissement(Etablissement $etablissement)
    {
        $req = $this->connexionBdd->prepare("INSERT INTO etablissement (nom, adresse, site_web) VALUES (:nom, :adresse, :site_web)");
        $req->bindValue(':nom', $etablissement->getNomEtablissement());
        $req->bindValue(':adresse', $etablissement->getAdresseEtablissement());
        $req->bindValue(':site_web', $etablissement->getSiteWebEtablissement());
        $req->execute();
        return $this->connexionBdd->lastInsertId();
    }
}