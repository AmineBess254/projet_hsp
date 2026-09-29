<?php
namespace repository;
class EntrepriseRepository
{
    private $connexionBdd;

    public function __construct()
    {
        $this->connexionBdd = (new Bdd())->getConnexionBdd();
    }

    public function getEntreprise($idEntreprise)
    {
        $sql = "SELECT * FROM entreprise
                WHERE id_entreprise = :id_entreprise";

        $req = $this->connexionBdd->prepare($sql);

        $req->bindValue(':id_entreprise', $idEntreprise);

        $req->execute();

        $result = $req->fetch();

        if (!$result) {
            return null;
        }

        $entreprise = new Entreprise(
            $result["id_entreprise"],
            $result["nom"],
            $result["site_web"],
            $result["adresse"]
        );

        return $entreprise;
    }

    public function getAllEntreprise()
    {
        $sql = "SELECT * FROM entreprise ORDER BY nom";

        $req = $this->connexionBdd->prepare($sql);

        $req->execute();

        $results = $req->fetchAll();

        $tabEntreprise = array();

        foreach ($results as $result) {

            $entreprise = new Entreprise(
                $result["id_entreprise"],
                $result["nom"],
                $result["site_web"],
                $result["adresse"]
            );

            $tabEntreprise[] = $entreprise;
        }

        return $tabEntreprise;
    }

    public function ajouterEntreprise(Entreprise $entreprise)
    {
        $sql = "INSERT INTO entreprise
                (nom, site_web, adresse)
                VALUES
                (:nom, :site_web, :adresse)";

        $req = $this->connexionBdd->prepare($sql);

        $req->bindValue(':nom', $entreprise->getNomEntreprise());
        $req->bindValue(':site_web', $entreprise->getSiteWebEntreprise());
        $req->bindValue(':adresse', $entreprise->getAdresseEntreprise());

        $req->execute();
    }

    public function modifierEntreprise(Entreprise $entreprise)
    {
        $sql = "UPDATE entreprise
                SET nom = :nom,
                    site_web = :site_web,
                    adresse = :adresse
                WHERE id_entreprise = :id_entreprise";

        $req = $this->connexionBdd->prepare($sql);

        $req->bindValue(':nom', $entreprise->getNomEntreprise());
        $req->bindValue(':site_web', $entreprise->getSiteWebEntreprise());
        $req->bindValue(':adresse', $entreprise->getAdresseEntreprise());
        $req->bindValue(':id_entreprise', $entreprise->getIdEntreprise());

        $req->execute();
    }

    public function supprimerEntreprise(Entreprise $entreprise)
    {
        $sql = "DELETE FROM entreprise
                WHERE id_entreprise = :id_entreprise";

        $req = $this->connexionBdd->prepare($sql);

        $req->bindValue(
            ':id_entreprise',
            $entreprise->getIdEntreprise()
        );

        $req->execute();
    }

    public function siteWebExiste($siteWeb)
    {
        $sql = "SELECT COUNT(*) FROM entreprise
                WHERE site_web = :site_web";

        $req = $this->connexionBdd->prepare($sql);

        $req->bindValue(':site_web', $siteWeb);

        $req->execute();

        return $req->fetchColumn() > 0;
    }

    public function getEntrepriseParSiteWeb($siteWeb)
    {
        $sql = "SELECT * FROM entreprise
                WHERE site_web = :site_web";

        $req = $this->connexionBdd->prepare($sql);

        $req->bindValue(':site_web', $siteWeb);

        $req->execute();

        $result = $req->fetch();

        if (!$result) {
            return null;
        }

        return new Entreprise(
            $result["id_entreprise"],
            $result["nom"],
            $result["site_web"],
            $result["adresse"]
        );
    }
}