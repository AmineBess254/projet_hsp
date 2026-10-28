<?php
require_once '../src/auth.php';   // démarre la session
require_once '../src/bdd/Bdd.php';
require_once '../src/modele/Entreprise.php';
require_once '../src/modele/Etablissement.php';
require_once '../src/repository/EntrepriseRepository.php';
require_once '../src/repository/EtablissementRepository.php';

// Sécurité : on ne peut arriver ici que juste après l'inscription
if (!isset($_SESSION['id_inscription'])) {
    header('Location: inscription.php');
    exit;
}

$role = $_SESSION['role_inscription'];

// Le partenaire se rattache à une entreprise, l'étudiant et le médecin à un établissement
$liste = array();
if ($role == 'partenaire') {
    $titre = 'une entreprise';
    $entrepriseRepository = new EntrepriseRepository();
    foreach ($entrepriseRepository->getAllEntreprise() as $entreprise) {
        $liste[$entreprise->getIdEntreprise()] = $entreprise->getNomEntreprise();
    }
} else {
    $titre = 'un établissement';
    $etablissementRepository = new EtablissementRepository();
    foreach ($etablissementRepository->getAllEtablissement() as $etablissement) {
        $liste[$etablissement->getIdEtablissement()] = $etablissement->getNomEtablissement();
    }
}

include 'header.php';
?>

    <div class="row justify-content-center">
        <div class="col-md-6">
            <h1 class="h3 mb-2">Dernière étape : <?= $titre ?></h1>
            <p class="text-muted mb-4">Choisissez <?= $titre ?> dans la liste, ou créez sa fiche si elle n'existe pas encore.</p>

            <?php if (isset($_GET['erreur'])) { ?>
                <div class="alert alert-danger">Merci de remplir tous les champs.</div>
            <?php } ?>

            <!-- Choix 1 : dans la liste (seulement s'il y en a déjà) -->
            <?php if (count($liste) > 0) { ?>
                <div class="card mb-4">
                    <div class="card-body">
                        <h2 class="h5">Choisir dans la liste</h2>
                        <form action="../src/traitement/rattachement.php" method="post">
                            <input type="hidden" name="choix" value="existant">
                            <select name="id" class="form-select mb-3" required>
                                <?php foreach ($liste as $id => $nom) { ?>
                                    <option value="<?= $id ?>"><?= htmlspecialchars($nom) ?></option>
                                <?php } ?>
                            </select>
                            <button type="submit" class="btn btn-primary w-100">Me rattacher</button>
                        </form>
                    </div>
                </div>
            <?php } ?>

            <!-- Choix 2 : créer une nouvelle fiche -->
            <div class="card mb-4">
                <div class="card-body">
                    <h2 class="h5">Créer une nouvelle fiche</h2>
                    <form action="../src/traitement/rattachement.php" method="post">
                        <input type="hidden" name="choix" value="nouveau">
                        <div class="mb-3">
                            <label class="form-label">Nom</label>
                            <input type="text" name="nom" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Adresse</label>
                            <input type="text" name="adresse" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Site web</label>
                            <input type="url" name="site_web" class="form-control" placeholder="https://www.exemple.fr" required>
                        </div>
                        <button type="submit" class="btn btn-outline-primary w-100">Créer et me rattacher</button>
                    </form>
                </div>
            </div>

            <!-- Choix 3 : seul le médecin peut passer (il n'est rattaché que s'il est professeur) -->
            <?php if ($role == 'medecin') { ?>
                <form action="../src/traitement/rattachement.php" method="post" class="text-center">
                    <input type="hidden" name="choix" value="aucun">
                    <button type="submit" class="btn btn-link">Je ne suis pas professeur, passer cette étape</button>
                </form>
            <?php } ?>
        </div>
    </div>

<?php include 'footer.php'; ?>