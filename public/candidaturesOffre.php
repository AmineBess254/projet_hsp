<?php
require_once '../src/bdd/Bdd.php';
require_once '../src/modele/Offre.php';
require_once '../src/repository/OffreRepository.php';
require_once '../src/modele/Candidature.php';
require_once '../src/repository/CandidatureRepository.php';
require_once '../src/modele/Utilisateurs.php';
require_once '../src/repository/UtilisateursRepository.php';

include 'header.php';

// 1. On récupère l'offre (?id=3)
$offreRepository = new OffreRepository();
$offre = $offreRepository->getOffre($_GET['id']);

// 2. On récupère les candidatures de cette offre (sans les masquées)
$candidatureRepository = new CandidatureRepository();
$candidatures = $candidatureRepository->getCandidaturesParOffre($_GET['id']);

// 3. Pour aller chercher le nom de chaque candidat
$utilisateursRepository = new UtilisateursRepository();
?>


<?php if ($offre != null && $_SESSION['id_utilisateur'] == $offre->getRefUtilisateur()) { ?>

    <a href="ficheOffre.php?id=<?= $offre->getIdOffre() ?>" class="btn btn-outline-secondary btn-sm mb-3">← Retour à l'offre</a>

    <h1>Candidatures pour : <?= htmlspecialchars($offre->getTitreOffre()) ?></h1>


    <?php if (count($candidatures) == 0) { ?>

        <!-- Personne n'a encore postulé -->
        <p class="text-muted">Aucune candidature pour le moment.</p>

    <?php } ?>


    <?php foreach ($candidatures as $candidature) { ?>

        <?php
        // On va chercher la personne qui a postulé
        $candidat = $utilisateursRepository->getUtilisateurParId($candidature->getRefUtilisateur());
        ?>

        <div class="card mb-3">
            <div class="card-body">

                <!-- Le nom et l'email du candidat -->
                <h5><?= htmlspecialchars($candidat->getPrenom() . ' ' . $candidat->getNom()) ?></h5>
                <p class="text-muted"><?= htmlspecialchars($candidat->getEmail()) ?></p>

                <!-- La date de la candidature -->
                <p>Postulé le <?= $candidature->getDateCandidature() ?></p>

                <!-- La motivation -->
                <p><strong>Motivation :</strong></p>
                <p><?= htmlspecialchars($candidature->getMotivation()) ?></p>

                <!-- Le bouton pour masquer (refuser) la candidature -->
                <a href="../src/traitement/masquerCandidature.php?id=<?= $candidature->getIdCandidature() ?>"
                   class="btn btn-outline-danger btn-sm"
                   onclick="return confirm('Masquer cette candidature ?');">
                    Masquer
                </a>

            </div>
        </div>

    <?php } ?>


<?php } else { ?>

    <!-- Ce n'est pas l'auteur de l'offre -->
    <div class="alert alert-danger">Vous ne pouvez voir que les candidatures de vos propres offres.</div>

<?php } ?>


<?php include 'footer.php'; ?>