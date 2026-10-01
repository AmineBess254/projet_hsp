<?php
require_once '../bdd/Bdd.php';
require_once '../modele/Entreprise.php';
require_once '../repository/EntrepriseRepository.php';

if (isset($_POST['nom'], $_POST['site_web'], $_POST['adresse'])) {
    $entreprise = new Entreprise(null, $_POST['nom'], $_POST['site_web'], $_POST['adresse']);
    $entrepriseRepository = new EntrepriseRepository();
}
?>