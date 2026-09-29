<?php

class Candidature
{
    private $idCandidature;
    private $motivation;
    private $dateCandidature;
    private $statutCandidature;
    private $refUtilisateur;
    private $refOffre;

    public function __construct(
        $idCandidature,
        $motivation,
        $dateCandidature,
        $statutCandidature,
        $refUtilisateur,
        $refOffre
    ) {
        $this->idCandidature = $idCandidature;
        $this->motivation = $motivation;
        $this->dateCandidature = $dateCandidature;
        $this->statutCandidature = $statutCandidature;
        $this->refUtilisateur = $refUtilisateur;
        $this->refOffre = $refOffre;
    }

    public function getIdCandidature()
    {
        return $this->idCandidature;
    }

    public function getMotivation()
    {
        return $this->motivation;
    }

    public function getDateCandidature()
    {
        return $this->dateCandidature;
    }

    public function getStatutCandidature()
    {
        return $this->statutCandidature;
    }

    public function getRefUtilisateur()
    {
        return $this->refUtilisateur;
    }

    public function getRefOffre()
    {
        return $this->refOffre;
    }

    public function setIdCandidature($idCandidature)
    {
        $this->idCandidature = $idCandidature;
    }

    public function setMotivation($motivation)
    {
        $this->motivation = $motivation;
    }

    public function setDateCandidature($dateCandidature)
    {
        $this->dateCandidature = $dateCandidature;
    }

    public function setStatutCandidature($statutCandidature)
    {
        $this->statutCandidature = $statutCandidature;
    }

    public function setRefUtilisateur($refUtilisateur)
    {
        $this->refUtilisateur = $refUtilisateur;
    }

    public function setRefOffre($refOffre)
    {
        $this->refOffre = $refOffre;
    }
}