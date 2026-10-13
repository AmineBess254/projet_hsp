<?php
require_once '../bdd/Bdd.php';
require_once '../modele/Evenement.php';
require_once '../repository/EvenementRepository.php';

if (isset($_POST['titre_evenement'], $_POST['type_evenement'], $_POST['description_evenement'], $_POST['$adresse_evenement'], $_POST['elementRequis_evenement'], $_POST['nbPlace_evenement'], $_POST['elementRequis_evenement'])) {
    $evenement = new Evenement( null, $_POST['titre_evenement'], $_POST['type_evenement'], $_POST['description_evenement'], $_POST['$adresse_evenement'], $_POST['elementRequis_evenement'], $_POST['nbPlace_evenement']);
    $evenementRepository = new EvenementRepository();
}
header('Location: ../../public/ajoutEvenement.php?ok=1');