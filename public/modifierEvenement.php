<?php
require_once '../src/auth.php';
require_once '../src/bdd/Bdd.php';
require_once '../src/modele/Evenement.php';
require_once '../src/repository/EvenementRepository.php';
exigerConnexion();

$evenementRepository = new EvenementRepository();
$idEvenement = (int) ($_GET['id'] ?? 0);
$evenement = $evenementRepository->getEvenement($idEvenement);

// Seuls les organisateurs de l'événement peuvent le modifier
if ($evenement == null || !$evenementRepository->estOrganisateur($idEvenement, $_SESSION['id_utilisateur'])) {
    header('Location: listeEvenements.php');
    exit;
}

$erreurs = [
    'champs' => 'Tous les champs obligatoires doivent être remplis.',
    'places' => 'Le nombre de places doit être au moins 1, et pas inférieur au nombre d\'inscrits (' . $evenement->getNbInscritsEvenement() . ').',
];
include 'header.php';
?>
    <div class="col-md-8">
        <h1 class="mb-4">Modifier l'événement</h1>
        <?php if (isset($_GET['erreur'], $erreurs[$_GET['erreur']])) { ?>
            <div class="alert alert-danger"><?= $erreurs[$_GET['erreur']] ?></div>
        <?php } ?>
        <form action="../src/traitement/modifierEvenement.php" method="post">
            <input type="hidden" name="id_evenement" value="<?= $idEvenement ?>">
            <div class="mb-3">
                <label class="form-label">Titre</label>
                <input type="text" name="titre" class="form-control" maxlength="150" value="<?= htmlspecialchars($evenement->getTitreEvenement()) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Type d'événement</label>
                <input type="text" name="type" class="form-control" maxlength="100" value="<?= htmlspecialchars($evenement->getTypeEvenement()) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="4" required><?= htmlspecialchars($evenement->getDescriptionEvenement()) ?></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Lieu (adresse)</label>
                <input type="text" name="adresse" class="form-control" maxlength="255" value="<?= htmlspecialchars($evenement->getAdresseEvenement()) ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Date</label>
                <input type="date" name="date_evenement" class="form-control" value="<?= $evenement->getDateEvenement() ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Éléments requis (pas obligatoire)</label>
                <input type="text" name="element_requis" class="form-control" maxlength="255" value="<?= htmlspecialchars($evenement->getElementRequisEvenement() ?? '') ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Nombre de places</label>
                <input type="number" name="nb_place" class="form-control" min="<?= max(1, $evenement->getNbInscritsEvenement()) ?>" value="<?= $evenement->getNbPlaceEvenement() ?>" required>
                <div class="form-text"><?= $evenement->getNbInscritsEvenement() ?> personne(s) déjà inscrite(s).</div>
            </div>
            <button type="submit" class="btn btn-primary w-100">Enregistrer les modifications</button>
            <a href="evenement.php?id=<?= $idEvenement ?>" class="btn btn-outline-secondary w-100 mt-2">Annuler</a>
        </form>
    </div>
<?php include 'footer.php'; ?>
