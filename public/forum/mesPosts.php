<?php
require_once '../../src/auth.php';
require_once '../../src/bdd/Bdd.php';
require_once '../../src/modele/Canal.php';
require_once '../../src/modele/Post.php';
require_once '../../src/repository/CanalRepository.php';
require_once '../../src/repository/PostRepository.php';
exigerConnexion();

$idUtilisateur = $_SESSION['id_utilisateur'];

$postRepository = new PostRepository();

$mesPosts = $postRepository->getPostParUtilisateur($idUtilisateur);

$postsRepondus = $postRepository->getPostParReponseUtilisateur($idUtilisateur);


$nomsCanaux = array();
foreach ((new CanalRepository())->getAllCanal() as $canal) {
    $nomsCanaux[$canal->getIdCanal()] = $canal->getNomCanal();
}

include '../header.php';
?>

    <a href="/projet_hsp/public/forum/forum.php" class="text-decoration-none">← Retour au forum</a>
    <h1 class="mt-2 mb-4">Mes posts</h1>

    <h2 class="h5">Posts que j'ai écrits</h2>
<?php if (count($mesPosts) == 0) { ?>
    <p class="text-muted">Vous n'avez écrit aucun post.</p>
<?php } else { ?>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
            <tr><th>Titre</th><th>Canal</th><th>Date</th><th></th></tr>
            </thead>
            <tbody>
            <?php foreach ($mesPosts as $post) { ?>
                <tr>
                    <td><?= htmlspecialchars($post->getTitre()) ?></td>
                    <td><?= htmlspecialchars($nomsCanaux[$post->getRefCanal()] ?? '') ?></td>
                    <td><?= date('d/m/Y à H:i', strtotime($post->getDateHeure())) ?></td>
                    <td><a href="/projet_hsp/public/forum/posts.php?id=<?= $post->getIdPost() ?>" class="btn btn-sm btn-outline-primary">Ouvrir</a></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
<?php } ?>

    <h2 class="h5 mt-5">Posts auxquels j'ai répondu</h2>
<?php if (count($postsRepondus) == 0) { ?>
    <p class="text-muted">Vous n'avez répondu à aucun post.</p>
<?php } else { ?>
    <div class="table-responsive">
        <table class="table align-middle">
            <thead>
            <tr><th>Titre</th><th>Canal</th><th>Date</th><th></th></tr>
            </thead>
            <tbody>
            <?php foreach ($postsRepondus as $post) { ?>
                <tr>
                    <td><?= htmlspecialchars($post->getTitre()) ?></td>
                    <td><?= htmlspecialchars($nomsCanaux[$post->getRefCanal()] ?? '') ?></td>
                    <td><?= date('d/m/Y à H:i', strtotime($post->getDateHeure())) ?></td>
                    <td><a href="/projet_hsp/public/forum/posts.php?id=<?= $post->getIdPost() ?>" class="btn btn-sm btn-outline-primary">Ouvrir</a></td>
                </tr>
            <?php } ?>
            </tbody>
        </table>
    </div>
<?php } ?>

<?php include '../footer.php'; ?>