<?php
class DroitsForum
{
    private $role;
    private $lecture = ['Général' => ['etudiant','medecins','partenaire','gestionnaire'],'Médecin' => ['medecins','partenaire'],'Étudiants'=> ['etudiant', 'medecins'] , ];
    private $creationPost = ['Général' => ['etudiant','medecins','partenaire','gestionnaire'],'Médecin' => ['medecins','partenaire'],'Étudiants'=> ['etudiant'] , ];
    private $reponse = ['Général' => ['etudiant','medecins','partenaire','gestionnaire'],'Médecin' => ['medecins','partenaire'],'Étudiants'=> ['etudiant', 'medecins'] , ];

public function __construct($role){
    $this->role = $role;
}
public function peutLire(Canal $canal){
    return $this->autorise($this->lecture, $canal);
}
public function peutPoster(Canal $canal){
    return $this->autorise($this->creationPost, $canal);

}
public function peutRepondre(Canal $canal){
    return $this->autorise($this->reponse, $canal);
}
private function autorise($regles, $canal){
$nom = $canal->getNomCanal();
return isset($regles[$nom]) && in_array($this->role, $regles[$nom]);
}



}