<?php
session_start();
require_once '../bdd/Bdd.php';
require_once '../modele/Utilisateurs.php';
require_once '../repository/UtilisateursRepository.php';

// 1. On cherche l'utilisateur grâce à son email
$repository = new UtilisateursRepository();
$utilisateur = $repository->getUtilisateurParEmail($_POST['email']);

// 2. Email inconnu ou mauvais mot de passe -> retour avec un message d'erreur
if ($utilisateur == null || !password_verify($_POST['mdp'], $utilisateur->getMdp())) {
    header('Location: ../../public/connexion.php?erreur=identifiants');
    exit;
}

// 3. Compte pas encore validé par un gestionnaire -> retour avec un autre message
if ($utilisateur->getStatutValidation() != 'valide') {
    header('Location: ../../public/connexion.php?erreur=attente');
    exit;
}

// 4. Tout est bon : on enregistre l'utilisateur dans la session
$_SESSION['id_utilisateur'] = $utilisateur->getIdUtilisateurs();
$_SESSION['role'] = $utilisateur->getRole();
$_SESSION['prenom'] = $utilisateur->getPrenom();

// 5. On l'envoie sur la page d'accueil
header('Location: ../../public/index.php');
exit;
