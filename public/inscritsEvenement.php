<?php
require_once '../src/auth.php';
require_once '../src/bdd/Bdd.php';
require_once '../src/modele/Evenement.php';
require_once '../src/repository/EvenementRepository.php';
exigerConnexion();

$evenementRepository = new EvenementRepository();
$idEvenement = (int) ($_GET['id'] ?? 0);
$evenement = $evenementRepository->getEvenement($idEvenement);

// Seuls les organisateurs de l'événement voient ses inscrits
if ($evenement == null || !$evenementRepository->estOrganisateur($idEvenement, $_SESSION['id_utilisateur'])) {
    header('Location: listeEvenements.php');
    exit;
}

$inscrits = $evenementRepository->getInscrits($idEvenement);
$libellesRole = ['etudiant' => 'Étudiant', 'medecin' => 'Médecin', 'partenaire' => 'Partenaire'];

include 'header.php';
?>

<a href="evenement.php?id=<?= $idEvenement ?>" class="text-decoration-none">← Retour à l'événement</a>
<h1 class="mt-2">Inscrits : <?= htmlspecialchars($evenement->getTitreEvenement()) ?></h1>
<p>Places restantes : <strong><?= $evenement->getPlacesRestantes() ?></strong> / <?= $evenement->getNbPlaceEvenement() ?></p>

<?php if (isset($_GET['ok'])) { ?>
    <div class="alert alert-success">L'inscription a été refusée. La place est de nouveau disponible.</div>
<?php } ?>

<?php if (count($inscrits) == 0) { ?>
    <p class="text-muted">Personne n'est encore inscrit.</p>
<?php } else { ?>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
            <tr><th>Nom</th><th>Email</th><th>Profil</th><th>Inscrit le</th><th>Statut</th><th></th></tr>
            </thead>
            <tbody>
            <?php foreach ($inscrits as $inscrit) { ?>
                <tr>
                    <td><?= htmlspecialchars($inscrit['prenom'] . ' ' . $inscrit['nom']) ?></td>
                    <td><?= htmlspecialchars($inscrit['email']) ?></td>
                    <td><?= $libellesRole[$inscrit['role']] ?? $inscrit['role'] ?></td>
                    <td><?= date('d/m/Y', strtotime($inscrit['date_inscription'])) ?></td>
                    <td>
                        <?php if ($inscrit['statut_inscription'] == 'refuse') { ?>
                            <span class="badge bg-danger">Refusé</span>
                        <?php } else { ?>
                            <span class="badge bg-success">Inscrit</span>
                        <?php } ?>
                    </td>
                    <td>
                        <?php if ($inscrit['statut_inscription'] != 'refuse') { ?>
                            <form action="../src/traitement/refuserInscription.php" method="post" onsubmit="return confirm('Refuser cette inscription ?');">
                                <input type="hidden" name="id_evenement" value="<?= $idEvenement ?>">
                                <input type="hidden" name="id_inscription" value="<?= $inscrit['id_inscription'] ?>">
                                <button type="submit" class="btn btn-sm btn-outline-danger">Refuser</button>
                            </form>
                        <?php } ?>
                    </td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
<?php } ?>

<?php include 'footer.php'; ?>
