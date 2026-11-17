<?php
require_once '../bdd/Bdd.php';
require_once '../modele/Candidature.php';
require_once '../repository/CandidatureRepository.php';
require_once '../modele/Offre.php';
require_once '../repository/OffreRepository.php';
require_once '../modele/Utilisateurs.php';                 // AJOUT : pour trouver l'auteur de l'offre
require_once '../repository/UtilisateursRepository.php';   // AJOUT : pour trouver l'auteur de l'offre
require_once '../lib/envoyerMail.php';                     // AJOUT : notre outil pour envoyer le mail

session_start();
// que les etudiant et medecin peuvent postuler
if (!isset($_SESSION['id_utilisateur'], $_SESSION['role'])
    || !in_array($_SESSION['role'], ['etudiant', 'medecin'])) {
    header('Location: ../../public/listeOffre.php');
    exit();
}

if (isset($_POST['motivation'], $_POST['ref_offre'])) {

    $offreRepository = new OffreRepository();
    $offre = $offreRepository->getOffre($_POST['ref_offre']);
// l offre doit etre ouverte
    if ($offre == null || $offre->getEtatOffre() == 'cloture') {
        header('Location: ../../public/ficheOffre.php?id=' . $_POST['ref_offre'] . '&erreur=cloture');
        exit();
    }
// on postule que une fois
    $candidatureRepository = new CandidatureRepository();
    if ($candidatureRepository->candidatureExiste($_SESSION['id_utilisateur'], $_POST['ref_offre'])) {
        header('Location: ../../public/ficheOffre.php?id=' . $_POST['ref_offre'] . '&erreur=deja');
        exit();
    }

    $candidature = new Candidature(null, $_POST['motivation'], date('Y-m-d H:i:s'), 'en_attente', $_SESSION['id_utilisateur'], $_POST['ref_offre']);
    $candidatureRepository->ajouterCandidature($candidature);

    // ===== AJOUT : prévenir l'entreprise par mail =====

    // 1) On cherche l'auteur de l'offre (l'entreprise)
    $utilisateursRepository = new UtilisateursRepository();
    $auteur = $utilisateursRepository->getUtilisateurParId($offre->getRefUtilisateur());

    // 2) Si on l'a trouvé, on lui envoie un mail
    if ($auteur != null) {

        $sujet = "Nouvelle candidature pour votre offre";

        $message = "Bonjour,\n\n"
            . "Vous avez reçu une nouvelle candidature pour votre offre : "
            . $offre->getTitreOffre() . ".\n\n"
            . "Connectez-vous sur HSP pour la consulter.";

        envoyerMail($auteur->getEmail(), $sujet, $message);
    }

    header('Location: ../../public/ficheOffre.php?id=' . $_POST['ref_offre'] . '&ok=1');
    exit();
}

header('Location: ../../public/listeOffre.php');