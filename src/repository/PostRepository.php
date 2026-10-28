<?php
class PostRepository
{
    private $connexionBdd;

    public function __construct()
    {
        $this->connexionBdd = (new Bdd())->getConnexionBdd();
    }

    private function creerPost($result)
    {
        return new Post($result["id_post"], $result["contenu"], $result["date_heure"],
            $result["titre"], $result["ref_canal"], $result["ref_utilisateur"]);
    }

    public function getPost($idPost)
    {
        $sql = "SELECT * FROM post WHERE id_post = :id_post";
        $req = $this->connexionBdd->prepare($sql);
        $req->bindValue(':id_post', $idPost);
        $req->execute();
        $result = $req->fetch();
        if (!$result) {
            return null;
        }
        return $this->creerPost($result);
    }

    public function getAllPost()
    {
        $sql = "SELECT * FROM post ORDER BY date_heure DESC";
        $req = $this->connexionBdd->prepare($sql);
        $req->execute();
        $tabPost = array();
        foreach ($req->fetchAll() as $result) {
            $tabPost[] = $this->creerPost($result);
        }
        return $tabPost;
    }

    public function getPostParCanal($idCanal)
    {
        $sql = "SELECT * FROM post WHERE ref_canal = :ref_canal ORDER BY date_heure DESC";
        $req = $this->connexionBdd->prepare($sql);
        $req->bindValue(':ref_canal', $idCanal);
        $req->execute();
        $tabPost = array();
        foreach ($req->fetchAll() as $result) {
            $tabPost[] = $this->creerPost($result);
        }
        return $tabPost;
    }

    public function getPostParUtilisateur($idUtilisateur)
    {
        $sql = "SELECT * FROM post WHERE ref_utilisateur = :ref_utilisateur ORDER BY date_heure DESC";
        $req = $this->connexionBdd->prepare($sql);
        $req->bindValue(':ref_utilisateur', $idUtilisateur);
        $req->execute();
        $tabPost = array();
        foreach ($req->fetchAll() as $result) {
            $tabPost[] = $this->creerPost($result);
        }
        return $tabPost;
    }

  
    public function getPostParReponseUtilisateur($idUtilisateur)
    {
        $sql = "SELECT DISTINCT post.* FROM post
                INNER JOIN reponse ON reponse.ref_post = post.id_post
                WHERE reponse.ref_utilisateur = :ref_utilisateur
                ORDER BY post.date_heure DESC";
        $req = $this->connexionBdd->prepare($sql);
        $req->bindValue(':ref_utilisateur', $idUtilisateur);
        $req->execute();
        $tabPost = array();
        foreach ($req->fetchAll() as $result) {
            $tabPost[] = $this->creerPost($result);
        }
        return $tabPost;
    }

    public function ajouterPost(Post $post)
    {
        $sql = "INSERT INTO post (contenu, titre, ref_canal, ref_utilisateur)
                VALUES (:contenu, :titre, :ref_canal, :ref_utilisateur)";
        $req = $this->connexionBdd->prepare($sql);
        $req->bindValue(':contenu', $post->getContenu());
        $req->bindValue(':titre', $post->getTitre());
        $req->bindValue(':ref_canal', $post->getIdCanal());
        $req->bindValue(':ref_utilisateur', $post->getIdUtilisateur());
        $req->execute();
        return $this->connexionBdd->lastInsertId();
    }

    public function modifierPost(Post $post)
    {
        $sql = "UPDATE post SET contenu = :contenu, titre = :titre WHERE id_post = :id_post";
        $req = $this->connexionBdd->prepare($sql);
        $req->bindValue(':id_post', $post->getIdPost());
        $req->bindValue(':contenu', $post->getContenu());
        $req->bindValue(':titre', $post->getTitre());
        $req->execute();
    }

    public function supprimerPost(Post $post)
    {
        $sql = "DELETE FROM post WHERE id_post = :id_post";
        $req = $this->connexionBdd->prepare($sql);
        $req->bindValue(':id_post', $post->getIdPost());
        $req->execute();
    }
}