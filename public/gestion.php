<?php
require_once '../src/auth.php';
exigerRole(['gestionnaire']);   // seuls les gestionnaires peuvent voir cette page

require_once '../src/bdd/Bdd.php';
require_once '../src/modele/Utilisateurs.php';
require_once '../src/repository/UtilisateursRepository.php';

$repository = new UtilisateursRepository();
$comptesEnAttente = $repository->getUtilisateursEnAttente();
$comptesTraites = $repository->getComptesTraites($_SESSION['id_utilisateur']);

include 'header.php';
?>

    <h1 class="h3 mb-4">Espace gestionnaire</h1>

    <!-- Messages renvoyés par les traitements -->
<?php if (isset($_GET['ok']) && $_GET['ok'] == 'valide') { ?>
    <div class="alert alert-success">Le compte a été validé.</div>
<?php } ?>
<?php if (isset($_GET['ok']) && $_GET['ok'] == 'refuse') { ?>
    <div class="alert alert-warning">Le compte a été refusé.</div>
<?php } ?>
<?php if (isset($_GET['ok']) && $_GET['ok'] == 'gestionnaire') { ?>
    <div class="alert alert-success">Le nouveau gestionnaire a été créé.</div>
<?php } ?>
<?php if (isset($_GET['ok']) && $_GET['ok'] == 'desactiver') { ?>
    <div class="alert alert-warning">Le compte a été désactivé.</div>
<?php } ?>
<?php if (isset($_GET['ok']) && $_GET['ok'] == 'reactiver') { ?>
    <div class="alert alert-success">Le compte a été réactivé.</div>
<?php } ?>
<?php if (isset($_GET['ok']) && $_GET['ok'] == 'supprimer') { ?>
    <div class="alert alert-danger">Le compte a été supprimé.</div>
<?php } ?>
<?php if (isset($_GET['erreur']) && $_GET['erreur'] == 'email') { ?>
    <div class="alert alert-danger">Cet email est déjà utilisé.</div>
<?php } ?>

    <!-- Partie 1 : les comptes à valider -->
    <h2 class="h5">Comptes en attente de validation</h2>

<?php if (count($comptesEnAttente) == 0) { ?>
    <p class="text-muted">Aucun compte en attente.</p>
<?php } else { ?>
    <table class="table table-bordered">
        <tr>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Email</th>
            <th>Rôle</th>
            <th>Action</th>
        </tr>
        <?php foreach ($comptesEnAttente as $compte) { ?>
            <tr>
                <td><?= htmlspecialchars($compte->getNom()) ?></td>
                <td><?= htmlspecialchars($compte->getPrenom()) ?></td>
                <td><?= htmlspecialchars($compte->getEmail()) ?></td>
                <td><?= $compte->getRole() ?></td>
                <td>
                    <form action="../src/traitement/validerCompte.php" method="post" class="d-inline">
                        <input type="hidden" name="id" value="<?= $compte->getIdUtilisateurs() ?>">
                        <button type="submit" name="statut" value="valide" class="btn btn-success btn-sm">Valider</button>
                        <button type="submit" name="statut" value="refuse" class="btn btn-danger btn-sm">Refuser</button>
                    </form>
                </td>
            </tr>
        <?php } ?>
    </table>
<?php } ?>

    <!-- Partie 2 : tous les autres comptes (désactiver, réactiver, supprimer) -->
    <h2 class="h5 mt-5">Tous les comptes</h2>

<?php if (count($comptesTraites) == 0) { ?>
    <p class="text-muted">Aucun compte.</p>
<?php } else { ?>
    <table class="table table-bordered">
        <tr>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Email</th>
            <th>Rôle</th>
            <th>Statut</th>
            <th>Action</th>
        </tr>
        <?php foreach ($comptesTraites as $compte) { ?>
            <tr>
                <td><?= htmlspecialchars($compte->getNom()) ?></td>
                <td><?= htmlspecialchars($compte->getPrenom()) ?></td>
                <td><?= htmlspecialchars($compte->getEmail()) ?></td>
                <td><?= $compte->getRole() ?></td>
                <td>
                    <?php if ($compte->getStatutValidation() == 'valide') { ?>
                        <span class="badge bg-success">Actif</span>
                    <?php } elseif ($compte->getStatutValidation() == 'desactive') { ?>
                        <span class="badge bg-secondary">Désactivé</span>
                    <?php } else { ?>
                        <span class="badge bg-danger">Refusé</span>
                    <?php } ?>
                </td>
                <td>
                    <form action="../src/traitement/gererCompte.php" method="post" class="d-inline">
                        <input type="hidden" name="id" value="<?= $compte->getIdUtilisateurs() ?>">

                        <?php if ($compte->getStatutValidation() == 'valide') { ?>
                            <button type="submit" name="action" value="desactiver" class="btn btn-warning btn-sm">Désactiver</button>
                        <?php } else { ?>
                            <button type="submit" name="action" value="reactiver" class="btn btn-success btn-sm">Réactiver</button>
                        <?php } ?>

                        <!-- confirm() demande "Êtes-vous sûr ?" avant de supprimer -->
                        <button type="submit" name="action" value="supprimer" class="btn btn-danger btn-sm"
                                onclick="return confirm('Supprimer définitivement ce compte ?');">Supprimer</button>
                    </form>
                </td>
            </tr>
        <?php } ?>
    </table>
<?php } ?>

    <!-- Partie 3 : créer un gestionnaire -->
    <h2 class="h5 mt-5">Créer un nouveau gestionnaire</h2>
    <form action="../src/traitement/newGestionnaire.php" method="post" class="col-md-6">
        <div class="mb-3">
            <label class="form-label">Nom</label>
            <input type="text" name="nom" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Prénom</label>
            <input type="text" name="prenom" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Mot de passe</label>
            <input type="password" name="mdp" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Créer le gestionnaire</button>
    </form>

<?php include 'footer.php'; ?>