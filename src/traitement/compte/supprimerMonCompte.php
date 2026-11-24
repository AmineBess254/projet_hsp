<?php
require_once '../../auth.php';
require_once '../../bdd/Bdd.php';
require_once '../../modele/Utilisateurs.php';
require_once '../../repository/UtilisateursRepository.php';

// Sécurité : il faut être connecté
if (!estConnecte()) {
    header('Location: /projet_hsp/public/compte/connexion.php');
    exit;
}

// On supprime TOUJOURS le compte de la session, jamais un id envoyé par le formulaire
$idUtilisateur = $_SESSION['id_utilisateur'];
$repository = new UtilisateursRepository();
$utilisateur = $repository->getUtilisateur($idUtilisateur);

// 1. Un gestionnaire ne peut pas se supprimer ici (il faut toujours au moins un gestionnaire)
if ($utilisateur->getRole() == 'gestionnaire') {
    header('Location: /projet_hsp/public/compte/profil.php');
    exit;
}

// 2. On demande le mot de passe pour être sûr que c'est bien la personne
if (empty($_POST['mdp']) || !password_verify($_POST['mdp'], $utilisateur->getMdp())) {
    header('Location: /projet_hsp/public/compte/profil.php?erreur=suppression');
    exit;
}

// 3. On supprime le compte (le reste part avec, grâce à ON DELETE CASCADE)
$repository->supprimerUtilisateur($idUtilisateur);

// 4. On vide la session : la personne est déconnectée
session_unset();
session_destroy();

header('Location: /projet_hsp/public/index.php');
exit;
