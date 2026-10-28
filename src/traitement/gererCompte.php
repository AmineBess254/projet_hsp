<?php
require_once '../auth.php';
require_once '../bdd/Bdd.php';
require_once '../modele/Utilisateurs.php';
require_once '../repository/UtilisateursRepository.php';

// Sécurité : seul un gestionnaire peut gérer les comptes
if (!estConnecte() || $_SESSION['role'] != 'gestionnaire') {
    header('Location: ../../public/index.php');
    exit;
}

$idCompte = $_POST['id'];
$action = $_POST['action'];

// Un gestionnaire ne peut pas se désactiver ou se supprimer lui-même
if ($idCompte == $_SESSION['id_utilisateur']) {
    header('Location: ../../public/gestion.php');
    exit;
}

$repository = new UtilisateursRepository();

// On fait l'action demandée
if ($action == 'desactiver') {
    $repository->changerStatutValidation($idCompte, 'desactive', $_SESSION['id_utilisateur']);
} elseif ($action == 'reactiver') {
    $repository->changerStatutValidation($idCompte, 'valide', $_SESSION['id_utilisateur']);
} elseif ($action == 'supprimer') {
    $repository->supprimerUtilisateur($idCompte);
} else {
    // Action inconnue : on ne fait rien
    header('Location: ../../public/gestion.php');
    exit;
}

// Retour à la page de gestion avec un message
header('Location: ../../public/gestion.php?ok=' . $action);
exit;
