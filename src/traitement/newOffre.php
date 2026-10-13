<?php
require_once '../bdd/Bdd.php';
require_once '../modele/Offre.php';
require_once '../repository/OffreRepository.php';
session_start();

//si personne n'est connecté, on fait comme si c'était l'utilisateur 1
if (!isset($_SESSION['id_utilisateur'])) {
    $_SESSION['id_utilisateur'] = 1;
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