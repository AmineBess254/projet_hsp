<?php
require_once '../auth.php';
require_once '../bdd/Bdd.php';
require_once '../modele/Utilisateurs.php';
require_once '../repository/UtilisateursRepository.php';

// Sécurité : seul un gestionnaire peut créer un autre gestionnaire
if (!estConnecte() || $_SESSION['role'] != 'gestionnaire') {
    header('Location: ../../public/index.php');
    exit;
}

$repository = new UtilisateursRepository();

// L'email ne doit pas déjà exister
if ($repository->emailExiste($_POST['email'])) {
    header('Location: ../../public/gestion.php?erreur=email');
    exit;
}

// 1. On crée le compte avec le rôle gestionnaire
$mdpCrypte = password_hash($_POST['mdp'], PASSWORD_DEFAULT);
$nouveau = new Utilisateurs(null, $_POST['nom'], $_POST['prenom'], $_POST['email'], $mdpCrypte, 'en_attente', null, 'gestionnaire');
$idNouveau = $repository->ajouterUtilisateur($nouveau);

// 2. Il est validé tout de suite (créé par un gestionnaire, pas besoin d'attendre)
$repository->changerStatutValidation($idNouveau, 'valide', $_SESSION['id_utilisateur']);

// 3. On l'ajoute dans la table gestionnaire, en notant qui l'a créé
$repository->ajouterGestionnaire($idNouveau, $_SESSION['id_utilisateur']);

header('Location: ../../public/gestion.php?ok=gestionnaire');
exit;