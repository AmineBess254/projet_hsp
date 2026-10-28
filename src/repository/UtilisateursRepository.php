<?php
class UtilisateursRepository
{
    private $connexionBdd;

    public function __construct()
    {
        $this->connexionBdd = (new Bdd())->getConnexionBdd();
    }

    // Transforme une ligne de la base en objet Utilisateurs
    private function creerObjet($result)
    {
        return new Utilisateurs($result["id_utilisateur"], $result["nom"], $result["prenom"], $result["email"],
            $result["mdp"], $result["statut_validation"], $result["ref_gestionnaire"], $result["role"]);
    }

    // Cherche un utilisateur par son email (sert à la connexion)
    public function getUtilisateurParEmail($email)
    {
        $req = $this->connexionBdd->prepare("SELECT * FROM utilisateur WHERE email = :email");
        $req->bindValue(':email', $email);
        $req->execute();
        $result = $req->fetch();
        if (!$result) {
            return null;
        }
        return $this->creerObjet($result);
    }

    // Liste des comptes à valider (sert au gestionnaire)
    public function getUtilisateursEnAttente()
    {
        $req = $this->connexionBdd->prepare("SELECT * FROM utilisateur WHERE statut_validation = 'en_attente'");
        $req->execute();
        $liste = array();
        foreach ($req->fetchAll() as $result) {
            $liste[] = $this->creerObjet($result);
        }
        return $liste;
    }

    // Vrai si l'email est déjà pris (sert à l'inscription)
    public function emailExiste($email)
    {
        $req = $this->connexionBdd->prepare("SELECT COUNT(*) FROM utilisateur WHERE email = :email");
        $req->bindValue(':email', $email);
        $req->execute();
        return $req->fetchColumn() > 0;
    }

    // Ajoute un utilisateur et renvoie son id
    public function ajouterUtilisateur(Utilisateurs $utilisateur)
    {
        $req = $this->connexionBdd->prepare("INSERT INTO utilisateur (nom, prenom, email, mdp, role)
                                             VALUES (:nom, :prenom, :email, :mdp, :role)");
        $req->bindValue(':nom', $utilisateur->getNom());
        $req->bindValue(':prenom', $utilisateur->getPrenom());
        $req->bindValue(':email', $utilisateur->getEmail());
        $req->bindValue(':mdp', $utilisateur->getMdp());
        $req->bindValue(':role', $utilisateur->getRole());
        $req->execute();
        return $this->connexionBdd->lastInsertId();
    }

    // Ajoute le profil selon le rôle (sert à l'inscription)
    public function ajouterEtudiant($idUtilisateur, $cv, $formation)
    {
        $req = $this->connexionBdd->prepare("INSERT INTO etudiant (id_utilisateur, cv, formation) VALUES (:id, :cv, :formation)");
        $req->bindValue(':id', $idUtilisateur);
        $req->bindValue(':cv', $cv);
        $req->bindValue(':formation', $formation);
        $req->execute();
    }

    // Le médecin garde le numéro de sa spécialité (ref_specialite), pas son nom
    public function ajouterMedecin($idUtilisateur, $refSpecialite, $refHopital)
    {
        $req = $this->connexionBdd->prepare("INSERT INTO medecin (id_utilisateur, ref_specialite, ref_hopital) VALUES (:id, :specialite, :hopital)");
        $req->bindValue(':id', $idUtilisateur);
        $req->bindValue(':specialite', $refSpecialite);
        $req->bindValue(':hopital', $refHopital);
        $req->execute();
    }

    public function ajouterPartenaire($idUtilisateur, $poste)
    {
        $req = $this->connexionBdd->prepare("INSERT INTO partenaire (id_utilisateur, poste) VALUES (:id, :poste)");
        $req->bindValue(':id', $idUtilisateur);
        $req->bindValue(':poste', $poste);
        $req->execute();
    }

    public function ajouterGestionnaire($idUtilisateur, $idCreateur)
    {
        $req = $this->connexionBdd->prepare("INSERT INTO gestionnaire (id_utilisateur, ref_gestionnaire_createur) VALUES (:id, :createur)");
        $req->bindValue(':id', $idUtilisateur);
        $req->bindValue(':createur', $idCreateur);
        $req->execute();
    }

    // Rattachements (sert après l'inscription)
    public function rattacherEntreprise($idUtilisateur, $idEntreprise)
    {
        $req = $this->connexionBdd->prepare("UPDATE partenaire SET ref_entreprise = :entreprise WHERE id_utilisateur = :id");
        $req->bindValue(':entreprise', $idEntreprise);
        $req->bindValue(':id', $idUtilisateur);
        $req->execute();
    }

    public function rattacherEtablissementEtudiant($idUtilisateur, $idEtablissement)
    {
        $req = $this->connexionBdd->prepare("UPDATE etudiant SET ref_etablissement = :etablissement WHERE id_utilisateur = :id");
        $req->bindValue(':etablissement', $idEtablissement);
        $req->bindValue(':id', $idUtilisateur);
        $req->execute();
    }

    public function rattacherEtablissementMedecin($idUtilisateur, $idEtablissement)
    {
        $req = $this->connexionBdd->prepare("UPDATE medecin SET ref_etablissement = :etablissement WHERE id_utilisateur = :id");
        $req->bindValue(':etablissement', $idEtablissement);
        $req->bindValue(':id', $idUtilisateur);
        $req->execute();
    }

    // Valider ou refuser un compte (sert au gestionnaire)
    public function changerStatutValidation($idUtilisateur, $statut, $idGestionnaire)
    {
        $req = $this->connexionBdd->prepare("UPDATE utilisateur SET statut_validation = :statut, ref_gestionnaire = :gestionnaire WHERE id_utilisateur = :id");
        $req->bindValue(':statut', $statut);
        $req->bindValue(':gestionnaire', $idGestionnaire);
        $req->bindValue(':id', $idUtilisateur);
        $req->execute();
    }
}