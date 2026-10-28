<?php

require_once '../bdd/Bdd.php';
require_once '../modele/Offre.php';
require_once '../repository/OffreRepository.php';

session_start();

if(isset($_GET['id'])){
    $OffreRepository = new OffreRepository();

    $offre= $OffreRepository->getOffre($_GET['id']);

    if($offre != null && isset($_SESSION['id_utilisateur'])&& $_SESSION['id_utilisateur']==$offre->getRefUtilisateur()){
        $OffreRepository-> supprimerOffre($offre->getIdOffre());
    }
}
header('Location: ../../public/listeOffre.php');
