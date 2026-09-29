<?php

class Canal
{
    private $idCanal;
    private $nomCanal;
    private $dateHeure;

    public function __construct($idCanal, $nomCanal, $dateHeure)
    {
        $this->idCanal = $idCanal;
        $this->nomCanal = $nomCanal;
        $this->dateHeure = $dateHeure;
    }

    public function getIdCanal()
    {
        return $this->idCanal;
    }

    public function getNomCanal()
    {
        return $this->nomCanal;
    }

    public function getDateHeure()
    {
        return $this->dateHeure;
    }

    public function setIdCanal($idCanal)
    {
        $this->idCanal = $idCanal;
    }

    public function setNomCanal($nomCanal)
    {
        $this->nomCanal = $nomCanal;
    }

    public function setDateHeure($dateHeure)
    {
        $this->dateHeure = $dateHeure;
    }
}