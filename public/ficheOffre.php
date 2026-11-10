<?php
require_once '../src/bdd/Bdd.php';
require_once '../src/modele/Offre.php';
require_once '../src/repository/OffreRepository.php';

// On récupère l'offre grâce à l'id dans l'adresse (?id=3)
$offre = null;
if (isset($_GET['id'])) {
    $offreRepository = new OffreRepository();
    $offre = $offreRepository->getOffre($_GET['id']);
}

include 'header.php';
?>

    <a href="listeOffre.php" class="btn btn-outline-secondary btn-sm mb-3">← Retour aux offres</a>


    <!-- ===== Les messages après une candidature ===== -->
<?php if (isset($_GET['ok'])) { ?>
    <div class="alert alert-success">Votre candidature a bien été envoyée !</div>
<?php } ?>

<?php if (isset($_GET['erreur']) && $_GET['erreur'] == 'deja') { ?>
    <div class="alert alert-warning">Vous avez déjà postulé à cette offre.</div>
<?php } ?>

<?php if (isset($_GET['erreur']) && $_GET['erreur'] == 'cloture') { ?>
    <div class="alert alert-danger">Cette offre est clôturée, vous ne pouvez plus postuler.</div>
<?php } ?>


<?php if ($offre == null) { ?>

    <div class="alert alert-danger">Cette offre n'existe pas.</div>

<?php } else { ?>

    <!-- ===== Les badges : type + état ===== -->
    <span class="badge bg-info text-dark text-uppercase"><?= $offre->getTypeOffre() ?></span>

    <?php if ($offre->getEtatOffre() == 'cloture') { ?>
        <span class="badge bg-secondary">Clôturée</span>
    <?php } else { ?>
        <span class="badge bg-success">Ouverte</span>
    <?php } ?>


    <!-- ===== Le titre et la date ===== -->
    <h1><?= htmlspecialchars($offre->getTitreOffre()) ?></h1>
    <p>Publiée le <?= $offre->getDateOffre() ?></p>


    <!-- ===== La description ===== -->
    <h4>Description :</h4>
    <p><?= htmlspecialchars($offre->getDescriptionOffre()) ?></p>


    <!-- ===== Les missions ===== -->
    <h4>Missions :</h4>
    <p><?= htmlspecialchars($offre->getMissionsOffre()) ?></p>


    <!-- ===== Le salaire (seulement s'il existe) ===== -->
    <?php if ($offre->getSalaireOffre() != null) { ?>
        <h4>Salaire :</h4>
        <p><?= $offre->getSalaireOffre() ?> €</p>
    <?php } ?>


    <!-- ===== Bouton Clôturer : seulement l'entreprise auteure, si l'offre est ouverte ===== -->
    <?php if (
            isset($_SESSION['id_utilisateur'], $_SESSION['role'])
            && $_SESSION['role'] == 'partenaire'
            && $_SESSION['id_utilisateur'] == $offre->getRefUtilisateur()
            && $offre->getEtatOffre() == 'ouvert'
    ) { ?>

        <a href="../src/traitement/cloturerOffre.php?id=<?= $offre->getIdOffre() ?>"
           class="btn btn-warning"
           onclick="return confirm('Voulez-vous vraiment clôturer cette offre ?');">
            Clôturer l'offre
        </a>

    <?php } ?>


    <!-- ===== Formulaire pour postuler : seulement étudiant ou médecin, si l'offre est ouverte ===== -->
    <?php if (
            isset($_SESSION['role'])
            && ($_SESSION['role'] == 'etudiant' || $_SESSION['role'] == 'medecin')
            && $offre->getEtatOffre() == 'ouvert'
    ) { ?>

        <h4 class="mt-4">Postuler à cette offre</h4>

        <form action="../src/traitement/newCandidature.php" method="post">

            <input type="hidden" name="ref_offre" value="<?= $offre->getIdOffre() ?>">

            <textarea name="motivation" class="form-control mb-3" rows="5"
                      placeholder="Votre motivation..." required></textarea>

            <button type="submit" class="btn btn-success">Envoyer ma candidature</button>

        </form>

    <?php } ?>

<?php } ?>

<?php include 'footer.php'; ?>