<?php

class Actualite
{
    private $id_actualite;
    private $titre;
    private $contenu;
    private $date_publication;
    private $ref_utilisateur;

    public function __construct($id_actualite, $titre, $contenu, $date_publication, $ref_utilisateur)
    {
        $this->id_actualite = $id_actualite;
        $this->titre = $titre;
        $this->contenu = $contenu;
        $this->date_publication = $date_publication;
        $this->ref_utilisateur = $ref_utilisateur;
    }

    public function getIdActualite() { return $this->id_actualite; }
    public function getTitre() { return $this->titre; }
    public function getContenu() { return $this->contenu; }
    public function getDatePublication() { return $this->date_publication; }
    public function getRefUtilisateur() { return $this->ref_utilisateur; }

    public function setTitre($titre) { $this->titre = $titre; }
    public function setContenu($contenu) { $this->contenu = $contenu; }
}
