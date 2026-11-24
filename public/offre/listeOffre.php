<?php
require_once '../../src/bdd/Bdd.php';
require_once '../../src/modele/Offre.php';
require_once '../../src/repository/OffreRepository.php';

$offreRepository = new OffreRepository();


//  filtre par type

if (isset($_GET['type']) && $_GET['type'] != '') {

    // Un type est choisi → seulement les offres de ce type
    $offres = $offreRepository->getOffresParType($_GET['type']);

} else {

    // Aucun type choisi → toutes les offres
    $offres = $offreRepository->getAllOffre();
}


include '../header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1>Offres d'emploi et de stage</h1>
    <?php
    if(isset($_SESSION['role']) && $_SESSION['role'] == 'partenaire'){?>
        <a href="/projet_hsp/public/offre/ajoutOffre.php" class="btn btn-primary">+ Publier une offre</a>
    <?php }?>

</div>


<!-- NOUVEAU : formulaire de filtre par type -->
<form action="/projet_hsp/public/offre/listeOffre.php" method="get" class="d-flex gap-2 mb-4">
    <select name="type" class="form-select w-auto">
        <option value="">Toutes les offres</option>
        <option value="stage">Stage</option>
        <option value="alternance">Alternance</option>
        <option value="cdd">CDD</option>
        <option value="cdi">CDI</option>
    </select>

    <button type="submit" class="btn btn-outline-primary">Filtrer</button>
</form>


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
                <a href="/projet_hsp/public/offre/ficheOffre.php?id=<?= $offre->getIdOffre() ?>" class="btn btn-sm btn-primary">Voir l'offre</a>
                <?php if ($offre->getSalaireOffre() != null) { ?>
                    <p class="fw-bold">Salaire : <?= $offre->getSalaireOffre() ?> €</p>
                <?php } ?>
                <?php if (isset($_SESSION['id_utilisateur']) && $_SESSION['id_utilisateur'] == $offre->getRefUtilisateur()) {
                ?>
                    <div class="mt-3">
                        <a href="/projet_hsp/public/offre/modifierOffre.php?id=<?= $offre->getIdOffre() ?>" class="btn btn-sm btn-outline-primary">Modifier</a>
                        <a href="/projet_hsp/src/traitement/offre/supprimerOffre.php?id=<?= $offre->getIdOffre() ?>" class="btn btn-sm btn-outline-danger"
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

<?php include '../footer.php'; ?>
