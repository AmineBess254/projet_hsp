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
$utilisateur = new Utilisateurs();
?>
<form action="forum.php" method="get" class="mb-4">
        <input type="hidden" name="canal" value="">
        <div class="input-group">
            <input type="text" name="keywords" class="form-control" placeholder="Rechercher un mot-clé dans ce canal"
                   value="">
            <button type="submit" class="btn btn-outline-primary">Rechercher</button>
        </div>
    </form>