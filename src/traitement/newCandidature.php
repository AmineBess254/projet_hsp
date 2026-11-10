<?php
require_once '../bdd/Bdd.php';
require_once '../modele/Candidature.php';
require_once '../repository/CandidatureRepository.php';
require_once '../modele/Offre.php';
require_once '../repository/OffreRepository.php';

session_start();
// que les etudiant et medecin peuvent postuler
if (!isset($_SESSION['id_utilisateur'], $_SESSION['role'])
    || !in_array($_SESSION['role'], ['etudiant', 'medecin'])) {
    header('Location: ../../public/listeOffre.php');
    exit();
}

if (isset($_POST['motivation'], $_POST['ref_offre'])) {

    $offreRepository = new OffreRepository();
    $offre = $offreRepository->getOffre($_POST['ref_offre']);
// l offre doit etre ouverte
    if ($offre == null || $offre->getEtatOffre() == 'cloture') {
        header('Location: ../../public/ficheOffre.php?id=' . $_POST['ref_offre'] . '&erreur=cloture');
        exit();
    }
// on postule que une fois
    $candidatureRepository = new CandidatureRepository();
    if ($candidatureRepository->candidatureExiste($_SESSION['id_utilisateur'], $_POST['ref_offre'])) {
        header('Location: ../../public/ficheOffre.php?id=' . $_POST['ref_offre'] . '&erreur=deja');
        exit();
    }

    $candidature = new Candidature(null, $_POST['motivation'], date('Y-m-d H:i:s'), 'en_attente', $_SESSION['id_utilisateur'], $_POST['ref_offre']);
    $candidatureRepository->ajouterCandidature($candidature);

    header('Location: ../../public/ficheOffre.php?id=' . $_POST['ref_offre'] . '&ok=1');
    exit();
}

header('Location: ../../public/listeOffre.php');