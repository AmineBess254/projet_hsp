<?php
require_once '../src/auth.php';
require_once '../src/bdd/Bdd.php';
require_once '../src/modele/Canal.php';
require_once '../src/modele/Post.php';
require_once '../src/modele/Reponse.php';
require_once '../src/modele/DroitsForum.php';
require_once '../src/repository/CanalRepository.php';
require_once '../src/repository/PostRepository.php';
require_once '../src/repository/ReponseRepository.php';
exigerConnexion();
$post = (new PostRepository())->getPost((int) ($_GET['id'] ?? 0));
if($post != null){
    $canal = (new CanalRepository())->getCanal($post->getRefCanal());

}
$droits = (new DroitsForum($_SESSION['role']));

if($post == null || $canal == null || !$droits->peutLire($canal)){
    header("Location: forum.php");
    exit;
}
$reponses = (new ReponseRepository())->getReponseParPost($post->getIdPost());
$peutRepondre = $droits->peutRepondre($canal);
include 'header.php';
?>
<a href="forum.php?canal=<?= $canal->getIdCanal() ?>" class="text-decoration-none">← Retour au canal <?= htmlspecialchars($canal->getNomCanal()) ?></a>

<?php if (isset($_GET['ok']) && $_GET['ok'] == 'cree') { ?>
    <div class="alert alert-success mt-3">Votre post a bien été publié.</div>
<?php } ?>
<?php if (isset($_GET['ok']) && $_GET['ok'] == 'reponse') { ?>
    <div class="alert alert-success mt-3">Votre réponse a bien été publiée.</div>
<?php } ?>
<?php if (isset($_GET['erreur']) && $_GET['erreur'] == 'champs') { ?>
    <div class="alert alert-danger mt-3">La réponse ne peut pas être vide.</div>
<?php } ?>


<div class="card mt-3 mb-4">
    <div class="card-body">
        <span class="badge bg-info text-dark"><?= htmlspecialchars($canal->getNomCanal()) ?></span>
        <h1 class="h3 mt-2"><?= htmlspecialchars($post->getTitre()) ?></h1>
        <p class="text-muted small">
            Par <?= htmlspecialchars($_SESSION['prenom'])?> le <?= date('d/m/Y à H:i', strtotime($post->getDateHeure())) ?>
        </p>
        <p class="mb-0"><?= nl2br(htmlspecialchars($post->getContenu())) ?></p>
    </div>
</div>


<h2 class="h5"><?= count($reponses) ?> réponse(s)</h2>
<?php if (count($reponses) == 0) { ?>
    <p class="text-muted">Aucune réponse pour le moment.</p>
<?php } ?>
<?php foreach ($reponses as $reponse) { ?>
    <div class="card mb-2">
        <div class="card-body">
            <p class="text-muted small mb-1">
                <?= htmlspecialchars($_SESSION['prenom']) ?> le <?= date('d/m/Y à H:i', strtotime($reponse->getDateHeure())) ?>
            </p>
            <p class="mb-0"><?= nl2br(htmlspecialchars($reponse->getContenu())) ?></p>
        </div>
    </div>
<?php } ?>


<?php if ($peutRepondre) { ?>
    <form action="../src/traitement/newReponse.php" method="post" class="mt-4">
        <input type="hidden" name="ref_post" value="<?= $post->getIdPost() ?>">
        <div class="mb-3">
            <label class="form-label">Votre réponse</label>
            <textarea name="contenu" class="form-control" rows="3" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Répondre</button>
    </form>
<?php } else { ?>
    <p class="text-muted mt-4">Vous ne pouvez pas répondre dans ce canal.</p>
<?php } ?>

<?php include 'footer.php'; ?>

