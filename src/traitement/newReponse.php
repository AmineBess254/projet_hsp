<?php
require_once '../bdd/Bdd.php';
require_once '../modele/Reponse.php';
require_once '../repository/ReponseRepository.php';
session_start();

if (isset($_POST['contenu'], $_POST['ref_post'])) {
    $reponse = new Reponse(null, $_POST['contenu'], date('Y-m-d H:i:s'), $_SESSION['id_utilisateur'], $_POST['ref_post']);
    $reponseRepository = new ReponseRepository();
    $reponseRepository->ajouterReponse($reponse);
}