<?php
require_once '../bdd/Bdd.php';
require_once '../modele/Post.php';
require_once '../repository/PostRepository.php';
require_once '../auth.php';
if (isset($_POST['contenu'], $_POST['titre'], $_POST['ref_canal'])) {
    $post = new Post(null, $_POST['contenu'], date('Y-m-d H:i:s'), $_POST['titre'], $_POST['ref_canal'], $_SESSION['id_utilisateur']);
    $postRepository = new PostRepository();
    $postRepository->ajouterPost($post);
}
$postRepository = new PostRepository();
$idPost = $postRepository->ajouterPost($post);

header('Location: ../../public/posts.php?id=' . $idPost . '&ok=cree');
exit;
