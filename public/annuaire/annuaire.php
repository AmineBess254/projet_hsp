<?php
require_once '../../src/auth.php';
require_once '../../src/bdd/Bdd.php';
require_once '../../src/repository/AnnuaireRepository.php';

exigerRole(['etudiant', 'medecin']);

$role = $_SESSION['role'];
$motCle = trim($_GET['keywords'] ?? '');
$annuaireRepository = new AnnuaireRepository();

if ($role == 'etudiant') {
    $titre = 'Annuaire des médecins du groupe';
    $indice = 'Rechercher par nom, spécialité ou hôpital';
    $personnes = $annuaireRepository->getMedecins($motCle);
} else {
    $titre = 'Annuaire des étudiants';
    $indice = 'Rechercher par nom, formation ou établissement';
    $personnes = $annuaireRepository->getEtudiants($motCle);
}

include '../header.php';
?>

    <h1 class="mb-4"><?= $titre ?></h1>


    <form action="/projet_hsp/public/annuaire/annuaire.php" method="get" class="mb-4">
        <div class="input-group">
            <input type="text" name="keywords" class="form-control" placeholder="<?= $indice ?>"
                   value="<?= htmlspecialchars($motCle) ?>">
            <button type="submit" class="btn btn-outline-primary">Rechercher</button>
        </div>
    </form>

<?php if ($motCle != '') { ?>
    <p class="text-muted">
        <?= count($personnes) ?> résultat(s) pour « <?= htmlspecialchars($motCle) ?> »
        <a href="/projet_hsp/public/annuaire/annuaire.php">Effacer la recherche</a>
    </p>
<?php } ?>

<?php if (count($personnes) == 0) { ?>
    <p class="text-muted">Aucun résultat.</p>
<?php } else { ?>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
            <?php if ($role == 'etudiant') { ?>
                <tr><th>Nom</th><th>Spécialité</th><th>Hôpital</th></tr>
            <?php } else { ?>
                <tr><th>Nom</th><th>Formation</th><th>Établissement</th><th>CV</th></tr>
            <?php } ?>
            </thead>
            <tbody>
            <?php foreach ($personnes as $personne) { ?>
                <tr>
                    <td><?= htmlspecialchars($personne['prenom'] . ' ' . $personne['nom']) ?></td>
                    <?php if ($role == 'etudiant') { ?>
                        <td><?= htmlspecialchars($personne['specialite']) ?></td>
                        <td><?= htmlspecialchars($personne['hopital']) ?></td>
                    <?php } else { ?>
                        <td><?= htmlspecialchars($personne['formation'] ?? '') ?></td>
                        <td><?= htmlspecialchars($personne['etablissement'] ?? '') ?></td>
                        <td>
                            <?php  ?>
                            <?php if (!empty($personne['cv']) && preg_match('#^https?://#i', $personne['cv'])) { ?>
                                <a href="<?= htmlspecialchars($personne['cv']) ?>" target="_blank" rel="noopener noreferrer"
                                   class="btn btn-sm btn-outline-primary">Voir le CV</a>
                            <?php } else { ?>
                                <span class="text-muted">Non renseigné</span>
                            <?php } ?>
                        </td>
                    <?php } ?>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
<?php } ?>

<?php include '../footer.php'; ?>