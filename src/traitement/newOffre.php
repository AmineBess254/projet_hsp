<?php
require_once '../bdd/Bdd.php';
require_once '../modele/Offre.php';
require_once '../repository/OffreRepository.php';
session_start();

// SÉCURITÉ : seuls un partenaire ou un gestionnaire connecté peuvent publier
if (!isset($_SESSION['id_utilisateur'], $_SESSION['role']) || !in_array($_SESSION['role'], ['partenaire'])) {
    header('Location: ../../public/connexion.php');
    exit();
}

if (isset($_POST['type_offre'], $_POST['titre'], $_POST['description'], $_POST['missions'])) {
    $salaire = $_POST['salaire'] ?? null;
    if ($salaire === '') {
        $salaire = null;
    }

    $offre = new Offre(null, $_POST['type_offre'], $_POST['titre'], date('Y-m-d'), $_POST['description'], $_POST['missions'], $salaire, 'ouvert', $_SESSION['id_utilisateur']);
    $offreRepository = new OffreRepository();
    $offreRepository->ajouterOffre($offre);
}
header('Location: ../../public/ajoutOffre.php?ok=1');