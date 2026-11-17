<?php

class Post
{
    private $idPost;
    private $contenu;
    private $dateHeure;
    private $titre;
    private $refCanal;

    public function __construct($idPost = null,$contenu,$dateHeure = null,$titre = null,$refCanal){
        $this->idPost = $idPost;
        $this->contenu = $contenu;
        $this->dateHeure = $dateHeure;
        $this->titre = $titre;
        $this->refCanal = $refCanal;

    }
    public function getIdPost(){
        return $this->idPost;
    }
    public function getContenu(){
        return $this->contenu;
    }
    public function getDateHeure(){
        return $this->dateHeure;
    }
    public function getTitre(){
        return $this->titre;
    }
    public function getRefCanal(){
        return $this->refCanal;
    }
    public function setIdPost($idPost){
        $this->idPost = $idPost;
    }
    public function setContenu($contenu){
        $this->contenu = $contenu;
    }
    public function setDateHeure($dateHeure){
        $this->dateHeure = $dateHeure;
    }
    public function setTitre($titre){
        $this->titre = $titre;
    }
    public function setRefCanal($refCanal){
        $this->refCanal = $refCanal;
    }

}