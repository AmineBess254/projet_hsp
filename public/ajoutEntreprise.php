<?php include 'header.php'; ?>

    <div class="row justify-content-center">
        <div class="col-md-6">
            <h1 class="mb-4">Créer une fiche entreprise</h1>

            <?php if (isset($_GET['ok'])) { ?>
                <div class="alert alert-success">
                    La fiche entreprise a bien été créée !
                </div>
            <?php } ?>

            <?php if (isset($_GET['erreur']) && $_GET['erreur'] === 'doublon') { ?>
                <div class="alert alert-danger">
                    Une entreprise avec ce site web existe déjà.
                </div>
            <?php } ?>

            <form action="../src/traitement/newEntreprise.php" method="post">
                <div class="mb-3">
                    <label class="form-label">Nom de l'entreprise</label>
                    <input type="text" name="nom" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Adresse</label>
                    <input type="text" name="adresse" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Site web</label>
                    <input type="url" name="site_web" class="form-control"
                           placeholder="https://www.exemple.fr" required>
                </div>

                <button type="submit" class="btn btn-primary w-100">
                    Créer la fiche
                </button>
            </form>
        </div>
    </div>

<?php include 'footer.php'; ?>