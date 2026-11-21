<?php

class AnnuaireRepository
{
    private $connexionBdd;

    public function __construct()
    {
        $this->connexionBdd = (new Bdd())->getConnexionBdd();
    }




    public function getMedecins($motCle)
    {
        $sql = "SELECT u.prenom, u.nom, s.nom AS specialite, h.nom AS hopital
                FROM medecin m
                INNER JOIN utilisateur u ON u.id_utilisateur = m.id_utilisateur
                INNER JOIN specialite s ON s.id_specialite = m.ref_specialite
                INNER JOIN hopital h ON h.id_hopital = m.ref_hopital
                WHERE u.statut_validation = 'valide'
                AND (u.nom LIKE :mot1 OR u.prenom LIKE :mot2 OR s.nom LIKE :mot3 OR h.nom LIKE :mot4)
                ORDER BY u.nom, u.prenom";
        $req = $this->connexionBdd->prepare($sql);
        $req->bindValue(':mot1', '%' . $motCle . '%');
        $req->bindValue(':mot2', '%' . $motCle . '%');
        $req->bindValue(':mot3', '%' . $motCle . '%');
        $req->bindValue(':mot4', '%' . $motCle . '%');
        $req->execute();
        return $req->fetchAll();
    }
    public function getEtudiants($motCle)
    {
        $sql = "SELECT u.prenom, u.nom, e.formation, e.cv, et.nom AS etablissement
                FROM etudiant e
                INNER JOIN utilisateur u ON u.id_utilisateur = e.id_utilisateur
                LEFT JOIN etablissement et ON et.id_etablissement = e.ref_etablissement
                WHERE u.statut_validation = 'valide'
                AND (u.nom LIKE :mot1 OR u.prenom LIKE :mot2 OR e.formation LIKE :mot3 OR et.nom LIKE :mot4)
                ORDER BY u.nom, u.prenom";
        $req = $this->connexionBdd->prepare($sql);
        $req->bindValue(':mot1', '%' . $motCle . '%');
        $req->bindValue(':mot2', '%' . $motCle . '%');
        $req->bindValue(':mot3', '%' . $motCle . '%');
        $req->bindValue(':mot4', '%' . $motCle . '%');
        $req->execute();
        return $req->fetchAll();
    }
}
