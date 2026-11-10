<?php
require_once '../src/auth.php';
exigerConnexion();   // il faut être connecté pour voir son profil

require_once '../src/bdd/Bdd.php';
require_once '../src/modele/Utilisateurs.php';
require_once '../src/modele/Hopital.php';
require_once '../src/modele/Specialite.php';
require_once '../src/repository/UtilisateursRepository.php';
require_once '../src/repository/HopitalRepository.php';
require_once '../src/repository/SpecialiteRepository.php';

// 1. On récupère l'utilisateur connecté et les infos de son rôle
$repository = new UtilisateursRepository();
$utilisateur = $repository->getUtilisateur($_SESSION['id_utilisateur']);
$role = $utilisateur->getRole();
$profil = $repository->getProfilRole($utilisateur->getIdUtilisateurs(), $role);

// 2. Pour le médecin, on a besoin des listes d'hôpitaux et de spécialités
if ($role == 'medecin') {
    $hopitaux = (new HopitalRepository())->getAllHopital();
    $specialites = (new SpecialiteRepository())->getAllSpecialite();
}

include 'header.php';
?>

    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h1 class="h3 mb-4 text-center">Mon profil</h1>

                    <?php if (isset($_GET['ok'])) { ?>
                        <div class="alert alert-success">Votre profil a bien été modifié.</div>
                    <?php } ?>
                    <?php if (isset($_GET['erreur']) && $_GET['erreur'] == 'email') { ?>
                        <div class="alert alert-danger">Cet email est déjà utilisé par un autre compte.</div>
                    <?php } ?>
                    <?php if (isset($_GET['erreur']) && $_GET['erreur'] == 'champs') { ?>
                        <div class="alert alert-danger">Merci de remplir tous les champs.</div>
                    <?php } ?>
                    <?php if (isset($_GET['erreur']) && $_GET['erreur'] == 'mdp') { ?>
                        <div class="alert alert-danger">Mot de passe actuel incorrect.</div>
                    <?php } ?>
                    <?php if (isset($_GET['erreur']) && $_GET['erreur'] == 'mdpcourt') { ?>
                        <div class="alert alert-danger">Le nouveau mot de passe doit faire au moins 8 caractères.</div>
                    <?php } ?>

                    <form action="../src/traitement/modifierProfil.php" method="post">

                        <!-- Infos communes, pré-remplies avec les valeurs actuelles -->
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nom</label>
                                <input type="text" name="nom" class="form-control" value="<?= htmlspecialchars($utilisateur->getNom()) ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Prénom</label>
                                <input type="text" name="prenom" class="form-control" value="<?= htmlspecialchars($utilisateur->getPrenom()) ?>" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" value="<?= htmlspecialchars($utilisateur->getEmail()) ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Rôle</label>
                            <input type="text" class="form-control" value="<?= $role ?>" disabled>
                        </div>

                        <!-- Infos de l'étudiant -->
                        <?php if ($role == 'etudiant') { ?>
                            <div class="mb-3">
                                <label class="form-label">Formation suivie</label>
                                <input type="text" name="formation" class="form-control" value="<?= htmlspecialchars($profil['formation']) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Lien vers votre CV</label>
                                <input type="url" name="cv" class="form-control" value="<?= htmlspecialchars($profil['cv']) ?>" required>
                            </div>
                        <?php } ?>

                        <!-- Infos du médecin : l'option actuelle est déjà sélectionnée -->
                        <?php if ($role == 'medecin') { ?>
                            <div class="mb-3">
                                <label class="form-label">Spécialité</label>
                                <select name="ref_specialite" class="form-select">
                                    <?php foreach ($specialites as $specialite) { ?>
                                        <option value="<?= $specialite->getIdSpecialite() ?>"
                                                <?php if ($specialite->getIdSpecialite() == $profil['ref_specialite']) { echo 'selected'; } ?>>
                                            <?= htmlspecialchars($specialite->getNomSpecialite()) ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Hôpital du groupe</label>
                                <select name="ref_hopital" class="form-select">
                                    <?php foreach ($hopitaux as $hopital) { ?>
                                        <option value="<?= $hopital->getIdHopital() ?>"
                                                <?php if ($hopital->getIdHopital() == $profil['ref_hopital']) { echo 'selected'; } ?>>
                                            <?= htmlspecialchars($hopital->getNomHopital()) ?>
                                        </option>
                                    <?php } ?>
                                </select>
                            </div>
                        <?php } ?>

                        <!-- Infos du partenaire -->
                        <?php if ($role == 'partenaire') { ?>
                            <div class="mb-3">
                                <label class="form-label">Poste dans l'entreprise</label>
                                <input type="text" name="poste" class="form-control" value="<?= htmlspecialchars($profil['poste']) ?>" required>
                            </div>
                        <?php } ?>

                        <!-- Changer de mot de passe (facultatif) -->
                        <hr class="my-4">
                        <h2 class="h6">Changer de mot de passe <span class="text-muted">(facultatif)</span></h2>
                        <div class="mb-3">
                            <label class="form-label">Mot de passe actuel</label>
                            <input type="password" name="mdp_actuel" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nouveau mot de passe</label>
                            <input type="password" name="nouveau_mdp" class="form-control">
                        </div>

                        <button type="submit" class="btn btn-primary w-100 mt-2">Enregistrer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

<?php include 'footer.php'; ?>