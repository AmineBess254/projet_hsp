<?php
require_once '../auth.php';
require_once '../bdd/Bdd.php';
require_once '../modele/Evenement.php';
require_once '../repository/EvenementRepository.php';

$idEvenement = (int) ($_POST['id_evenement'] ?? 0);
$idInscription = (int) ($_POST['id_inscription'] ?? 0);
$evenementRepository = new EvenementRepository();

// Sécurité : seul un organisateur de l'événement peut refuser un inscrit
if (!estConnecte() || !$evenementRepository->estOrganisateur($idEvenement, $_SESSION['id_utilisateur'])) {
    header('Location: ../../public/listeEvenements.php');
    exit;
}

$evenementRepository->refuserInscription($idInscription, $idEvenement);

header('Location: ../../public/inscritsEvenement.php?id=' . $idEvenement . '&ok=refuse');
exit;
