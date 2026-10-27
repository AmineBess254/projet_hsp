<?php include 'header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <h1 class="mb-4">Publier une offre</h1>

        <?php if (isset($_GET['ok'])) { ?>
            <div class="alert alert-success">L'offre a bien été publiée !</div>
        <?php } ?>

        <form action="../src/traitement/newOffre.php" method="post">
            <div class="mb-3">
                <label class="form-label">Titre de l'offre</label>
                <input type="text" name="titre" class="form-control" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Type d'offre</label>
                <select name="type_offre" class="form-select">
                    <option value="stage">Stage</option>
                    <option value="alternance">Alternance</option>
                    <option value="cdd">CDD</option>
                    <option value="cdi">CDI</option>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="4" required></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Missions</label>
                <textarea name="missions" class="form-control" rows="4" required></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Salaire (pas obligatoire)</label>
                <input type="number" name="salaire" class="form-control" step="0.01">
            </div>
            <button type="submit" class="btn btn-primary w-100">Publier l'offre</button>
        </form>
    </div>
</div>

<?php include 'footer.php'; ?>
