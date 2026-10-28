<?php

class Specialite
{
    private $idSpecialite;
    private $nomSpecialite;

    public function __construct($idSpecialite, $nomSpecialite)
    {
        $this->idSpecialite = $idSpecialite;
        $this->nomSpecialite = $nomSpecialite;
    }

    public function getIdSpecialite()
    {
        return $this->idSpecialite;
    }

    public function getNomSpecialite()
    {
        return $this->nomSpecialite;
    }
}
