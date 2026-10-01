<?php
require_once '../bdd/Bdd.php';
require_once '../modele/Utilisateurs.php';
require_once '../repository/UtilisateursRepository.php';

if (isset($_POST['nom'], $_POST['prenom'], $_POST['email'], $_POST['mdp'])) {
    $mdpCrypte = password_hash($_POST['mdp'], PASSWORD_DEFAULT);
    $utilisateur = new Utilisateurs(null, $_POST['nom'], $_POST['prenom'], $_POST['email'], $mdpCrypte, 'en_attente', null);
    $utilisateursRepository = new UtilisateursRepository();
    $utilisateursRepository->ajouterUtilisateurs($utilisateur);
}