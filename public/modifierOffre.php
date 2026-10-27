<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once '../src/bdd/Bdd.php';
require_once '../src/modele/Offre.php';
require_once '../src/repository/OffreRepository.php';

$offre = null;
if (isset($_GET['id'])) {
    $offreRepository = new OffreRepository();
    $offre = $offreRepository->getOffre($_GET['id']);
}
include 'header.php';
?>
    <h1>Modifier une offre</h1>
<?php if ($offre == null) { ?>
    <div class="alert alert-danger">Cette offre existe pas</div>
<?php } else { ?>
    <form action="../src/traitement/modifierOffre.php" method="post">
    <input type="hidden" name="id_offre" value="<?= $offre->getIdOffre() ?>">

    <div class="mb-3">
        <label class="form-label">Titre de l'offre</label>
        <input type="text" name="titre" class="form-control" value="<?= htmlspecialchars($offre->getTitreOffre()) ?>" required>
    </div>
    <div class="mb-3">
        <label class="form-label">Type d'offre</label>
        <select name="type_offre" class="form-select">
            <option value="stage" <?= $offre->getTypeOffre() == 'stage' ? 'selected' : '' ?>>Stage</option>
            <option value="alternance" <?= $offre->getTypeOffre() == 'alternance' ? 'selected' : '' ?>>Alternance</option>
            <option value="cdd" <?= $offre->getTypeOffre() == 'cdd' ? 'selected' : '' ?>>CDD</option>
            <option value="cdi" <?= $offre->getTypeOffre() == 'cdi' ? 'selected' : '' ?>>CDI</option>
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label">Description</label>
        <textarea name="description" class="form-control" rows="4" required><?= htmlspecialchars($offre->getDescriptionOffre()) ?></textarea>
    </div>
    <div class="mb-3">
        <label class="form-label">Missions</label>
        <textarea name="missions" class="form-control" rows="4" required><?= htmlspecialchars($offre->getMissionsOffre()) ?></textarea>
    </div>
    <div class="mb-3">
        <label class="form-label">Salaire (pas obligatoire)</label>
        <input type="number" name="salaire" class="form-control" step="0.01" value="<?= $offre->getSalaireOffre() ?>">
    </div>
    <button type="submit" class="btn btn-primary w-100">Enregistrer les modifications</button>
    <a href="listeOffre.php" class="btn btn-outline-secondary w-100 mt-2">Annuler</a>
    </form><?php } ?>
<?php include 'footer.php'; ?>