<?php
require_once '../bdd/Bdd.php';
require_once '../modele/Candidature.php';
require_once '../repository/CandidatureRepository.php';
require_once '../modele/Offre.php';
require_once '../repository/OffreRepository.php';
require_once '../modele/Utilisateurs.php';                 // pour trouver le candidat
require_once '../repository/UtilisateursRepository.php';   // pour trouver le candidat
require_once '../lib/envoyerMail.php';                     // outil mail

session_start();

if (isset($_GET['id'])) {

    // 1. On va chercher la candidature grâce à son numéro (?id=...)
    $candidatureRepository = new CandidatureRepository();
    $candidature = $candidatureRepository->getCandidature($_GET['id']);

    if ($candidature != null) {

        // 2. On va chercher l'offre de cette candidature
        $offreRepository = new OffreRepository();
        $offre = $offreRepository->getOffre($candidature->getRefOffre());

        // 3. SÉCURITÉ : seulement l'entreprise qui a publié l'offre
        if ($offre != null && isset($_SESSION['id_utilisateur']) && $_SESSION['id_utilisateur'] == $offre->getRefUtilisateur()) {
            $candidatureRepository->masquerCandidature($candidature->getIdCandidature());

            // ===== AJOUT : prévenir le candidat par mail =====

            // 1) On cherche le candidat (l'auteur de la candidature)
            $utilisateursRepository = new UtilisateursRepository();
            $candidat = $utilisateursRepository->getUtilisateurParId($candidature->getRefUtilisateur());

            // 2) Si on l'a trouvé, on lui envoie un mail
            if ($candidat != null) {

                $sujet = "Réponse à votre candidature";

                $message = "Bonjour,\n\n"
                    . "Votre candidature pour l'offre : " . $offre->getTitreOffre()
                    . " n'a pas été retenue.\n\n"
                    . "Merci pour votre intérêt, et bonne continuation sur HSP.";

                envoyerMail($candidat->getEmail(), $sujet, $message);
            }
        }

        // 4. Retour à la page des candidatures de cette offre
        header('Location: ../../public/candidaturesOffre.php?id=' . $candidature->getRefOffre());
        exit();
    }
}

// Si le numéro est absent ou faux : retour à la liste des offres
header('Location: ../../public/listeOffre.php');