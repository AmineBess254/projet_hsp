<?php
require_once '../bdd/Bdd.php';
require_once '../modele/Hopital.php';
require_once '../repository/HopitalRepository.php';

if (isset($_POST['nom'], $_POST['adresse'])) {
    $hopital = new Hopital(null, $_POST['nom'], $_POST['adresse']);
    $hopitalRepository = new HopitalRepository();
    $hopitalRepository->ajouterHopital($hopital);
}
