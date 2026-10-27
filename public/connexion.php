<?php include 'header.php'; ?>
    <div class="row justify-content-center">
        <div class="col-md-5">
            <h1 class="mb-4">Connexion</h1>
            <?php if (isset($_GET['erreur']) && $_GET['erreur'] == 'identifiants') { ?>
                <div class="alert alert-danger">Email ou mot de passe incorrect.</div>
            <?php } ?>
            <?php if (isset($_GET['erreur']) && $_GET['erreur'] == 'attente') { ?>
                <div class="alert alert-warning">Votre compte n'a pas encore été validé par un gestionnaire.</div>
            <?php } ?>
            <form action="../src/traitement/connexion.php" method="post">
                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label class="form-label">Mot de passe</label>
                    <input type="password" name="mdp" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary w-100">Se connecter</button>
            </form>
            <p class="mt-3 text-center">Pas encore de compte ? <a href="inscription.php">S'inscrire</a></p>
        </div>
    </div>
<?php include 'footer.php'; ?>