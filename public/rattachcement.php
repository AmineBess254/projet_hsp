<?php
require_once '../auth.php';
require_once '../bdd/Bdd.php';
require_once '../modele/Utilisateurs.php';
require_once '../modele/Entreprise.php';
require_once '../modele/Etablissement.php';
require_once '../repository/UtilisateursRepository.php';
require_once '../repository/EntrepriseRepository.php';
require_once '../repository/EtablissementRepository.php';

// Sécurité : il faut venir de l'inscription
if (!isset($_SESSION['id_inscription'])) {
    header('Location: ../../public/inscription.php');
    exit;
}

$idUtilisateur = $_SESSION['id_inscription'];
$role = $_SESSION['role_inscription'];

$utilisateursRepository = new UtilisateursRepository();
$entrepriseRepository = new EntrepriseRepository();
$etablissementRepository = new EtablissementRepository();

// 1. On trouve l'id de l'entreprise ou de l'établissement à rattacher
if ($_POST['choix'] == 'existant') {
    $idCible = $_POST['id'];
} else {
    // Nouveau : on vérifie d'abord si le site web existe déjà (contrôle des doublons)
    if ($role == 'partenaire') {
        $existant = $entrepriseRepository->getEntrepriseParSiteWeb($_POST['site_web']);
        if ($existant != null) {
            $idCible = $existant->getIdEntreprise();
        } else {
            $entreprise = new Entreprise(null, $_POST['nom'], $_POST['site_web'], $_POST['adresse']);
            $idCible = $entrepriseRepository->ajouterEntreprise($entreprise);
        }
    } else {
        $existant = $etablissementRepository->getEtablissementParSiteWeb($_POST['site_web']);
        if ($existant != null) {
            $idCible = $existant->getIdEtablissement();
        } else {
            $etablissement = new Etablissement(null, $_POST['nom'], $_POST['adresse'], $_POST['site_web']);
            $idCible = $etablissementRepository->ajouterEtablissement($etablissement);
        }
    }
}

// 2. On fait le rattachement selon le rôle
if ($role == 'partenaire') {
    $utilisateursRepository->rattacherEntreprise($idUtilisateur, $idCible);
}
if ($role == 'etudiant') {
    $utilisateursRepository->rattacherEtablissementEtudiant($idUtilisateur, $idCible);
}
if ($role == 'medecin') {
    $utilisateursRepository->rattacherEtablissementMedecin($idUtilisateur, $idCible);
}

// 3. Inscription terminée : on efface les infos temporaires de la session
unset($_SESSION['id_inscription']);
unset($_SESSION['role_inscription']);

header('Location: ../../public/inscription.php?ok=1');
exit;