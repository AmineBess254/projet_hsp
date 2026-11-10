<?php
require_once '../auth.php';
require_once '../bdd/Bdd.php';
require_once '../modele/Utilisateurs.php';
require_once '../repository/UtilisateursRepository.php';

// Sécurité : il faut être connecté
if (!estConnecte()) {
    header('Location: ../../public/connexion.php');
    exit;
}

// On modifie TOUJOURS le compte de la session, jamais un id envoyé par le formulaire
// (sinon quelqu'un pourrait modifier le profil d'un autre)
$idUtilisateur = $_SESSION['id_utilisateur'];
$repository = new UtilisateursRepository();
$utilisateur = $repository->getUtilisateur($idUtilisateur);
$role = $utilisateur->getRole();

// 1. Les champs communs doivent être remplis
if (empty($_POST['nom']) || empty($_POST['prenom']) || empty($_POST['email'])) {
    header('Location: ../../public/profil.php?erreur=champs');
    exit;
}

// 2. Les champs du rôle doivent être remplis
if ($role == 'etudiant' && (empty($_POST['formation']) || empty($_POST['cv']))) {
    header('Location: ../../public/profil.php?erreur=champs');
    exit;
}
if ($role == 'medecin' && (empty($_POST['ref_specialite']) || empty($_POST['ref_hopital']))) {
    header('Location: ../../public/profil.php?erreur=champs');
    exit;
}
if ($role == 'partenaire' && empty($_POST['poste'])) {
    header('Location: ../../public/profil.php?erreur=champs');
    exit;
}

// 3. Le nouvel email ne doit pas appartenir à quelqu'un d'autre
if ($repository->emailExistePourAutre($_POST['email'], $idUtilisateur)) {
    header('Location: ../../public/profil.php?erreur=email');
    exit;
}

// 4. Si un nouveau mot de passe est demandé, on vérifie d'abord l'ancien
if (!empty($_POST['nouveau_mdp'])) {

    // Le nouveau mot de passe doit faire au moins 8 caractères
    if (strlen($_POST['nouveau_mdp']) < 8) {
        header('Location: ../../public/profil.php?erreur=mdpcourt');
        exit;
    }

    if (empty($_POST['mdp_actuel']) || !password_verify($_POST['mdp_actuel'], $utilisateur->getMdp())) {
        header('Location: ../../public/profil.php?erreur=mdp');
        exit;
    }
    $repository->modifierMdp($idUtilisateur, password_hash($_POST['nouveau_mdp'], PASSWORD_DEFAULT));
}

// 5. On enregistre les infos communes
$repository->modifierUtilisateur($idUtilisateur, $_POST['nom'], $_POST['prenom'], $_POST['email']);

// 6. On enregistre les infos du rôle
if ($role == 'etudiant') {
    $repository->modifierEtudiant($idUtilisateur, $_POST['cv'], $_POST['formation']);
}
if ($role == 'medecin') {
    $repository->modifierMedecin($idUtilisateur, $_POST['ref_specialite'], $_POST['ref_hopital']);
}
if ($role == 'partenaire') {
    $repository->modifierPartenaire($idUtilisateur, $_POST['poste']);
}

// 7. On met à jour le prénom dans la session (pour le « Bonjour ... » du menu)
$_SESSION['prenom'] = $_POST['prenom'];

header('Location: ../../public/profil.php?ok=1');
exit;