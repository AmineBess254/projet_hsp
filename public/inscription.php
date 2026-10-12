<?php include 'header.php'; ?>

    <div class="row justify-content-center">
        <div class="col-md-6">
            <h1 class="mb-4">Inscription</h1>

            <?php if (isset($_GET['ok'])) { ?>
                <div class="alert alert-success">Inscription réussie ! Votre compte doit être validé par un gestionnaire.</div>
            <?php } ?>

            <form action="../src/traitement/newUtilisateurs.php" method="post">
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
                <div class="mb-3">
                    <label class="form-label">Je suis</label>
                    <select name="role" class="form-select">
                        <option value="etudiant">Étudiant</option>
                        <option value="medecin">Médecin</option>
                        <option value="partenaire">Partenaire (entreprise)</option>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary w-100">S'inscrire</button>
            </form>
        </div>
    </div>

