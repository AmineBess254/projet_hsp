<?php
class Hopital
{
    private $idHopital;
    private $nomHopital;
    private $adresseHopital;

    /**
     * @param $idHopital
     * @param $nomHopital
     * @param $adresseHopital
     */
    public function __construct($idHopital, $nomHopital, $adresseHopital)
    {
        $this->idHopital = $idHopital;
        $this->nomHopital = $nomHopital;
        $this->adresseHopital = $adresseHopital;
    }


    public function getIdHopital()
    {
        return $this->idHopital;
    }

    public function setIdHopital($idHopital)
    {
        $this->idHopital = $idHopital;
    }


    public function getNomHopital()
    {
        return $this->nomHopital;
    }

    public function setNomHopital($nomHopital)
    {
        $this->nomHopital = $nomHopital;
    }


    public function getAdresseHopital()
    {
        return $this->adresseHopital;
    }

    public function setAdresseHopital($adresseHopital)
    {
        $this->adresseHopital = $adresseHopital;
    }
}