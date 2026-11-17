<?php

class ActualiteRepository
{
    private $connexionBdd;

    public function __construct()
    {
        $this->connexionBdd = (new Bdd())->getConnexionBdd();
    }

    // Transforme une ligne de la base en objet Actualite
    private function creerObjet($result)
    {
        return new Actualite($result['id_actualite'], $result['titre'], $result['contenu'], $result['date_publication'], $result['ref_utilisateur']);
    }

    // Toutes les actualités, de la plus récente à la plus ancienne
    public function getAllActualites()
    {
        $req = $this->connexionBdd->prepare("SELECT * FROM actualite ORDER BY date_publication DESC, id_actualite DESC");
        $req->execute();
        $tabActualite = array();
        foreach ($req->fetchAll() as $result) {
            $tabActualite[] = $this->creerObjet($result);
        }
        return $tabActualite;
    }

    // Les dernières actualités, pour la page d'accueil
    public function getDernieresActualites($limite)
    {
        $req = $this->connexionBdd->prepare("SELECT * FROM actualite ORDER BY date_publication DESC, id_actualite DESC LIMIT :limite");
        $req->bindValue(':limite', (int) $limite, PDO::PARAM_INT);
        $req->execute();
        $tabActualite = array();
        foreach ($req->fetchAll() as $result) {
            $tabActualite[] = $this->creerObjet($result);
        }
        return $tabActualite;
    }

    public function ajouterActualite(Actualite $actualite)
    {
        $req = $this->connexionBdd->prepare("INSERT INTO actualite (titre, contenu, ref_utilisateur) VALUES (:titre, :contenu, :ref_utilisateur)");
        $req->bindValue(':titre', $actualite->getTitre());
        $req->bindValue(':contenu', $actualite->getContenu());
        $req->bindValue(':ref_utilisateur', $actualite->getRefUtilisateur(), PDO::PARAM_INT);
        $req->execute();
    }

    public function supprimerActualite($idActualite)
    {
        $req = $this->connexionBdd->prepare("DELETE FROM actualite WHERE id_actualite = :id_actualite");
        $req->bindValue(':id_actualite', $idActualite, PDO::PARAM_INT);
        $req->execute();
    }
}
