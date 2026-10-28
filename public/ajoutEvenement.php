<?php
require_once '../src/auth.php';
// Seuls les médecins et partenaires connectés peuvent créer un événement
exigerRole(['medecin', 'partenaire']);

$erreurs = [
    'champs' => 'Tous les champs obligatoires doivent être remplis.',
    'places' => 'Le nombre de places doit être au moins 1.',
    'date' => 'La date de l\'événement ne peut pas être dans le passé.',
];
include 'header.php';
?>
    <div class="col-md-8">
        <h1 class="mb-4">Créer un événement</h1>
        <?php if (isset($_GET['erreur'], $erreurs[$_GET['erreur']])) { ?>
            <div class="alert alert-danger"><?= $erreurs[$_GET['erreur']] ?></div>
        <?php } ?>
        <form action="../src/traitement/newEvenement.php" method="post">
            <div class="mb-3">
                <label class="form-label">Titre</label>
                <input type="text" name="titre" class="form-control" maxlength="150" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Type d'événement</label>
                <input type="text" name="type" class="form-control" maxlength="100" placeholder="Conférence, salon, atelier..." required>
            </div>
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="4" required></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Lieu (adresse)</label>
                <input type="text" name="adresse" class="form-control" maxlength="255" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Date</label>
                <input type="date" name="date_evenement" class="form-control" min="<?= date('Y-m-d') ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Éléments requis (pas obligatoire)</label>
                <input type="text" name="element_requis" class="form-control" maxlength="255" placeholder="Blouse, carte étudiante...">
            </div>
            <div class="mb-3">
                <label class="form-label">Nombre de places</label>
                <input type="number" name="nb_place" class="form-control" min="1" required>
            </div>
            <button type="submit" class="btn btn-primary w-100">Créer l'événement</button>
            <a href="listeEvenements.php" class="btn btn-outline-secondary w-100 mt-2">Annuler</a>
        </form>
    </div>
<?php include 'footer.php'; ?>
