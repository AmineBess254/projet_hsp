<?php

class EvenementRepository
{
    private $connexionBdd;

    public function __construct()
    {
        $this->connexionBdd = (new Bdd())->getConnexionBdd();
    }

    // Requête de base : chaque événement + son nombre d'inscrits acceptés (colonne nb_inscrits)
    private function requeteEvenements()
    {
        return "SELECT e.*,
                (SELECT COUNT(*) FROM inscription i
                 WHERE i.ref_evenement = e.id_evenement AND i.statut_inscription = 'accepte') AS nb_inscrits
                FROM evenement e";
    }

    // Transforme une ligne de la base en objet Evenement
    private function creerEvenement($ligne)
    {
        return new Evenement($ligne['id_evenement'], $ligne['type'], $ligne['titre'], $ligne['description'],
            $ligne['adresse'], $ligne['date_evenement'], $ligne['element_requis'], $ligne['nb_place'], $ligne['nb_inscrits']);
    }

    // ============================== ÉVÉNEMENTS ==============================

    // Tous les événements, avec deux filtres facultatifs : le type et une date minimum
    public function getAllEvenements($type = null, $dateMin = null)
    {
        $sql = $this->requeteEvenements() . " WHERE 1 = 1";
        if ($type != null) {
            $sql = $sql . " AND e.type = :type";
        }
        if ($dateMin != null) {
            $sql = $sql . " AND e.date_evenement >= :date_min";
        }
        $sql = $sql . " ORDER BY e.date_evenement";

        $req = $this->connexionBdd->prepare($sql);
        if ($type != null) {
            $req->bindValue(':type', $type);
        }
        if ($dateMin != null) {
            $req->bindValue(':date_min', $dateMin);
        }
        $req->execute();

        $tabEvenement = array();
        foreach ($req->fetchAll() as $ligne) {
            $tabEvenement[] = $this->creerEvenement($ligne);
        }
        return $tabEvenement;
    }

    // Les 3 prochains événements, pour la page d'accueil
    public function getProchainsEvenements()
    {
        $req = $this->connexionBdd->prepare($this->requeteEvenements() . " WHERE e.date_evenement >= CURDATE() ORDER BY e.date_evenement LIMIT 3");
        $req->execute();

        $tabEvenement = array();
        foreach ($req->fetchAll() as $ligne) {
            $tabEvenement[] = $this->creerEvenement($ligne);
        }
        return $tabEvenement;
    }

    // Un seul événement, ou null s'il n'existe pas
    public function getEvenement($idEvenement)
    {
        $req = $this->connexionBdd->prepare($this->requeteEvenements() . " WHERE e.id_evenement = :id");
        $req->bindValue(':id', $idEvenement);
        $req->execute();
        $ligne = $req->fetch();

        if ($ligne == false) {
            return null;
        }
        return $this->creerEvenement($ligne);
    }

    // Les types existants (sans doublons), pour la liste déroulante du filtre
    public function getTypesEvenements()
    {
        $req = $this->connexionBdd->prepare("SELECT DISTINCT type FROM evenement WHERE type IS NOT NULL ORDER BY type");
        $req->execute();

        $types = array();
        foreach ($req->fetchAll() as $ligne) {
            $types[] = $ligne['type'];
        }
        return $types;
    }

    // Ajoute l'événement et renvoie son id (créé automatiquement par MySQL)
    public function ajouterEvenement(Evenement $evenement)
    {
        $sql = "INSERT INTO evenement (type, titre, description, adresse, date_evenement, element_requis, nb_place)
                VALUES (:type, :titre, :description, :adresse, :date, :element_requis, :nb_place)";
        $req = $this->connexionBdd->prepare($sql);
        $req->bindValue(':type', $evenement->getTypeEvenement());
        $req->bindValue(':titre', $evenement->getTitreEvenement());
        $req->bindValue(':description', $evenement->getDescriptionEvenement());
        $req->bindValue(':adresse', $evenement->getAdresseEvenement());
        $req->bindValue(':date', $evenement->getDateEvenement());
        $req->bindValue(':element_requis', $evenement->getElementRequisEvenement());
        $req->bindValue(':nb_place', $evenement->getNbPlaceEvenement());
        $req->execute();

        return $this->connexionBdd->lastInsertId();
    }

    public function modifierEvenement(Evenement $evenement)
    {
        $sql = "UPDATE evenement SET type = :type, titre = :titre, description = :description, adresse = :adresse,
                date_evenement = :date, element_requis = :element_requis, nb_place = :nb_place
                WHERE id_evenement = :id_evenement";
        $req = $this->connexionBdd->prepare($sql);
        $req->bindValue(':type', $evenement->getTypeEvenement());
        $req->bindValue(':titre', $evenement->getTitreEvenement());
        $req->bindValue(':description', $evenement->getDescriptionEvenement());
        $req->bindValue(':adresse', $evenement->getAdresseEvenement());
        $req->bindValue(':date', $evenement->getDateEvenement());
        $req->bindValue(':element_requis', $evenement->getElementRequisEvenement());
        $req->bindValue(':nb_place', $evenement->getNbPlaceEvenement());
        $req->bindValue(':id_evenement', $evenement->getIdEvenement());
        $req->execute();
    }

    public function supprimerEvenement($idEvenement)
    {
        $req = $this->connexionBdd->prepare("DELETE FROM evenement WHERE id_evenement = :id_evenement");
        $req->bindValue(':id_evenement', $idEvenement);
        $req->execute();
    }

    // ============================== ORGANISATEURS ==============================

    public function ajouterOrganisateur($idEvenement, $idUtilisateur)
    {
        $req = $this->connexionBdd->prepare("INSERT INTO organisateur (ref_evenement, ref_utilisateur) VALUES (:ref_evenement, :ref_utilisateur)");
        $req->bindValue(':ref_evenement', $idEvenement);
        $req->bindValue(':ref_utilisateur', $idUtilisateur);
        $req->execute();
    }

    // Vrai si l'utilisateur organise cet événement
    public function estOrganisateur($idEvenement, $idUtilisateur)
    {
        $req = $this->connexionBdd->prepare("SELECT * FROM organisateur WHERE ref_evenement = :ref_evenement AND ref_utilisateur = :ref_utilisateur");
        $req->bindValue(':ref_evenement', $idEvenement);
        $req->bindValue(':ref_utilisateur', $idUtilisateur);
        $req->execute();

        // fetch() renvoie false s'il n'y a aucune ligne
        return $req->fetch() != false;
    }

    // Les organisateurs d'un événement (nom, prénom, rôle)
    public function getOrganisateurs($idEvenement)
    {
        $sql = "SELECT u.id_utilisateur, u.nom, u.prenom, u.role
                FROM organisateur o
                INNER JOIN utilisateur u ON u.id_utilisateur = o.ref_utilisateur
                WHERE o.ref_evenement = :ref_evenement";
        $req = $this->connexionBdd->prepare($sql);
        $req->bindValue(':ref_evenement', $idEvenement);
        $req->execute();
        return $req->fetchAll();
    }

    // Médecins et partenaires validés qui ne sont pas encore organisateurs (liste déroulante des co-organisateurs)
    public function getCoOrganisateursPossibles($idEvenement)
    {
        $sql = "SELECT id_utilisateur, nom, prenom, role
                FROM utilisateur
                WHERE role IN ('medecin', 'partenaire')
                  AND statut_validation = 'valide'
                  AND id_utilisateur NOT IN (SELECT ref_utilisateur FROM organisateur WHERE ref_evenement = :ref_evenement)";
        $req = $this->connexionBdd->prepare($sql);
        $req->bindValue(':ref_evenement', $idEvenement);
        $req->execute();
        return $req->fetchAll();
    }

    // ============================== INSCRIPTIONS ==============================

    // Statut de l'inscription : null (pas inscrit), 'accepte' ou 'refuse'
    public function getStatutInscription($idEvenement, $idUtilisateur)
    {
        $req = $this->connexionBdd->prepare("SELECT statut_inscription FROM inscription WHERE ref_evenement = :ref_evenement AND ref_utilisateur = :ref_utilisateur");
        $req->bindValue(':ref_evenement', $idEvenement);
        $req->bindValue(':ref_utilisateur', $idUtilisateur);
        $req->execute();
        $ligne = $req->fetch();

        if ($ligne == false) {
            return null;
        }
        return $ligne['statut_inscription'];
    }

    // Inscrit un utilisateur s'il reste de la place.
    // Renvoie 'ok', 'complet', 'deja_inscrit' ou 'refuse'.
    public function inscrire($idEvenement, $idUtilisateur)
    {
        // 1. Déjà inscrit, ou refusé par les organisateurs ?
        $statut = $this->getStatutInscription($idEvenement, $idUtilisateur);
        if ($statut == 'accepte') {
            return 'deja_inscrit';
        }
        if ($statut == 'refuse') {
            return 'refuse';
        }

        // 2. Reste-t-il des places ?
        $evenement = $this->getEvenement($idEvenement);
        if ($evenement->getPlacesRestantes() == 0) {
            return 'complet';
        }

        // 3. On l'inscrit
        $req = $this->connexionBdd->prepare("INSERT INTO inscription (statut_inscription, ref_evenement, ref_utilisateur) VALUES ('accepte', :ref_evenement, :ref_utilisateur)");
        $req->bindValue(':ref_evenement', $idEvenement);
        $req->bindValue(':ref_utilisateur', $idUtilisateur);
        $req->execute();
        return 'ok';
    }

    // Désinscription (seulement une inscription acceptée : un refus ne peut pas être effacé)
    public function desinscrire($idEvenement, $idUtilisateur)
    {
        $req = $this->connexionBdd->prepare("DELETE FROM inscription WHERE ref_evenement = :ref_evenement AND ref_utilisateur = :ref_utilisateur AND statut_inscription = 'accepte'");
        $req->bindValue(':ref_evenement', $idEvenement);
        $req->bindValue(':ref_utilisateur', $idUtilisateur);
        $req->execute();
    }

    // Les inscrits d'un événement, avec leurs infos
    public function getInscrits($idEvenement)
    {
        $sql = "SELECT i.id_inscription, i.statut_inscription, i.date_inscription, u.nom, u.prenom, u.email, u.role
                FROM inscription i
                INNER JOIN utilisateur u ON u.id_utilisateur = i.ref_utilisateur
                WHERE i.ref_evenement = :ref_evenement";
        $req = $this->connexionBdd->prepare($sql);
        $req->bindValue(':ref_evenement', $idEvenement);
        $req->execute();
        return $req->fetchAll();
    }

    // Refuse une inscription (le ref_evenement vérifie qu'elle appartient bien à cet événement)
    public function refuserInscription($idInscription, $idEvenement)
    {
        $req = $this->connexionBdd->prepare("UPDATE inscription SET statut_inscription = 'refuse' WHERE id_inscription = :id AND ref_evenement = :ref_evenement");
        $req->bindValue(':id', $idInscription);
        $req->bindValue(':ref_evenement', $idEvenement);
        $req->execute();
    }
}
