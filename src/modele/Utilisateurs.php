<?php

class Utilisateurs {
    private $idUtilisateurs;
    private $nom;
    private $prenom;
    private $email;
    private $mdp;
    private $statutValidation;
    private $refGestionnaire;


    public function __construct($idUtilisateurs = null,$nom, $prenom, $email, $mdp,$statutValidation = 'en_attente',$refGestionnaire = null ) {
        $this->idUtilisateurs = $idUtilisateurs;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->email = $email;
        $this->mdp = $mdp;
        $this->statutValidation = $statutValidation;
        $this->refGestionnaire = $refGestionnaire;
    }
    public function getIdUtilisateurs() {
        return $this->idUtilisateurs;
    }
    public function getNom() {
        return $this->nom;

    }
    public function getPrenom() {
        return $this->prenom;
    }
    public function getEmail() {
        return $this->email;
    }
    public function getMdp() {
        return $this->mdp;
    }
    public function getStatutValidation() {
        return $this->statutValidation;
    }
    public function getRefGestionnaire() {
        return $this->refGestionnaire;
    }
    public function setIdUtilisateurs($idUtilisateurs) {
        $this->idUtilisateurs = $idUtilisateurs;
    }
    public function setNom($nom) {
        $this->nom = $nom;
    }
    public function setPrenom($prenom) {
        $this->prenom = $prenom;
    }
    public function setEmail($email) {
        $this->email = $email;
    }
    public function setMdp($mdp) {
        $this->mdp = $mdp;
    }
    public function setStatutValidation($statutValidation) {
        $this->statutValidation = $statutValidation;
    }
    public function setRefGestionnaire($refGestionnaire) {
        $this->refGestionnaire = $refGestionnaire;
    }



}