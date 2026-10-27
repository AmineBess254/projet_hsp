<?php
class Bdd {
    private $connexionBdd;
    private $identifiant = "root";
    private $motDePasse = "";
    private $nomBdd = "projet_hsp";
    private $host = "localhost";

    public function __construct()
    {
        $this->connexionBdd = new PDO(
            "mysql:host=" . $this->host . ";dbname=" . $this->nomBdd . ";charset=utf8mb4",
            $this->identifiant,
            $this->motDePasse,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]
        );
    }

    public function getConnexionBdd()
    {
        return $this->connexionBdd;
    }
}