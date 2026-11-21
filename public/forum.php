<?php
require_once'../src/bdd/Bdd.php';
require_once'../src/modele/Canal.php';
require_once '../src/modele/Post.php';
require_once '../src/modele/DroitsForum.php';
require_once '../src/repository/CanalRepository.php';
require_once '../src/repository/PostRepository.php';
require_once '../src/auth.php';
require_once '../src/modele/Utilisateurs.php';
require_once '../src/repository/UtilisateursRepository.php';
exigerConnexion();

$droits = new DroitsForum($_SESSION['role']);
$canaux = array();
foreach ((new CanalRepository())->getAllCanal() as $canal) {
    if ($droits ->peutLire($canal)) {
        $canaux[] = $canal;
    }
}
$idDemande = (int) ($_GET['canal'] ?? 0);
$canalAffiche = null;
foreach ($canaux as $canal) {
    if($canal->getIdcanal() == $idDemande) {
        $canalAffiche = $canal;
    }
}
if($canalAffiche == null && count($canaux) > 0) {
    $canalAffiche = $canaux[0];
}
$posts = array();
$peutPoster =false;
if($canalAffiche != null) {
    $posts = (new PostRepository())->getPostParCanal($canalAffiche->getIdcanal());
    $peutPoster = $droits->peutPoster($canalAffiche);
    }
$posts = array();
$peutPoster = false;
$motCle = trim($_GET['keywords'] ?? '');
if($canalAffiche != null) {
    if($motCle != "") {
        $posts = (new PostRepository())->rechercherPosts($canalAffiche->getIdcanal(), $motCle);
    }else{
        $posts = (new PostRepository())->getPostParCanal($canalAffiche->getIdcanal());
    }
    $peutPoster = $droits->peutPoster($canalAffiche);
}
$utilisateursRepository = new UtilisateursRepository();
include 'header.php'
?>

<div class="d-flex justify-content-between align-items-center mb-3">
    <h1>Forum</h1>
    <a href="mesPosts.php" class="btn btn-outline-primary">Mes posts</a>
</div>

<?php if (isset($_GET['erreur']) && $_GET['erreur'] == 'champs') { ?>
    <div class="alert alert-danger">Le titre et le contenu sont obligatoires.</div>
<?php } ?>

<?php if ($canalAffiche == null) { ?>
    <div class="alert alert-warning">Aucun canal disponible pour votre profil.</div>
<?php } else { ?>

    <ul class="nav nav-pills mb-4">
        <?php foreach ($canaux as $canal) { ?>
            <li class="nav-item">
                <a class="nav-link <?= $canal->getIdCanal() == $canalAffiche->getIdCanal() ? 'active' : '' ?>"
                   href="forum.php?canal=<?= $canal->getIdCanal() ?>"><?= htmlspecialchars($canal->getNomCanal()) ?></a>
            </li>
        <?php } ?>
    </ul>
    <form action="forum.php" method="get" class="mb-4">
        <input type="hidden" name="canal" value="<?= $canalAffiche->getIdCanal() ?>">
        <div class="input-group">
            <input type="text" name="keywords" class="form-control" placeholder="Rechercher un mot-clé dans ce canal"
                   value="<?= htmlspecialchars($motCle) ?>">
            <button type="submit" class="btn btn-outline-primary">Rechercher</button>
        </div>
    </form>
    <?php if ($motCle != '') { ?>
        <p class="text-muted">
            <?= count($posts) ?> résultat(s) pour « <?= htmlspecialchars($motCle) ?> »
            <a href="forum.php?canal=<?= $canalAffiche->getIdCanal() ?>">Effacer la recherche</a>
        </p>
    <?php } ?>


    <?php if ($peutPoster) { ?>
        <div class="card mb-4">
            <div class="card-body">
                <h2 class="h5">Nouveau post</h2>
                <form action="../src/traitement/newPost.php" method="post">
                    <input type="hidden" name="ref_canal" value="<?= $canalAffiche->getIdCanal() ?>">
                    <div class="mb-3">
                        <label class="form-label">Titre</label>
                        <input type="text" name="titre" class="form-control" maxlength="150" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Message</label>
                        <textarea name="contenu" class="form-control" rows="4" required></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Publier</button>
                </form>
            </div>
        </div>
    <?php } else { ?>
        <p class="text-muted">Vous pouvez lire ce canal, mais seuls certains profils peuvent y créer un post.</p>
    <?php } ?>

    <?php if (count($posts) == 0) { ?>
        <p class="text-muted">Aucun post pour le moment.</p>
    <?php } else { ?>
        <div class="table-responsive">
            <table class="table align-middle">
                <thead>
                <tr><th>Titre</th><th>Contenu</th><th>Auteur</th><th>Date</th><th>Réponses</th><th></th></tr>
                </thead>
                <tbody>
                <?php foreach ($posts as $post) { ?>
                    <tr>
                        <td><?= htmlspecialchars($post->getTitre()) ?></td>
                        <td><?= htmlspecialchars(mb_strimwidth($post->getContenu(), 0, 100, '...')) ?></td>
                        <td><?= htmlspecialchars($utilisateursRepository->getNomComplet($post->getRefUtilisateur())) ?></td>
                        <td><?= date('d/m/Y à H:i', strtotime($post->getDateHeure())) ?></td>
                        <td><a href="posts.php?id=<?= $post->getIdPost() ?>" class="btn btn-sm btn-outline-primary">Ouvrir</a></td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    <?php } ?>

<?php } ?>

<?php include 'footer.php'; ?>


