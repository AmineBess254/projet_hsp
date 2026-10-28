<?php
require_once '../auth.php';
require_once '../bdd/Bdd.php';
require_once '../modele/Evenement.php';
require_once '../repository/EvenementRepository.php';

$idEvenement = (int) ($_POST['id_evenement'] ?? 0);
$evenementRepository = new EvenementRepository();
$evenement = $evenementRepository->getEvenement($idEvenement);

// Sécurité : l'événement doit exister et la personne connectée doit en être organisatrice
if (!estConnecte() || $evenement == null || !$evenementRepository->estOrganisateur($idEvenement, $_SESSION['id_utilisateur'])) {
    header('Location: ../../public/listeEvenements.php');
    exit;
}

$titre = trim($_POST['titre'] ?? '');
$type = trim($_POST['type'] ?? '');
$description = trim($_POST['description'] ?? '');
$adresse = trim($_POST['adresse'] ?? '');
$date = $_POST['date_evenement'] ?? '';
$nbPlace = (int) ($_POST['nb_place'] ?? 0);
$retour = '../../public/modifierEvenement.php?id=' . $idEvenement;

if ($titre == '' || $type == '' || $description == '' || $adresse == '' || $date == '') {
    header('Location: ' . $retour . '&erreur=champs');
    exit;
}
// On ne peut pas descendre en dessous du nombre de personnes déjà inscrites
if ($nbPlace < 1 || $nbPlace < $evenement->getNbInscritsEvenement()) {
    header('Location: ' . $retour . '&erreur=places');
    exit;
}

$elementRequis = trim($_POST['element_requis'] ?? '');
if ($elementRequis == '') {
    $elementRequis = null;
}

// On remplace les infos par celles du formulaire, puis on enregistre
$evenement->setTitreEvenement($titre);
$evenement->setTypeEvenement($type);
$evenement->setDescriptionEvenement($description);
$evenement->setAdresseEvenement($adresse);
$evenement->setDateEvenement($date);
$evenement->setElementRequisEvenement($elementRequis);
$evenement->setNbPlaceEvenement($nbPlace);
$evenementRepository->modifierEvenement($evenement);

header('Location: ../../public/evenement.php?id=' . $idEvenement . '&ok=modifie');
exit;
