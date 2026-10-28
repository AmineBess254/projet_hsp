<?php include
'header.php';
?>
    <div class="col-md-8">
        <h1 class="mb-4">Créer un événement</h1>
        <?php if (isset($_GET['ok'])) { ?>
            <div class="alert alert-success">L'événement a bien été créé !</div>
        <?php } ?>
        <form action="../src/traitement/newEvenement.php" method="post">
        <div class="mb-3">
            <label class="form-label">Titre</label>
            <input type="text" name="titre" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Type d'événement</label>
                <input type="text" name="type" class="form-control" placeholder="Conférence, salon, atelier..." required>
        </div>
        <div class="mb-3"><label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="4" required></textarea>
        </div>
        <div class="mb-3"><label class="form-label">Lieu (adresse)</label><input type="text" name="adresse" class="form-control" required></div>
            <div class="mb-3"><label class="form-label">Date:</label><input type="date" name="date_evenement" class="form-control" required></div>
        <div class="mb-3"><label class="form-label">Éléments requis (pas obligatoire)</label>
            <input type="text" name="element_requis" class="form-control" placeholder="Blouse, carte étudiante..."></div>
        <div class="mb-3"><label class="form-label">Nombre de places</label><input type="number" name="nb_place" class="form-control" min="1" required></div><button type="submit" class="btn btn-primary w-100">Créer l'événement</button>
        </form>
    </div> <?php include 'footer.php'; ?>