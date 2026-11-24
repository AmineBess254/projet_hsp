<?php
require_once '../../src/auth.php';
require_once '../../src/bdd/Bdd.php';
require_once '../../src/modele/Actualite.php';
require_once '../../src/repository/ActualiteRepository.php';
// Page réservée aux gestionnaires
exigerRole(['gestionnaire']);

$actualites = (new ActualiteRepository())->getAllActualites();
$messagesOk = [
    'publiee' => 'L\'actualité a bien été publiée sur la page d\'accueil.',
    'supprimee' => 'L\'actualité a bien été supprimée.',
];

include '../header.php';
?>

<h1 class="mb-4">Actualités de la page d'accueil</h1>

<?php if (isset($_GET['ok'], $messagesOk[$_GET['ok']])) { ?>
    <div class="alert alert-success"><?= $messagesOk[$_GET['ok']] ?></div>
<?php } ?>
<?php if (isset($_GET['erreur'])) { ?>
    <div class="alert alert-danger">Le titre et le contenu sont obligatoires.</div>
<?php } ?>

<div class="row">
    <!-- Formulaire de publication -->
    <div class="col-md-5 mb-4">
        <div class="card">
            <div class="card-body">
                <h5 class="card-title">Publier une actualité</h5>
                <form action="/projet_hsp/src/traitement/gestion/newActualite.php" method="post">
                    <div class="mb-3">
                        <label class="form-label">Titre</label>
                        <input type="text" name="titre" class="form-control" maxlength="150" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Contenu</label>
                        <textarea name="contenu" class="form-control" rows="5" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Publier</button>
                </form>
            </div>
        </div>
    </div>

    <!-- Actualités déjà publiées -->
    <div class="col-md-7">
        <h5>Actualités publiées</h5>
        <?php if (count($actualites) == 0) { ?>
            <p class="text-muted">Aucune actualité pour le moment.</p>
        <?php } ?>
        <?php foreach ($actualites as $actualite) { ?>
            <div class="card mb-3">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start">
                        <h6 class="card-title"><?= htmlspecialchars($actualite->getTitre()) ?></h6>
                        <form action="/projet_hsp/src/traitement/gestion/supprimerActualite.php" method="post" onsubmit="return confirm('Supprimer cette actualité ?');">
                            <input type="hidden" name="id_actualite" value="<?= $actualite->getIdActualite() ?>">
                            <button type="submit" class="btn btn-sm btn-outline-danger">Supprimer</button>
                        </form>
                    </div>
                    <p class="text-muted small mb-2">Publié le <?= date('d/m/Y à H:i', strtotime($actualite->getDatePublication())) ?></p>
                    <p class="card-text mb-0"><?= nl2br(htmlspecialchars($actualite->getContenu())) ?></p>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

<?php include '../footer.php'; ?>
