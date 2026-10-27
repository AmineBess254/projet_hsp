<?php
require_once '../auth.php';
require_once '../bdd/Bdd.php';
require_once '../modele/Utilisateurs.php';
require_once '../repository/UtilisateursRepository.php';

$repository = new UtilisateursRepository();
$role = $_POST['role'];

// 1. Les champs communs doivent être remplis
if (empty($_POST['nom']) || empty($_POST['prenom']) || empty($_POST['email']) || empty($_POST['mdp'])) {
    header('Location: ../../public/inscription.php?erreur=champs');
    exit;
}

// 2. On n'accepte que ces 3 rôles (un gestionnaire ne peut pas s'inscrire lui-même)
if ($role != 'etudiant' && $role != 'medecin' && $role != 'partenaire') {
    header('Location: ../../public/inscription.php?erreur=champs');
    exit;
}

// 3. Les champs du rôle choisi doivent être remplis
if ($role == 'etudiant' && (empty($_POST['formation']) || empty($_POST['cv']))) {
    header('Location: ../../public/inscription.php?erreur=champs');
    exit;
}
if ($role == 'partenaire' && empty($_POST['poste'])) {
    header('Location: ../../public/inscription.php?erreur=champs');
    exit;
}

// 4. L'email ne doit pas déjà exister
if ($repository->emailExiste($_POST['email'])) {
    header('Location: ../../public/inscription.php?erreur=email');
    exit;
}

// 5. On enregistre l'utilisateur (mot de passe chiffré, compte "en_attente" par défaut)
$mdpCrypte = password_hash($_POST['mdp'], PASSWORD_DEFAULT);
$utilisateur = new Utilisateurs(null, $_POST['nom'], $_POST['prenom'], $_POST['email'], $mdpCrypte, 'en_attente', null, $role);
$idUtilisateur = $repository->ajouterUtilisateur($utilisateur);

// 6. On enregistre son profil selon son rôle
if ($role == 'etudiant') {
    $repository->ajouterEtudiant($idUtilisateur, $_POST['cv'], $_POST['formation']);
}
if ($role == 'medecin') {
    $repository->ajouterMedecin($idUtilisateur, $_POST['specialite'], $_POST['ref_hopital']);
}
if ($role == 'partenaire') {
    $repository->ajouterPartenaire($idUtilisateur, $_POST['poste']);
}

// 7. On garde son id et son rôle pour l'étape suivante, puis on l'envoie au rattachement
$_SESSION['id_inscription'] = $idUtilisateur;
$_SESSION['role_inscription'] = $role;

header('Location: ../../public/rattachement.php');
exit;