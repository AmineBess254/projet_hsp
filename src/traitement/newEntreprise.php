<?php
require_once '../bdd/Bdd.php';
require_once '../modele/Entreprise.php';
require_once '../repository/EntrepriseRepository.php';

if (isset($_POST['nom'], $_POST['site_web'], $_POST['adresse'])) {
    $entrepriseRepository = new EntrepriseRepository();

    if ($entrepriseRepository->siteWebExiste($_POST['site_web'])) {
        header('Location: ../../public/ajoutEntreprise.php?erreur=doublon');
        exit;
    }

    $entreprise = new Entreprise(null, $_POST['nom'], $_POST['site_web'], $_POST['adresse']);
    $entrepriseRepository->ajouterEntreprise($entreprise);

    header('Location: ../../public/ajoutEntreprise.php?ok=1');
    exit;
}