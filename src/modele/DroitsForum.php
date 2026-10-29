<?php
class DroitsForum
{
    private $role;
    private $lecture = ['Général' => ['etudiant','medecin','partenaire','gestionnaire'],'Médecin' => ['medecin','partenaire'],'Étudiants'=> ['etudiant', 'medecin'] , ];
    private $creationPost = ['Général' => ['etudiant','medecin','partenaire','gestionnaire'],'Médecin' => ['medecin','partenaire'],'Étudiants'=> ['etudiant'] , ];
    private $reponse = ['Général' => ['etudiant','medecin','partenaire','gestionnaire'],'Médecin' => ['medecin','partenaire'],'Étudiants'=> ['etudiant', 'medecin'] , ];

public function __contruct($role){
    $this->role = $role;
}
public function peutLire(Canal $canal){
    return $this->autorise($this->lecture, $canal);
}
public function peutPosterr(Canal $canal){
    return $this->autorise($this->creationPost, $canal);

}
public function peutRepondre(Canal $canal){
    return $this->autorise($this->reponse, $canal);
}
private function autorise($liste, $canal){
$nom = $canal->getNomCanal();
return isset($regles[$nom]) && in_array($this->role, $regles[$nom]);
}



}