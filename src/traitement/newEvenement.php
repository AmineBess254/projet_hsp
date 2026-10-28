<?php
require_once '../auth.php';
require_once '../bdd/Bdd.php';
require_once '../modele/Evenement.php';
require_once '../repository/EvenementRepository.php';

// Sécurité : seuls les médecins et partenaires connectés peuvent créer un événement
if (!estConnecte() || !in_array($_SESSION['role'], ['medecin', 'partenaire'])) {
    header('Location: ../../public/index.php');
    exit;
}

// Champs obligatoires : présents et pas vides
$titre = trim($_POST['titre'] ?? '');
$type = trim($_POST['type'] ?? '');
$description = trim($_POST['description'] ?? '');
$adresse = trim($_POST['adresse'] ?? '');
$date = $_POST['date_evenement'] ?? '';
$nbPlace = (int) ($_POST['nb_place'] ?? 0);
if ($titre == '' || $type == '' || $description == '' || $adresse == '' || $date == '') {
    header('Location: ../../public/ajoutEvenement.php?erreur=champs');
    exit;
}
if ($nbPlace < 1) {
    header('Location: ../../public/ajoutEvenement.php?erreur=places');
    exit;
}
if ($date < date('Y-m-d')) {
    header('Location: ../../public/ajoutEvenement.php?erreur=date');
    exit;
}

// Champ facultatif : vide -> null
$elementRequis = trim($_POST['element_requis'] ?? '');
if ($elementRequis == '') {
    $elementRequis = null;
}

$evenement = new Evenement(null, $type, $titre, $description, $adresse, $date, $elementRequis, $nbPlace);
$evenementRepository = new EvenementRepository();

// 1. On ajoute l'événement et on récupère son id
$idEvenement = $evenementRepository->ajouterEvenement($evenement);

// 2. La personne connectée devient organisateur de cet événement
$evenementRepository->ajouterOrganisateur($idEvenement, $_SESSION['id_utilisateur']);

header('Location: ../../public/evenement.php?id=' . $idEvenement . '&ok=cree');
exit;
