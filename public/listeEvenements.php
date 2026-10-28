<?php
require_once '../src/auth.php';
require_once '../src/bdd/Bdd.php';
require_once '../src/modele/Evenement.php';
require_once '../src/repository/EvenementRepository.php';

$evenementRepository = new EvenementRepository();
$evenements = $evenementRepository->getAllEvenements();

// Le bouton « Créer » n'apparaît que pour les médecins et partenaires
$peutCreer = estConnecte() && in_array($_SESSION['role'], ['medecin', 'partenaire']);

include 'header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Événements</h1>
    <?php if ($peutCreer) { ?>
        <a href="ajoutEvenement.php" class="btn btn-primary">+ Créer un événement</a>
    <?php } ?>
</div>

<?php if (count($evenements) == 0) { ?>
    <p class="text-muted">Aucun événement pour le moment.</p>
<?php } ?>

<div class="row">
    <?php foreach ($evenements as $evenement) { ?>
        <div class="col-md-6 mb-4">
            <div class="card h-100 <?= $evenement->estPasse() ? 'opacity-50' : '' ?>">
                <div class="card-body">
                    <span class="badge bg-info text-dark"><?= htmlspecialchars($evenement->getTypeEvenement()) ?></span>
                    <?php if ($evenement->estPasse()) { ?>
                        <span class="badge bg-secondary">Terminé</span>
                    <?php } elseif ($evenement->getPlacesRestantes() == 0) { ?>
                        <span class="badge bg-danger">Complet</span>
                    <?php } ?>
                    <h5 class="card-title mt-2"><?= htmlspecialchars($evenement->getTitreEvenement()) ?></h5>
                    <p class="card-text mb-1">📅 <?= date('d/m/Y', strtotime($evenement->getDateEvenement())) ?></p>
                    <p class="card-text mb-1">📍 <?= htmlspecialchars($evenement->getAdresseEvenement()) ?></p>
                    <p class="card-text">Places restantes : <strong><?= $evenement->getPlacesRestantes() ?></strong> / <?= $evenement->getNbPlaceEvenement() ?></p>
                    <a href="evenement.php?id=<?= $evenement->getIdEvenement() ?>" class="btn btn-outline-primary btn-sm">Voir la fiche</a>
                </div>
            </div>
        </div>
    <?php } ?>
</div>

<?php include 'footer.php'; ?>
