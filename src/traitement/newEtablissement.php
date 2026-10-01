<?php
require_once '../bdd/Bdd.php';
require_once '../modele/Etablissement.php';
require_once '../repository/EtablissementRepository.php';

if (isset($_POST['nom'], $_POST['adresse'], $_POST['site_web'])) {
    $etablissement = new Etablissement( null, $_POST['nom'], $_POST['adresse'], $_POST['site_web']);
    $etablissementRepository = new EtablissementRepository();
}
?>