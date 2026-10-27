<?php
// Démarre la session si ce n'est pas déjà fait
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Vrai si quelqu'un est connecté
function estConnecte()
{
    return isset($_SESSION['id_utilisateur']);
}

// Bloque la page si personne n'est connecté
function exigerConnexion()
{
    if (!estConnecte()) {
        header('Location: connexion.php');
        exit;
    }
}

// Bloque la page si l'utilisateur n'a pas un des rôles demandés
// Exemple : exigerRole(['medecin', 'partenaire']);
function exigerRole($roles)
{
    exigerConnexion();
    if (!in_array($_SESSION['role'], $roles)) {
        header('Location: index.php');
        exit;
    }
}
