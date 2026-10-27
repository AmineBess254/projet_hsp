<?php
require_once '../src/bdd/Bdd.php';
require_once '../src/modele/Hopital.php';
require_once '../src/repository/HopitalRepository.php';

$hopitalRepository = new HopitalRepository();
$hopitaux = $hopitalRepository->getAllHopital();

include 'header.php';
?>

    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm">
                <div class="card-body p-4">
                    <h1 class="h3 mb-4 text-center">Créer un compte</h1>

                    <?php if (isset($_GET['ok'])) { ?>
                        <div class="alert alert-success">Inscription réussie ! Un gestionnaire doit valider votre compte avant que vous puissiez vous connecter.</div>
                    <?php } ?>
                    <?php if (isset($_GET['erreur']) && $_GET['erreur'] == 'email') { ?>
                        <div class="alert alert-danger">Cet email est déjà utilisé.</div>
                    <?php } ?>
                    <?php if (isset($_GET['erreur']) && $_GET['erreur'] == 'champs') { ?>
                        <div class="alert alert-danger">Merci de remplir tous les champs.</div>
                    <?php } ?>

                    <form action="../src/traitement/newUtilisateurs.php" method="post">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nom</label>
                                <input type="text" name="nom" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Prénom</label>
                                <input type="text" name="prenom" class="form-control" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Mot de passe</label>
                            <input type="password" name="mdp" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Je suis</label>
                            <select name="role" id="role" class="form-select">
                                <option value="etudiant">Étudiant</option>
                                <option value="medecin">Médecin</option>
                                <option value="partenaire">Partenaire (entreprise)</option>
                            </select>
                        </div>

                        <!-- Bloc étudiant -->
                        <div class="bloc-role" id="bloc-etudiant">
                            <div class="mb-3">
                                <label class="form-label">Formation suivie</label>
                                <input type="text" name="formation" class="form-control" placeholder="ex : 3e année de médecine">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Lien vers votre CV</label>
                                <input type="url" name="cv" class="form-control" placeholder="https://...">
                            </div>
                        </div>

                        <!-- Bloc médecin -->
                        <div class="bloc-role" id="bloc-medecin">
                            <div class="mb-3">
                                <label class="form-label">Spécialité</label>
                                <select name="specialite" class="form-select">
                                    <option>Cardiologue</option>
                                    <option>Neurologue</option>
                                    <option>Gastroentérologue</option>
                                    <option>Dermatologue</option>
                                    <option>Pédiatrie</option>
                                    <option>Oncologue</option>
                                    <option>Pneumologue</option>
                                    <option>Rhumatologue</option>
                                    <option>Endocrinologue</option>
                                    <option>Ophtalmologue</option>
                                    <option>Gynécologue</option>
                                    <option>Psychiatre</option>
                                    <option>Urologue</option>
                                    <option>Néphrologue</option>
                                    <option>Hématologue</option>
                                    <option>Oto-rhino-laryngologue</option>
                                    <option>Chirurgien orthopédique</option>
                                    <option>Anesthésiologue</option>
                                    <option>Médecin interne</option>
                                    <option>Médecin généraliste</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Hôpital du groupe</label>
                                <select name="ref_hopital" class="form-select">
                                    <?php foreach ($hopitaux as $hopital) { ?>
                                        <option value="<?= $hopital->getIdHopital() ?>"><?= htmlspecialchars($hopital->getNomHopital()) ?></option>
                                    <?php } ?>
                                </select>
                            </div>
                        </div>

                        <!-- Bloc partenaire -->
                        <div class="bloc-role" id="bloc-partenaire">
                            <div class="mb-3">
                                <label class="form-label">Poste dans l'entreprise</label>
                                <input type="text" name="poste" class="form-control" placeholder="ex : Responsable RH">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 mt-2">S'inscrire</button>
                    </form>
                    <p class="mt-3 text-center">Déjà un compte ? <a href="connexion.php">Se connecter</a></p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Affiche seulement le bloc du rôle choisi
        function afficherBloc() {
            var role = document.getElementById('role').value;   // etudiant, medecin ou partenaire
            document.querySelectorAll('.bloc-role').forEach(function (bloc) {
                bloc.style.display = 'none';                      // on cache tous les blocs
            });
            document.getElementById('bloc-' + role).style.display = 'block';  // on montre le bon
        }
        document.getElementById('role').addEventListener('change', afficherBloc);  // à chaque changement
        afficherBloc();                                                           // et au chargement
    </script>

<?php include 'footer.php'; ?>