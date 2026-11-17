<?php
require_once '../src/auth.php';
require_once '../src/bdd/Bdd.php';
require_once '../src/modele/Canal.php';
require_once '../src/modele/Post.php';
require_once '../src/repository/CanalRepository.php';
require_once '../src/repository/PostRepository.php';
exigerConnexion();
$idUtlisateur = $_SESSION['id_utilisateur'];
$postRepository = new PostRepository();
$mesPosts = $postRepository->getPostParUtilisateur($idUtlisateur);
$postsRepondus = $postRepository->getPostParReponseUtilisateur($idUtlisateur);
$nomsCanaux = array();
foreach ((new CanalRepository())->getAllCanal()as $canal) {
    $nomsCanaux[$canal->getIdCanal()] = $canal->getNomCanal();
}
include 'header.php';
?>
<a href="forum.php" class="text-decoration-none">Retour au forum</a>
<h1 class="mt-02 mb-04">Mes posts</h1>
<h2 class="h5">Posts que j'ai écrits</h2>
<?php if(count($mesPosts) == 0): { ?>
<p class="text-muted">Vous n'avez écrit aucun post.</p>
<?php } else { ?>

}
