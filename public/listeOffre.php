<?php
require_once '../src/bdd/Bdd.php';
require_once '../src/modele/Offre.php';
require_once '../src/repository/OffreRepository.php';

$offreRepository = new OffreRepository();
$offres = $offreRepository->getAllOffre();

include 'header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Offres d'emploi et de stage</h1>
    <a href="ajoutOffre.php" class="btn btn-primary">+ Publier une offre</a>
</div>

<?php if (count($offres) == 0) { ?>
    <p class="text-muted">Aucune offre pour le moment.</p>
<?php } ?>

<div class="row">
    <?php foreach ($offres as $offre) { ?>
        <div class="col-md-6 mb-4">
            <div class="card h-100">
                <div class="card-body">
                    <span class="badge bg-info text-dark text-uppercase"><?= $offre->getTypeOffre() ?></span>
                    <?php if ($offre->getEtatOffre() == 'cloture') { ?>
                        <span class="badge bg-secondary">Clôturée</span>
                    <?php } ?>
                    <h5 class="card-title mt-2"><?= htmlspecialchars($offre->getTitreOffre()) ?></h5>
                    <p class="card-text"><?= htmlspecialchars($offre->getDescriptionOffre()) ?></p>
                    <?php if ($offre->getSalaireOffre() != null) { ?>
                        <p class="fw-bold">Salaire : <?= $offre->getSalaireOffre() ?> €</p>
                    <?php } ?>
                    <?php if (isset($_SESSION['id_utilisateur']) && $_SESSION['id_utilisateur'] == $offre->getRefUtilisateur()) {
                        ?>
                        <div class="mt-3">
                            <a href="modifierOffre.php?id=<?= $offre->getIdOffre() ?>" class="btn btn-sm btn-outline-primary">Modifier</a>
                            <a href="../src/traitement/supprimerOffre.php?id=<?= $offre->getIdOffre() ?>" class="btn btn-sm btn-outline-danger"
                               <!-- poupup -->
                               onclick="return confirm('Voulez-vous vraiment supprimer cette offre ?');">Supprimer</a>
                        </div>
                    <?php
                    }
                    ?>
                </div>
            </div>
        </div>
    <?php } ?>
</div>

<?php include 'footer.php'; ?>
