<?php
session_start();                  // on récupère la session en cours
session_unset();                  // on efface ce qu'elle contient (id, rôle, prénom)
session_destroy();                // on la supprime complètement
header("Location: index.php");    // retour à l'accueil
exit;