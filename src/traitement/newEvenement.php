<?php
require_once '../bdd/Bdd.php';
require_once '../modele/Evenement.php';
require_once '../repository/EvenementRepository.php';

if (isset( $_POST['type'],$_POST['titre'], $_POST['description'], $_POST['adresse'], $_POST['date_evenement'],$_POST['element_requis'], $_POST['nb_place'])) {
    $evenement = new Evenement(
        null,
        $_POST['type'],
        $_POST['titre'],
        $_POST['description'],
        $_POST['adresse'],
        $_POST['date_evenement'],
        $_POST['element_requis'] ?? null,
        (int) $_POST['nb_place']
    );
    $evenementRepository = new EvenementRepository();
    $evenementRepository->ajouterEvenement($evenement);

    header('Location: ../../public/ajoutEvenement.php?ok=1');
    exit;
}
header('Location: ../../public/ajoutEvenement.php?erreur=1');
exit;