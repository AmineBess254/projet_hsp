<?php
require_once '../bdd/Bdd.php';
require_once '../modele/Offre.php';
require_once '../repository/OffreRepository.php';
session_start();

if (isset($_POST['type_offre'], $_POST['titre'], $_POST['date'], $_POST['description'], $_POST['missions'], $_POST['salaire'])) {
    $offre = new Offre(null, $_POST['type_offre'], $_POST['titre'], $_POST['date'], $_POST['description'], $_POST['missions'], $_POST['salaire'], 'ouvert', $_SESSION['id_utilisateur']);
    $offreRepository = new OffreRepository();
    $offreRepository->ajouterOffre($offre);
}