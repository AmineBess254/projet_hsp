<?php
require_once'../src/bdd/Bdd.php';
require_once'../src/modele/Canal.php';
require_once '../src/modele/Post.php';
require_once '../src/repository/CanalRepository.php';
require_once '../src/repository/PostRepository.php';
exigerConnexion();

$canaux = array();
foreach ((new CanalRepository()->getAllCanal()) as $canal) {
    if
}









?>