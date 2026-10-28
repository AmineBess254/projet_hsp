<?php
require_once '../bdd/Bdd.php';
require_once '../modele/Evenement.php';
require_once '../repository/EvenementRepository.php';
session_start();

session_start();

if (!isset($_SESSION['id_utilisateur'])) {
    header('Location: ../../public/connexion.php');
    exit;
}
$evenementRepository = new EvenementRepository();
if (!$evenementRepository->peutCreerEvenement($_SESSION['id_utilisateur'])) {
    header('Location: ../../public/index.php');
    exit;
}
if (isset($_POST['type'], $_POST['titre'], $_POST['description'], $_POST['adresse'], $_POST['date_evenement'], $_POST['nb_place'])) {
    $evenement = new Evenement(
        null,
        $_POST['type'],
        $_POST['titre'],
        $_POST['description'],
        $_POST['adresse'],
        $_POST['date_evenement'],
        $_POST['element_requis'] ?? null,
        (int) $_POST['nb_place']
    );
    $evenementRepository = new EvenementRepository();

    $idEvenement = $evenementRepository->ajouterEvenement($evenement);

    $evenementRepository->ajouterOrganisateur($idEvenement, $_SESSION['id_utilisateur']);

    header('Location: ../../public/ajoutEvenement.php?ok=1');
    exit;
}
header('Location: ../../public/ajoutEvenement.php?erreur=1');
exit;