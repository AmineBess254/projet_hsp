<?php require_once __DIR__ . '/../src/auth.php'; ?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>HSP - Hôpital Sud Paris</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="/projet_hsp/public/css/style.css" rel="stylesheet">
</head>
<body class="d-flex flex-column min-vh-100"><nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="/projet_hsp/public/index.php">HSP</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="menu">
            <ul class="navbar-nav me-auto">
                <li class="nav-item"><a class="nav-link" href="/projet_hsp/public/index.php">Accueil</a></li>
                <li class="nav-item"><a class="nav-link" href="/projet_hsp/public/offre/listeOffre.php">Offres</a></li>
                <li class="nav-item"><a class="nav-link" href="/projet_hsp/public/evenement/listeEvenements.php">Événements</a></li>
                <li class="nav-item"><a class="nav-link" href="/projet_hsp/public/forum/forum.php">Forum</a></li>
                <li class="nav-item"><a class="nav-link" href="/projet_hsp/public/annuaire/annuaire.php">Contact</a></li>

                <?php if (estConnecte() && $_SESSION['role'] == 'gestionnaire') { ?>
                    <li class="nav-item"><a class="nav-link" href="/projet_hsp/public/gestion/gestion.php">Gestion</a></li>
                    <li class="nav-item"><a class="nav-link" href="/projet_hsp/public/gestion/gestionActualites.php">Actualités</a></li>
                <?php } ?>
            </ul>

            <?php if (estConnecte()) { ?>
                <span class="navbar-text text-white me-3">Bonjour <?= htmlspecialchars($_SESSION['prenom']) ?></span>
                <a class="btn btn-light me-2" href="/projet_hsp/public/compte/profil.php">Mon profil</a>
                <a class="btn btn-outline-light" href="/projet_hsp/public/compte/deconnexion.php">Déconnexion</a>
            <?php } else { ?>
                <a class="btn btn-light me-2" href="/projet_hsp/public/compte/connexion.php">Connexion</a>
                <a class="btn btn-outline-light" href="/projet_hsp/public/compte/inscription.php">Inscription</a>
            <?php } ?>
        </div>
    </div>
</nav>
<main class="container my-4">