<?php

class Reponse
{
    private $idReponse;
    private $contenu;
    private $dateHeure;
    private $refUtilisateur;
    private $refPost;


    public function __construct($idReponse = null,$contenu,$dateHeure = null ,$refUtilisateur,$refPost){
        $this->idReponse=$idReponse;
        $this->contenu=$contenu;
        $this->dateHeure=$dateHeure;
        $this->refUtilisateur=$refUtilisateur;
        $this->refPost=$refPost;

    }

    public function getIdReponse(){
        return $this->idReponse;
    }
    public function setIdReponse($idReponse){
        $this->idReponse=$idReponse;
    }
    public function getContenu(){
        return $this->contenu;
    }
    public function setContenu($contenu){
        $this->contenu=$contenu;
    }
    public function getDateHeure(){
        return $this->dateHeure;

    }
    public function setDateHeure($dateHeure){
        $this->dateHeure=$dateHeure;
    }
    public function getRefUtilisateur(){
        return $this->refUtilisateur;
    }
    public function setRefUtilisateur($refUtilisateur){
        $this->refUtilisateur=$refUtilisateur;
    }
    public function getRefPost(){
        return $this->refPost;
    }
    public function setRefPost($refPost){
        $this->refPost=$refPost;
    }


}