<?php

class Utilisateurs {
    private $idUtilisateurs;
    private $nom;
    private $prenom;
    private $email;
    private $mdp;
    private $statutValidation;
    private $refGestionnaire;
    private $role;

    // $role est le dernier paramètre et vaut null par défaut :
    // les anciens appels à new Utilisateurs(...) continuent de fonctionner
    public function __construct($idUtilisateurs, $nom, $prenom, $email, $mdp, $statutValidation, $refGestionnaire, $role = null) {
        $this->idUtilisateurs = $idUtilisateurs;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->email = $email;
        $this->mdp = $mdp;
        $this->statutValidation = $statutValidation;
        $this->refGestionnaire = $refGestionnaire;
        $this->role = $role;
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
    public function getRole() {
        return $this->role;
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
    public function setRole($role) {
        $this->role = $role;
    }
}