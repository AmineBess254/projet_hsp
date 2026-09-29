<?php
namespace repository;
class CanalRepository
{
    private $connexionBdd;

    public function __construct()
    {
        $this->connexionBdd = (new Bdd())->getConnexionBdd();
    }

    public function getCanal($idCanal)
    {
        $sql = "SELECT * FROM canal WHERE id_canal = :id_canal";

        $req = $this->connexionBdd->prepare($sql);
        $req->bindValue(':id_canal', $idCanal);
        $req->execute();

        $result = $req->fetch();

        if (!$result) {
            return null;
        }

        $canal = new Canal(
            $result["id_canal"],
            $result["nom_canal"],
            $result["date_heure"]
        );

        return $canal;
    }

    public function getAllCanal()
    {
        $sql = "SELECT * FROM canal";

        $req = $this->connexionBdd->prepare($sql);
        $req->execute();

        $results = $req->fetchAll();

        $tabCanal = array();

        foreach ($results as $result) {
            $canal = new Canal(
                $result["id_canal"],
                $result["nom_canal"],
                $result["date_heure"]
            );

            $tabCanal[] = $canal;
        }

        return $tabCanal;
    }

    public function ajouterCanal(Canal $canal)
    {
        $sql = "INSERT INTO canal (nom_canal)
                VALUES (:nom_canal)";

        $req = $this->connexionBdd->prepare($sql);

        $req->bindValue(':nom_canal', $canal->getNomCanal());

        $req->execute();
    }

    public function modifierCanal(Canal $canal)
    {
        $sql = "UPDATE canal
                SET nom_canal = :nom_canal
                WHERE id_canal = :id_canal";

        $req = $this->connexionBdd->prepare($sql);

        $req->bindValue(':nom_canal', $canal->getNomCanal());
        $req->bindValue(':id_canal', $canal->getIdCanal());

        $req->execute();
    }

    public function supprimerCanal(Canal $canal)
    {
        $sql = "DELETE FROM canal
                WHERE id_canal = :id_canal";

        $req = $this->connexionBdd->prepare($sql);

        $req->bindValue(':id_canal', $canal->getIdCanal());

        $req->execute();
    }
}