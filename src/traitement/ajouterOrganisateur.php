<?php
require_once '../auth.php';
require_once '../bdd/Bdd.php';
require_once '../modele/Evenement.php';
require_once '../repository/EvenementRepository.php';

$idEvenement = (int) ($_POST['id_evenement'] ?? 0);
$idUtilisateur = (int) ($_POST['id_utilisateur'] ?? 0);
$evenementRepository = new EvenementRepository();

// Sécurité : seul un organisateur peut ajouter un co-organisateur
if (!estConnecte() || !$evenementRepository->estOrganisateur($idEvenement, $_SESSION['id_utilisateur'])) {
    header('Location: ../../public/listeEvenements.php');
    exit;
}

// On vérifie que la personne choisie est bien un médecin ou partenaire validé, pas encore organisateur
$possible = false;
foreach ($evenementRepository->getCoOrganisateursPossibles($idEvenement) as $personne) {
    if ($personne['id_utilisateur'] == $idUtilisateur) {
        $possible = true;
    }
}
if (!$possible) {
    header('Location: ../../public/evenement.php?id=' . $idEvenement . '&erreur=organisateur');
    exit;
}

// Un utilisateur inscrit qui devient organisateur n'a plus besoin de sa place
$evenementRepository->desinscrire($idEvenement, $idUtilisateur);
$evenementRepository->ajouterOrganisateur($idEvenement, $idUtilisateur);

header('Location: ../../public/evenement.php?id=' . $idEvenement . '&ok=organisateur');
exit;
