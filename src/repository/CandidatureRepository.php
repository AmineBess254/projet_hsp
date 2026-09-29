<?php
namespace repository;
class CandidatureRepository
{
    private $connexionBdd;

    public function __construct()
    {
        $this->connexionBdd = (new Bdd())->getConnexionBdd();
    }

    public function getCandidature($idCandidature)
    {
        $sql = "SELECT * FROM candidature
                WHERE id_candidature = :id_candidature";

        $req = $this->connexionBdd->prepare($sql);

        $req->bindValue(':id_candidature', $idCandidature);

        $req->execute();

        $result = $req->fetch();

        if (!$result) {
            return null;
        }

        $candidature = new Candidature(
            $result["id_candidature"],
            $result["motivation"],
            $result["date_candidature"],
            $result["statut_candidature"],
            $result["ref_utilisateur"],
            $result["ref_offre"]
        );

        return $candidature;
    }

    public function getAllCandidature()
    {
        $sql = "SELECT * FROM candidature";

        $req = $this->connexionBdd->prepare($sql);
        $req->execute();

        $results = $req->fetchAll();

        $tabCandidature = array();

        foreach ($results as $result) {

            $candidature = new Candidature(
                $result["id_candidature"],
                $result["motivation"],
                $result["date_candidature"],
                $result["statut_candidature"],
                $result["ref_utilisateur"],
                $result["ref_offre"]
            );

            $tabCandidature[] = $candidature;
        }

        return $tabCandidature;
    }

    public function ajouterCandidature(Candidature $candidature)
    {
        $sql = "INSERT INTO candidature
                (motivation, statut_candidature, ref_utilisateur, ref_offre)
                VALUES
                (:motivation, :statut_candidature, :ref_utilisateur, :ref_offre)";

        $req = $this->connexionBdd->prepare($sql);

        $req->bindValue(':motivation', $candidature->getMotivation());
        $req->bindValue(':statut_candidature', $candidature->getStatutCandidature());
        $req->bindValue(':ref_utilisateur', $candidature->getRefUtilisateur());
        $req->bindValue(':ref_offre', $candidature->getRefOffre());

        $req->execute();
    }

    public function modifierCandidature(Candidature $candidature)
    {
        $sql = "UPDATE candidature
                SET motivation = :motivation,
                    statut_candidature = :statut_candidature,
                    ref_utilisateur = :ref_utilisateur,
                    ref_offre = :ref_offre
                WHERE id_candidature = :id_candidature";

        $req = $this->connexionBdd->prepare($sql);

        $req->bindValue(':motivation', $candidature->getMotivation());
        $req->bindValue(':statut_candidature', $candidature->getStatutCandidature());
        $req->bindValue(':ref_utilisateur', $candidature->getRefUtilisateur());
        $req->bindValue(':ref_offre', $candidature->getRefOffre());
        $req->bindValue(':id_candidature', $candidature->getIdCandidature());

        $req->execute();
    }

    public function supprimerCandidature(Candidature $candidature)
    {
        $sql = "DELETE FROM candidature
                WHERE id_candidature = :id_candidature";

        $req = $this->connexionBdd->prepare($sql);

        $req->bindValue(
            ':id_candidature',
            $candidature->getIdCandidature()
        );

        $req->execute();
    }

    public function getCandidaturesParOffre($idOffre)
    {
        $sql = "SELECT * FROM candidature
                WHERE ref_offre = :ref_offre
                AND statut_candidature != 'masquee'
                ORDER BY date_candidature DESC";

        $req = $this->connexionBdd->prepare($sql);

        $req->bindValue(':ref_offre', $idOffre);

        $req->execute();

        $results = $req->fetchAll();

        $tabCandidature = array();

        foreach ($results as $result) {

            $candidature = new Candidature(
                $result["id_candidature"],
                $result["motivation"],
                $result["date_candidature"],
                $result["statut_candidature"],
                $result["ref_utilisateur"],
                $result["ref_offre"]
            );

            $tabCandidature[] = $candidature;
        }

        return $tabCandidature;
    }

    public function candidatureExiste($idUtilisateur, $idOffre)
    {
        $sql = "SELECT COUNT(*) FROM candidature
                WHERE ref_utilisateur = :ref_utilisateur
                AND ref_offre = :ref_offre";

        $req = $this->connexionBdd->prepare($sql);

        $req->bindValue(':ref_utilisateur', $idUtilisateur);
        $req->bindValue(':ref_offre', $idOffre);

        $req->execute();

        return $req->fetchColumn() > 0;
    }

    public function masquerCandidature($idCandidature)
    {
        $sql = "UPDATE candidature
                SET statut_candidature = 'masquee'
                WHERE id_candidature = :id_candidature";

        $req = $this->connexionBdd->prepare($sql);

        $req->bindValue(':id_candidature', $idCandidature);

        $req->execute();
    }
}