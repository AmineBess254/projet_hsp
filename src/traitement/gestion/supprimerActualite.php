<?php
require_once '../../auth.php';
require_once '../../bdd/Bdd.php';
require_once '../../modele/Actualite.php';
require_once '../../repository/ActualiteRepository.php';

// Sécurité : seuls les gestionnaires suppriment des actualités
if (!estConnecte() || $_SESSION['role'] != 'gestionnaire') {
    header('Location: /projet_hsp/public/index.php');
    exit;
}

$idActualite = (int) ($_POST['id_actualite'] ?? 0);
(new ActualiteRepository())->supprimerActualite($idActualite);

header('Location: /projet_hsp/public/gestion/gestionActualites.php?ok=supprimee');
exit;
