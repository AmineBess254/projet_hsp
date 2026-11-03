<?php
require_once '../src/bdd/Bdd.php';
require_once '../src/modele/Offre.php';
require_once '../src/repository/OffreRepository.php';

$offre =null;
if(isset($_GET['id'])){
    $offreRepository = new OffreRepository();
    $offre = $offreRepository->getOffre($_GET['id']);

}
include 'header.php';
?>
<?php if ($offre == null) {
    ?>
    <div class="alert alert-danger">Cette offre n'existe pas.</div>
<?php
} else {
    ?>
    <h1><?= htmlspecialchars($offre->getTitreOffre()) ?></h1>
<?php
}
?>
<?php include 'footer.php'; ?>
