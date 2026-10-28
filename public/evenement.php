<?php
require_once '../src/auth.php';
require_once '../src/bdd/Bdd.php';
require_once '../src/modele/Evenement.php';
require_once '../src/repository/EvenementRepository.php';

$evenementRepository = new EvenementRepository();
$idEvenement = (int) ($_GET['id'] ?? 0);
$evenement = $evenementRepository->getEvenement($idEvenement);

if ($evenement != null) {
    $organisateurs = $evenementRepository->getOrganisateurs($idEvenement);
    $estOrganisateur = estConnecte() && $evenementRepository->estOrganisateur($idEvenement, $_SESSION['id_utilisateur']);
    $statutInscription = estConnecte() ? $evenementRepository->getStatutInscription($idEvenement, $_SESSION['id_utilisateur']) : null;
    $coOrganisateursPossibles = $estOrganisateur ? $evenementRepository->getCoOrganisateursPossibles($idEvenement) : [];
}

$messagesOk = [
    'cree' => 'L\'événement a bien été créé !',
    'modifie' => 'L\'événement a bien été modifié.',
    'inscrit' => 'Vous êtes inscrit à cet événement.',
    'desinscrit' => 'Vous êtes désinscrit de cet événement.',
    'organisateur' => 'Le co-organisateur a bien été ajouté.',
];
$messagesErreur = [
    'complet' => 'Désolé, cet événement est complet.',
    'deja_inscrit' => 'Vous êtes déjà inscrit à cet événement.',
    'refuse' => 'Votre inscription à cet événement a été refusée par les organisateurs.',
    'passe' => 'Cet événement est terminé.',
    'organisateur' => 'Impossible d\'ajouter cette personne comme co-organisateur.',
];
$libellesRole = ['medecin' => 'Médecin', 'partenaire' => 'Partenaire'];

include 'header.php';
?>

<?php if ($evenement == null) { ?>
    <div class="alert alert-danger">Cet événement n'existe pas.</div>
    <a href="listeEvenements.php" class="btn btn-outline-secondary">Retour aux événements</a>
<?php } else { ?>

    <?php if (isset($_GET['ok'], $messagesOk[$_GET['ok']])) { ?>
        <div class="alert alert-success"><?= $messagesOk[$_GET['ok']] ?></div>
    <?php } ?>
    <?php if (isset($_GET['erreur'], $messagesErreur[$_GET['erreur']])) { ?>
        <div class="alert alert-danger"><?= $messagesErreur[$_GET['erreur']] ?></div>
    <?php } ?>

    <a href="listeEvenements.php" class="text-decoration-none">← Tous les événements</a>

    <div class="row mt-3">
        <!-- Colonne de gauche : infos de l'événement -->
        <div class="col-md-8">
            <span class="badge bg-info text-dark"><?= htmlspecialchars($evenement->getTypeEvenement()) ?></span>
            <?php if ($evenement->estPasse()) { ?>
                <span class="badge bg-secondary">Terminé</span>
            <?php } ?>
            <h1 class="mt-2"><?= htmlspecialchars($evenement->getTitreEvenement()) ?></h1>
            <p class="mb-1">📅 <?= date('d/m/Y', strtotime($evenement->getDateEvenement())) ?></p>
            <p>📍 <?= htmlspecialchars($evenement->getAdresseEvenement()) ?></p>
            <p><?= nl2br(htmlspecialchars($evenement->getDescriptionEvenement())) ?></p>
            <?php if ($evenement->getElementRequisEvenement() != null) { ?>
                <p><strong>Éléments requis :</strong> <?= htmlspecialchars($evenement->getElementRequisEvenement()) ?></p>
            <?php } ?>

            <h5 class="mt-4">Organisateurs</h5>
            <ul>
                <?php foreach ($organisateurs as $organisateur) { ?>
                    <li><?= htmlspecialchars($organisateur['prenom'] . ' ' . $organisateur['nom']) ?>
                        <small class="text-muted">(<?= $libellesRole[$organisateur['role']] ?? $organisateur['role'] ?>)</small></li>
                <?php } ?>
            </ul>
        </div>

        <!-- Colonne de droite : places et actions -->
        <div class="col-md-4">
            <div class="card">
                <div class="card-body">
                    <p class="fs-5 mb-3">Places restantes : <strong><?= $evenement->getPlacesRestantes() ?></strong> / <?= $evenement->getNbPlaceEvenement() ?></p>

                    <?php if ($estOrganisateur) { ?>
                        <a href="modifierEvenement.php?id=<?= $idEvenement ?>" class="btn btn-primary w-100 mb-2">Modifier l'événement</a>
                        <a href="inscritsEvenement.php?id=<?= $idEvenement ?>" class="btn btn-outline-primary w-100">Voir les inscrits (<?= $evenement->getNbInscritsEvenement() ?>)</a>

                    <?php } elseif (!estConnecte()) { ?>
                        <a href="connexion.php" class="btn btn-primary w-100">Se connecter pour s'inscrire</a>

                    <?php } elseif ($_SESSION['role'] == 'gestionnaire') { ?>
                        <p class="text-muted mb-0">Les gestionnaires ne peuvent pas s'inscrire aux événements.</p>

                    <?php } elseif ($evenement->estPasse()) { ?>
                        <p class="text-muted mb-0">Cet événement est terminé.</p>

                    <?php } elseif ($statutInscription == 'accepte') { ?>
                        <p class="text-success fw-bold">✔ Vous êtes inscrit</p>
                        <form action="../src/traitement/inscriptionEvenement.php" method="post">
                            <input type="hidden" name="id_evenement" value="<?= $idEvenement ?>">
                            <input type="hidden" name="action" value="desinscrire">
                            <button type="submit" class="btn btn-outline-danger w-100">Se désinscrire</button>
                        </form>

                    <?php } elseif ($statutInscription == 'refuse') { ?>
                        <p class="text-danger mb-0">Votre inscription a été refusée par les organisateurs.</p>

                    <?php } elseif ($evenement->getPlacesRestantes() == 0) { ?>
                        <button class="btn btn-secondary w-100" disabled>Complet</button>

                    <?php } else { ?>
                        <form action="../src/traitement/inscriptionEvenement.php" method="post">
                            <input type="hidden" name="id_evenement" value="<?= $idEvenement ?>">
                            <input type="hidden" name="action" value="inscrire">
                            <button type="submit" class="btn btn-success w-100">S'inscrire</button>
                        </form>
                    <?php } ?>
                </div>
            </div>

            <?php if ($estOrganisateur) { ?>
                <!-- Ajout d'un co-organisateur (médecin ou partenaire) -->
                <div class="card mt-3">
                    <div class="card-body">
                        <h6>Ajouter un co-organisateur</h6>
                        <?php if (count($coOrganisateursPossibles) == 0) { ?>
                            <p class="text-muted small mb-0">Aucun médecin ou partenaire disponible.</p>
                        <?php } else { ?>
                            <form action="../src/traitement/ajouterOrganisateur.php" method="post">
                                <input type="hidden" name="id_evenement" value="<?= $idEvenement ?>">
                                <select name="id_utilisateur" class="form-select mb-2" required>
                                    <?php foreach ($coOrganisateursPossibles as $personne) { ?>
                                        <option value="<?= $personne['id_utilisateur'] ?>">
                                            <?= htmlspecialchars($personne['prenom'] . ' ' . $personne['nom']) ?> (<?= $libellesRole[$personne['role']] ?>)
                                        </option>
                                    <?php } ?>
                                </select>
                                <button type="submit" class="btn btn-outline-primary w-100">Ajouter</button>
                            </form>
                        <?php } ?>
                    </div>
                </div>
            <?php } ?>
        </div>
    </div>
<?php } ?>

<?php include 'footer.php'; ?>
