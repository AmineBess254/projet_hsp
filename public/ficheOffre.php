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
    <span class="badge bg-info text-dark text-uppercase"><?= $offre->getTypeOffre() ?></span>
    <?php
    if ($offre->getEtatOffre() == 'cloture') {
        echo '<span class="badge bg-secondary">Clôturée</span>';
    }
    else {
        echo '<span class="badge bg-success">Ouverte</span>';
    }

    ?>


    <h1><?= htmlspecialchars($offre->getTitreOffre()) ?></h1>
    <p>Publiée le <?= $offre->getDateOffre() ?></p>

    <h4>Description :</h4>
    <p><?= htmlspecialchars($offre->getDescriptionOffre())?></p>

    <h4>Mission :</h4>
    <p><?=htmlspecialchars($offre->getMissionsOffre())?></p>

    <?php
    if($offre->getSalaireOffre() != null){ ?>
        <h4>Salaire :</h4>>
        <p><?= $offre->getSalaireOffre() ?>€</p>

    <?php } ?>
<?php
}
?>
<?php include 'footer.php'; ?>
