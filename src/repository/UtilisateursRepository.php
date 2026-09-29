<?php
namespace repository;
class UtilisateursRepository
{
    private $connexionBdd;

    public function __construct()
    {
        $this->connexionBdd = (new Bdd())->getConnexionBdd();
    }

    public function getUtilisateur($idUtilisateurs)
    {
        $sql = "SELECT * FROM utilisateur WHERE id_utilisateur = :id_utilisateur";
        $req = $this->connexionBdd->prepare($sql);
        $req->bindValue(':id_utilisateur', $idUtilisateurs);
        $req->execute();
        $result = $req->fetch();
        if (!$result) {
            return null;
        }
        $utilisateur = new Utilisateurs($result["id_utilisateur"], $result["nom"], $result["prenom"], $result["email"], $result["mdp"],
            $result["statut_validation"], $result["ref_gestionnaire"]);
        return $utilisateur;
    }

    public function getUtilisateurParEmail($email)
    {
        $sql = "SELECT * FROM utilisateur WHERE email = :email";
        $req = $this->connexionBdd->prepare($sql);
        $req->bindValue(':email', $email);
        $req->execute();
        $result = $req->fetch();
        if (!$result) {
            return null;
        }
        $utilisateur = new Utilisateurs($result["id_utilisateur"], $result["nom"], $result["prenom"], $result["email"], $result["mdp"],
            $result["statut_validation"], $result["ref_gestionnaire"]);
        return $utilisateur;
    }

    public function getAllUtilisateur()
    {
        $sql = "SELECT * FROM utilisateur ORDER BY nom, prenom";
        $req = $this->connexionBdd->prepare($sql);
        $req->execute();
        $results = $req->fetchAll();
        $tabUtilisateur = array();
        foreach ($results as $result) {
            $utilisateur = new Utilisateurs($result["id_utilisateur"], $result["nom"], $result["prenom"], $result["email"], $result["mdp"],
                $result["statut_validation"], $result["ref_gestionnaire"]);
            $tabUtilisateur[] = $utilisateur;
        }
        return $tabUtilisateur;
    }

    public function getUtilisateurEnAttente()
    {
        $sql = "SELECT * FROM utilisateur WHERE statut_validation = 'en_attente' ORDER BY id_utilisateur";
        $req = $this->connexionBdd->prepare($sql);
        $req->execute();
        $results = $req->fetchAll();
        $tabUtilisateur = array();
        foreach ($results as $result) {
            $utilisateur = new Utilisateurs($result["id_utilisateur"], $result["nom"], $result["prenom"], $result["email"], $result["mdp"],
                $result["statut_validation"], $result["ref_gestionnaire"]);
            $tabUtilisateur[] = $utilisateur;
        }
        return $tabUtilisateur;
    }

    public function emailExiste($email)
    {
        $sql = "SELECT * FROM utilisateur WHERE email = :email";
        $req = $this->connexionBdd->prepare($sql);
        $req->bindValue(':email', $email);
        $req->execute();
        return (bool) $req->fetchColumn();
    }

    public function ajouterUtilisateur(Utilisateurs $utilisateur)
    {
        $sql = "INSERT INTO utilisateur 
            (nom, prenom, email, mdp)
            VALUES 
            (:nom, :prenom, :email, :mdp)";

        $req = $this->connexionBdd->prepare($sql);

        $req->bindValue(':nom', $utilisateur->getNom());
        $req->bindValue(':prenom', $utilisateur->getPrenom());
        $req->bindValue(':email', $utilisateur->getEmail());
        $req->bindValue(':mdp', $utilisateur->getMdp());   // déjà hashé avec password_hash()

        $req->execute();
        return $this->connexionBdd->lastInsertId();
    }

    public function modifierUtilisateur(Utilisateurs $utilisateur)
    {
        $sql = "UPDATE utilisateur SET nom = :nom, prenom = :prenom, email = :email WHERE id_utilisateur = :id_utilisateur";
        $req = $this->connexionBdd->prepare($sql);
        $req->bindValue(':id_utilisateur', $utilisateur->getIdUtilisateurs());
        $req->bindValue(':nom', $utilisateur->getNom());
        $req->bindValue(':prenom', $utilisateur->getPrenom());
        $req->bindValue(':email', $utilisateur->getEmail());
        $req->execute();
    }

    public function modifierMotDePasse($idUtilisateurs, $mdpHash)
    {
        $sql = "UPDATE utilisateur SET mdp = :mdp WHERE id_utilisateur = :id_utilisateur";
        $req = $this->connexionBdd->prepare($sql);
        $req->bindValue(':mdp', $mdpHash);
        $req->bindValue(':id_utilisateur', $idUtilisateurs);
        $req->execute();
    }

    public function supprimerUtilisateur(Utilisateurs $utilisateur)
    {
        $sql = "DELETE FROM utilisateur WHERE id_utilisateur = :id_utilisateur";
        $req = $this->connexionBdd->prepare($sql);
        $req->bindValue(':id_utilisateur', $utilisateur->getIdUtilisateurs());
        $req->execute();
    }

    public function changerStatutValidation($idUtilisateurs, $statut, $refGestionnaire)
    {
        $sql = "UPDATE utilisateur SET statut_validation = :statut, ref_gestionnaire = :ref_gestionnaire WHERE id_utilisateur = :id_utilisateur";
        $req = $this->connexionBdd->prepare($sql);
        $req->bindValue(':statut', $statut);
        $req->bindValue(':ref_gestionnaire', $refGestionnaire);
        $req->bindValue(':id_utilisateur', $idUtilisateurs);
        $req->execute();
    }

}