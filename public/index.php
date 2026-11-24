<?php
require_once '../src/auth.php';
require_once '../src/bdd/Bdd.php';
require_once '../src/modele/Evenement.php';
require_once '../src/repository/EvenementRepository.php';
require_once '../src/modele/Actualite.php';
require_once '../src/repository/ActualiteRepository.php';

// Les 3 prochains événements pour la page d'accueil
$prochainsEvenements = (new EvenementRepository())->getProchainsEvenements();
// Les 3 dernières actualités publiées par les gestionnaires
$actualites = (new ActualiteRepository())->getDernieresActualites(3);

include 'header.php';

?>
    <div class="p-5 mb-4 bg-light rounded-3">
        <?php if (estConnecte()) { ?>
            <h1 class="display-5 fw-bold">Bonjour <?= htmlspecialchars($_SESSION['prenom']) ?></h1>
            <p class="fs-5">Content de vous revoir sur la plateforme HSP.</p>
        <?php } else { ?>
            <h1 class="display-5 fw-bold">Bienvenue sur la plateforme HSP</h1>
            <p class="fs-5">La Générale des Hôpitaux (GDH), premier groupe de cliniques et hôpitaux privés en France, relie ses médecins, les étudiants en médecine et ses entreprises partenaires.</p>
            <a href="/projet_hsp/public/compte/inscription.php" class="btn btn-primary btn-lg">Rejoindre la plateforme</a>
        <?php } ?>
    </div>
    <div class="row text-center">
        <div class="col-md-4 mb-3">
            <div class="card h-100"><div class="card-body">
                    <h5 class="card-title">Étudiants</h5>
                    <p class="card-text">Trouvez un stage, une alternance ou un emploi, et participez aux événements.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card h-100"><div class="card-body">
                    <h5 class="card-title">Médecins</h5>
                    <p class="card-text">Restez en contact avec le groupe et découvrez les talents de demain.</p>
                </div>
            </div>
        </div>
        <div class="col-md-4 mb-3">
            <div class="card h-100"><div class="card-body">
                    <h5 class="card-title">Entreprises</h5>
                    <p class="card-text">Publiez vos offres et organisez des événements avec nos hôpitaux.</p>
                </div>
            </div>
        </div>
    </div> <h2 class="mt-4">Actualités</h2>
<?php if (count($actualites) == 0) { ?>
    <p class="text-muted">Aucune actualité pour le moment.</p>
<?php } ?>
<?php foreach ($actualites as $actualite) { ?>
    <div class="card mb-3">
        <div class="card-body">
            <h5 class="card-title"><?= htmlspecialchars($actualite->getTitre()) ?></h5>
            <p class="text-muted small mb-2">Publié le <?= date('d/m/Y', strtotime($actualite->getDatePublication())) ?></p>
            <p class="card-text"><?= nl2br(htmlspecialchars($actualite->getContenu())) ?></p>
        </div>
    </div>
<?php } ?>

    <h2 class="mt-4">Prochains événements</h2>
<?php if (count($prochainsEvenements) == 0) { ?>
    <p class="text-muted">Aucun événement à venir pour le moment.</p>
<?php } else { ?>
    <div class="row">
        <?php foreach ($prochainsEvenements as $evenement) { ?>
            <div class="col-md-4 mb-3">
                <div class="card h-100"><div class="card-body">
                        <span class="badge bg-info text-dark"><?= htmlspecialchars($evenement->getTypeEvenement()) ?></span>
                        <h5 class="card-title mt-2"><?= htmlspecialchars($evenement->getTitreEvenement()) ?></h5>
                        <p class="card-text mb-1">📅 <?= date('d/m/Y', strtotime($evenement->getDateEvenement())) ?></p>
                        <p class="card-text">Places restantes : <?= $evenement->getPlacesRestantes() ?></p>
                        <a href="/projet_hsp/public/evenement/evenement.php?id=<?= $evenement->getIdEvenement() ?>" class="btn btn-outline-primary btn-sm">Voir</a>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
<?php } ?>
    <a href="/projet_hsp/public/evenement/listeEvenements.php" class="btn btn-link px-0">Voir tous les événements →</a>


<?php include 'footer.php'; ?>