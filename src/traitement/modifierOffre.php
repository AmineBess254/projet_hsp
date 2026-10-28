<?php
require_once '../bdd/Bdd.php';
require_once '../modele/Offre.php';
require_once '../repository/OffreRepository.php';
session_start();

if (isset($_POST['id_offre'], $_POST['type_offre'], $_POST['titre'], $_POST['description'], $_POST['missions'])) {
    $offreRepository = new OffreRepository();

    $offre = $offreRepository->getOffre($_POST['id_offre']);

    //  SÉCURITÉ : on modifie seulement si l'offre existe ET que la personne connectée est l'auteur
    if ($offre != null && isset($_SESSION['id_utilisateur']) && $_SESSION['id_utilisateur'] == $offre->getRefUtilisateur()) {
        // Le salaire peut rester vide
        $salaire = $_POST['salaire'] ?? null;
        if ($salaire === '') {
            $salaire = null;
        }

        // 2. On remplace les infos par celles du formulaire
        $offre->setTypeOffre($_POST['type_offre']);
        $offre->setTitreOffre($_POST['titre']);
        $offre->setDescriptionOffre($_POST['description']);
        $offre->setMissionsOffre($_POST['missions']);
        $offre->setSalaireOffre($salaire);

        // 3. On enregistre dans la base
        $offreRepository->modifierOffre($offre);
    }
}
header('Location: ../../public/listeOffre.php');