<?php
require_once '../auth.php';
require_once '../bdd/Bdd.php';
require_once '../modele/Evenement.php';
require_once '../repository/EvenementRepository.php';

$idEvenement = (int) ($_POST['id_evenement'] ?? 0);
$action = $_POST['action'] ?? '';
$fiche = '../../public/evenement.php?id=' . $idEvenement;

// Pas connecté -> connexion
if (!estConnecte()) {
    header('Location: ../../public/connexion.php');
    exit;
}

$evenementRepository = new EvenementRepository();
$evenement = $evenementRepository->getEvenement($idEvenement);
if ($evenement == null) {
    header('Location: ../../public/listeEvenements.php');
    exit;
}

// Les gestionnaires ne s'inscrivent pas, les organisateurs non plus (à leur propre événement)
if ($_SESSION['role'] == 'gestionnaire' || $evenementRepository->estOrganisateur($idEvenement, $_SESSION['id_utilisateur'])) {
    header('Location: ' . $fiche);
    exit;
}

// Pas d'inscription ni de désinscription une fois l'événement passé
if ($evenement->estPasse()) {
    header('Location: ' . $fiche . '&erreur=passe');
    exit;
}

if ($action == 'inscrire') {
    $resultat = $evenementRepository->inscrire($idEvenement, $_SESSION['id_utilisateur']);
    if ($resultat == 'ok') {
        header('Location: ' . $fiche . '&ok=inscrit');
    } else {
        header('Location: ' . $fiche . '&erreur=' . $resultat);
    }
    exit;
}

if ($action == 'desinscrire') {
    $evenementRepository->desinscrire($idEvenement, $_SESSION['id_utilisateur']);
    header('Location: ' . $fiche . '&ok=desinscrit');
    exit;
}

header('Location: ' . $fiche);
exit;
