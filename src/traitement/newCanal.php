<?php
require_once '../bdd/Bdd.php';
require_once '../modele/Canal.php';
require_once '../repository/CanalRepository.php';

if (isset($_POST['nom_canal'])) {
    $canal = new Canal(null, $_POST['nom_canal'], date('Y-m-d H:i:s'));
    $canalRepository = new CanalRepository();
}
?>