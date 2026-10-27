<?php

class EntrepriseRepository
{
    private $connexionBdd;

    public function __construct()
    {
        $this->connexionBdd = (new Bdd())->getConnexionBdd();
    }

    // Transforme une ligne de la base en objet Entreprise
    private function creerObjet($result)
    {
        return new Entreprise($result["id_entreprise"], $result["nom"], $result["site_web"], $result["adresse"]);
    }

    // Une entreprise par son id
    public function getEntreprise($idEntreprise)
    {
        $req = $this->connexionBdd->prepare("SELECT * FROM entreprise WHERE id_entreprise = :id");
        $req->bindValue(':id', $idEntreprise);
        $req->execute();
        $result = $req->fetch();
        if (!$result) {
            return null;
        }
        return $this->creerObjet($result);
    }

    // Liste de toutes les entreprises (pour le menu déroulant)
    public function getAllEntreprise()
    {
        $req = $this->connexionBdd->prepare("SELECT * FROM entreprise ORDER BY nom");
        $req->execute();
        $liste = array();
        foreach ($req->fetchAll() as $result) {
            $liste[] = $this->creerObjet($result);
        }
        return $liste;
    }

    // Cherche une entreprise par son site web (contrôle des doublons)
    public function getEntrepriseParSiteWeb($siteWeb)
    {
        $req = $this->connexionBdd->prepare("SELECT * FROM entreprise WHERE site_web = :site_web");
        $req->bindValue(':site_web', $siteWeb);
        $req->execute();
        $result = $req->fetch();
        if (!$result) {
            return null;
        }
        return $this->creerObjet($result);
    }

    // Vrai si une entreprise a déjà ce site web
    public function siteWebExiste($siteWeb)
    {
        return $this->getEntrepriseParSiteWeb($siteWeb) != null;
    }

    // Ajoute une entreprise et renvoie son id
    public function ajouterEntreprise(Entreprise $entreprise)
    {
        $req = $this->connexionBdd->prepare("INSERT INTO entreprise (nom, site_web, adresse) VALUES (:nom, :site_web, :adresse)");
        $req->bindValue(':nom', $entreprise->getNomEntreprise());
        $req->bindValue(':site_web', $entreprise->getSiteWebEntreprise());
        $req->bindValue(':adresse', $entreprise->getAdresseEntreprise());
        $req->execute();
        return $this->connexionBdd->lastInsertId();
    }

    public function modifierEntreprise(Entreprise $entreprise)
    {
        $req = $this->connexionBdd->prepare("UPDATE entreprise SET nom = :nom, site_web = :site_web, adresse = :adresse WHERE id_entreprise = :id");
        $req->bindValue(':nom', $entreprise->getNomEntreprise());
        $req->bindValue(':site_web', $entreprise->getSiteWebEntreprise());
        $req->bindValue(':adresse', $entreprise->getAdresseEntreprise());
        $req->bindValue(':id', $entreprise->getIdEntreprise());
        $req->execute();
    }

    public function supprimerEntreprise(Entreprise $entreprise)
    {
        $req = $this->connexionBdd->prepare("DELETE FROM entreprise WHERE id_entreprise = :id");
        $req->bindValue(':id', $entreprise->getIdEntreprise());
        $req->execute();
    }
}