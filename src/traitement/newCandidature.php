<?php
require_once '../bdd/Bdd.php';
require_once '../modele/Candidature.php';
require_once '../repository/CandidatureRepository.php';

if (isset($_POST['motivation'], $_POST['ref_offre'])) {
    $candidature = new Candidature(null, $_POST['motivation'], date('Y-m-d H:i:s'), 'en_attente', $_SESSION['id_utilisateur'], $_POST['ref_offre']);
    $candidatureRepository = new CandidatureRepository();
}
?>