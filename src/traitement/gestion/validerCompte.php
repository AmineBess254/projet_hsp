<?php
require_once '../../auth.php';
require_once '../../bdd/Bdd.php';
require_once '../../modele/Utilisateurs.php';
require_once '../../repository/UtilisateursRepository.php';

// Sécurité : seul un gestionnaire peut valider ou refuser un compte
if (!estConnecte() || $_SESSION['role'] != 'gestionnaire') {
    header('Location: /projet_hsp/public/index.php');
    exit;
}

// Le statut doit être "valide" ou "refuse", rien d'autre
$statut = $_POST['statut'];
if ($statut != 'valide' && $statut != 'refuse') {
    header('Location: /projet_hsp/public/gestion/gestion.php');
    exit;
}

// On change le statut et on note quel gestionnaire a décidé
$repository = new UtilisateursRepository();
$repository->changerStatutValidation($_POST['id'], $statut, $_SESSION['id_utilisateur']);

// Retour à la page de gestion avec un message
header('Location: /projet_hsp/public/gestion/gestion.php?ok=' . $statut);
exit;
