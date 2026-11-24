<?php
require_once '../../auth.php';
require_once '../../bdd/Bdd.php';
require_once '../../modele/Actualite.php';
require_once '../../repository/ActualiteRepository.php';

// Sécurité : seuls les gestionnaires publient des actualités
if (!estConnecte() || $_SESSION['role'] != 'gestionnaire') {
    header('Location: /projet_hsp/public/index.php');
    exit;
}

$titre = trim($_POST['titre'] ?? '');
$contenu = trim($_POST['contenu'] ?? '');
if ($titre == '' || $contenu == '') {
    header('Location: /projet_hsp/public/gestion/gestionActualites.php?erreur=1');
    exit;
}

// La date de publication est mise automatiquement par MySQL (CURRENT_TIMESTAMP)
$actualite = new Actualite(null, $titre, $contenu, null, $_SESSION['id_utilisateur']);
(new ActualiteRepository())->ajouterActualite($actualite);

header('Location: /projet_hsp/public/gestion/gestionActualites.php?ok=publiee');
exit;
