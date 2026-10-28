<?php

class EvenementRepository
{
    private $connexionBdd;

    public function __construct()
    {
        $this->connexionBdd = (new Bdd())->getConnexionBdd();
    }

    // Début de requête commun : l'événement + son nombre d'inscrits acceptés
    private $selectEvenement = "SELECT e.*,
            (SELECT COUNT(*) FROM inscription i
             WHERE i.ref_evenement = e.id_evenement AND i.statut_inscription = 'accepte') AS nb_inscrits
            FROM evenement e";

    // Transforme une ligne de la base en objet Evenement
    private function creerObjet($result)
    {
        return new Evenement($result["id_evenement"], $result["type"], $result["titre"], $result["description"],
            $result["adresse"], $result["date_evenement"], $result["element_requis"], $result["nb_place"], $result["nb_inscrits"]);
    }

    // ---------------------------------------------------------------- Événements

    public function getAllEvenements()
    {
        $req = $this->connexionBdd->prepare($this->selectEvenement . " ORDER BY e.date_evenement");
        $req->execute();
        $tabEvenement = array();
        foreach ($req->fetchAll() as $result) {
            $tabEvenement[] = $this->creerObjet($result);
        }
        return $tabEvenement;
    }

    // Les prochains événements (aujourd'hui ou plus tard), pour la page d'accueil
    public function getProchainsEvenements($limite)
    {
        $req = $this->connexionBdd->prepare($this->selectEvenement . " WHERE e.date_evenement >= CURDATE() ORDER BY e.date_evenement LIMIT :limite");
        $req->bindValue(':limite', (int) $limite, PDO::PARAM_INT);
        $req->execute();
        $tabEvenement = array();
        foreach ($req->fetchAll() as $result) {
            $tabEvenement[] = $this->creerObjet($result);
        }
        return $tabEvenement;
    }

    // Un seul événement, ou null s'il n'existe pas
    public function getEvenement($idEvenement)
    {
        $req = $this->connexionBdd->prepare($this->selectEvenement . " WHERE e.id_evenement = :id");
        $req->bindValue(':id', $idEvenement, PDO::PARAM_INT);
        $req->execute();
        $result = $req->fetch();
        if ($result == false) {
            return null;
        }
        return $this->creerObjet($result);
    }

    // Ajoute l'événement et renvoie son id (créé par AUTO_INCREMENT)
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
        $req->bindValue(':nb_place', $evenement->getNbPlaceEvenement(), PDO::PARAM_INT);
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
        $req->bindValue(':nb_place', $evenement->getNbPlaceEvenement(), PDO::PARAM_INT);
        $req->bindValue(':id_evenement', $evenement->getIdEvenement(), PDO::PARAM_INT);
        $req->execute();
    }

    public function supprimerEvenement($idEvenement)
    {
        $req = $this->connexionBdd->prepare("DELETE FROM evenement WHERE id_evenement = :id_evenement");
        $req->bindValue(':id_evenement', $idEvenement, PDO::PARAM_INT);
        $req->execute();
    }

    // ---------------------------------------------------------------- Organisateurs

    public function ajouterOrganisateur($idEvenement, $idUtilisateur)
    {
        // INSERT IGNORE : si la personne est déjà organisatrice, on ne fait rien (pas d'erreur)
        $req = $this->connexionBdd->prepare("INSERT IGNORE INTO organisateur (ref_evenement, ref_utilisateur) VALUES (:ref_evenement, :ref_utilisateur)");
        $req->bindValue(':ref_evenement', $idEvenement, PDO::PARAM_INT);
        $req->bindValue(':ref_utilisateur', $idUtilisateur, PDO::PARAM_INT);
        $req->execute();
    }

    // Vrai si l'utilisateur fait partie des organisateurs de l'événement
    public function estOrganisateur($idEvenement, $idUtilisateur)
    {
        $req = $this->connexionBdd->prepare("SELECT COUNT(*) FROM organisateur WHERE ref_evenement = :ref_evenement AND ref_utilisateur = :ref_utilisateur");
        $req->bindValue(':ref_evenement', $idEvenement, PDO::PARAM_INT);
        $req->bindValue(':ref_utilisateur', $idUtilisateur, PDO::PARAM_INT);
        $req->execute();
        return $req->fetchColumn() > 0;
    }

    // Liste des organisateurs (nom, prénom, rôle)
    public function getOrganisateurs($idEvenement)
    {
        $sql = "SELECT u.id_utilisateur, u.nom, u.prenom, u.role
                FROM organisateur o
                INNER JOIN utilisateur u ON u.id_utilisateur = o.ref_utilisateur
                WHERE o.ref_evenement = :ref_evenement
                ORDER BY u.nom, u.prenom";
        $req = $this->connexionBdd->prepare($sql);
        $req->bindValue(':ref_evenement', $idEvenement, PDO::PARAM_INT);
        $req->execute();
        return $req->fetchAll();
    }

    // Médecins et partenaires validés qui ne sont pas encore organisateurs (pour ajouter un co-organisateur)
    public function getCoOrganisateursPossibles($idEvenement)
    {
        $sql = "SELECT u.id_utilisateur, u.nom, u.prenom, u.role
                FROM utilisateur u
                WHERE u.role IN ('medecin', 'partenaire')
                  AND u.statut_validation = 'valide'
                  AND u.id_utilisateur NOT IN (SELECT ref_utilisateur FROM organisateur WHERE ref_evenement = :ref_evenement)
                ORDER BY u.nom, u.prenom";
        $req = $this->connexionBdd->prepare($sql);
        $req->bindValue(':ref_evenement', $idEvenement, PDO::PARAM_INT);
        $req->execute();
        return $req->fetchAll();
    }

    // ---------------------------------------------------------------- Inscriptions

    // Statut de l'inscription d'un utilisateur : null (pas inscrit), 'accepte' ou 'refuse'
    public function getStatutInscription($idEvenement, $idUtilisateur)
    {
        $req = $this->connexionBdd->prepare("SELECT statut_inscription FROM inscription WHERE ref_evenement = :ref_evenement AND ref_utilisateur = :ref_utilisateur");
        $req->bindValue(':ref_evenement', $idEvenement, PDO::PARAM_INT);
        $req->bindValue(':ref_utilisateur', $idUtilisateur, PDO::PARAM_INT);
        $req->execute();
        $statut = $req->fetchColumn();
        return $statut === false ? null : $statut;
    }

    // Inscrit un utilisateur en respectant le nombre de places.
    // Renvoie 'ok', 'complet', 'deja_inscrit' ou 'refuse'.
    public function inscrire($idEvenement, $idUtilisateur)
    {
        // Transaction + FOR UPDATE : si deux personnes s'inscrivent en même temps sur la dernière place,
        // la deuxième attend que la première ait fini, et ne peut donc pas dépasser le nombre de places.
        $this->connexionBdd->beginTransaction();
        try {
            $req = $this->connexionBdd->prepare("SELECT nb_place FROM evenement WHERE id_evenement = :id FOR UPDATE");
            $req->bindValue(':id', $idEvenement, PDO::PARAM_INT);
            $req->execute();
            $nbPlace = (int) $req->fetchColumn();

            $statut = $this->getStatutInscription($idEvenement, $idUtilisateur);
            if ($statut == 'accepte') {
                $this->connexionBdd->rollBack();
                return 'deja_inscrit';
            }
            if ($statut == 'refuse') {
                $this->connexionBdd->rollBack();
                return 'refuse';
            }

            $req = $this->connexionBdd->prepare("SELECT COUNT(*) FROM inscription WHERE ref_evenement = :id AND statut_inscription = 'accepte'");
            $req->bindValue(':id', $idEvenement, PDO::PARAM_INT);
            $req->execute();
            if ($req->fetchColumn() >= $nbPlace) {
                $this->connexionBdd->rollBack();
                return 'complet';
            }

            $req = $this->connexionBdd->prepare("INSERT INTO inscription (statut_inscription, ref_evenement, ref_utilisateur) VALUES ('accepte', :ref_evenement, :ref_utilisateur)");
            $req->bindValue(':ref_evenement', $idEvenement, PDO::PARAM_INT);
            $req->bindValue(':ref_utilisateur', $idUtilisateur, PDO::PARAM_INT);
            $req->execute();

            $this->connexionBdd->commit();
            return 'ok';
        } catch (Exception $e) {
            $this->connexionBdd->rollBack();
            throw $e;
        }
    }

    // Désinscription : seulement si l'inscription est acceptée
    // (un inscrit refusé ne doit pas pouvoir effacer son refus pour se réinscrire)
    public function desinscrire($idEvenement, $idUtilisateur)
    {
        $req = $this->connexionBdd->prepare("DELETE FROM inscription WHERE ref_evenement = :ref_evenement AND ref_utilisateur = :ref_utilisateur AND statut_inscription = 'accepte'");
        $req->bindValue(':ref_evenement', $idEvenement, PDO::PARAM_INT);
        $req->bindValue(':ref_utilisateur', $idUtilisateur, PDO::PARAM_INT);
        $req->execute();
    }

    // Liste des inscrits d'un événement, avec leurs infos
    public function getInscrits($idEvenement)
    {
        $sql = "SELECT i.id_inscription, i.statut_inscription, i.date_inscription, u.nom, u.prenom, u.email, u.role
                FROM inscription i
                INNER JOIN utilisateur u ON u.id_utilisateur = i.ref_utilisateur
                WHERE i.ref_evenement = :ref_evenement
                ORDER BY i.date_inscription";
        $req = $this->connexionBdd->prepare($sql);
        $req->bindValue(':ref_evenement', $idEvenement, PDO::PARAM_INT);
        $req->execute();
        return $req->fetchAll();
    }

    // Refuse une inscription. Le WHERE sur ref_evenement empêche de refuser
    // une inscription qui appartient à un autre événement.
    public function refuserInscription($idInscription, $idEvenement)
    {
        $req = $this->connexionBdd->prepare("UPDATE inscription SET statut_inscription = 'refuse' WHERE id_inscription = :id AND ref_evenement = :ref_evenement");
        $req->bindValue(':id', $idInscription, PDO::PARAM_INT);
        $req->bindValue(':ref_evenement', $idEvenement, PDO::PARAM_INT);
        $req->execute();
    }
}
