<?php
namespace Models;

use Models\Post;

class PostManager extends Manager
{
    protected $table = "posts";

    // trouver tous les enregistrement ?trier &/ou limiter
    public function findAllPosts(?string $order="", ?string $limit="")
    {
        $sql= "SELECT * FROM posts";
        
        if ($order) {
            $sql .=" ORDER BY ".$order;
        }
        if ($limit) {
            $sql .=" LIMIT ".$limit;
        }
        $resultats = $this->pdo->query($sql);
        $items = $resultats->fetchAll();

        // Hydrate les posts
        $itemshydrate =array();

        foreach ($items as $item) {
            $post = new Post;

            array_push($itemshydrate, $this->hydrate($post, $item)) ;
        }

        return $itemshydrate;
    }

    // trouver un enregistrement par son uuid -a voir -
    public function findPost(string $findword, string $word)
    {
        $sql = "SELECT * FROM posts WHERE ".' '.$findword.' = '."'$word'";
        $query = $this->pdo->prepare($sql);
        $query->execute([$findword => $word]);
        $item = $query->fetch();

        if ($item) {
            $post = new Post;
            $itemshydrate = $this->hydrate($post, $item);
        
            return $itemshydrate;
        }
    
        return $item;
    }

    public function insert()
    {
        // tester le formulaire
        // 1- Un des elements du formulaire vide
        if (empty($_POST['title']) || empty($_POST['chapo'])|| empty($_POST['content']) || empty($_POST['author'])) {
            $erreur='Veuillez remplir tous les champs';

            return $erreur;
        }

        $title = strip_tags($_POST['title']);
        $chapo = strip_tags($_POST['chapo']);
        $content = strip_tags($_POST['content']);
        $author = strip_tags($_POST['author']);
        $date_creat = date("Y-m-d h:i:s");
        $date_modify = date("Y-m-d h:i:s");
        $uuid = uniqid();
        $user_id=$_SESSION['user_id'];

        // On instencie et hydrater le model;
        $post = new Post;
        $post->setTitle($title)
            ->setChapo($chapo)
            ->setContent($content)
            ->setAuthor($author)
            ->setDate_creat($date_creat)
            ->setDate_modify($date_modify)
            ->setUuid($uuid)
            ->setUser_id($user_id);

        // On enregistre

        $sql = $this->pdo->prepare("INSERT INTO  posts (uuid, date_creat, date_modify, chapo, content, title, author, user_id) 
        VALUES (:uuid, :date_creat, :date_modify, :chapo, :content, :title, :author, :user_id)");

        $sql->bindValue(':uuid', $post->getUuid());
        $sql->bindValue(':date_creat', $post->getDate_creat());
        $sql->bindValue(':date_modify', $post->getDate_modify());
        $sql->bindValue(':chapo', $post->getChapo());
        $sql->bindValue(':content', $post->getContent());
        $sql->bindValue(':title', $post->getTitle());
        $sql->bindValue(':author', $post->getAuthor());
        $sql->bindValue(':user_id', $post->getUser_id());
        $sql->execute();
            
        return null;
    }

    public function deletePost($uuid)
    {
        $post = $this->findPost('uuid', $uuid);
        if ($post) {
            $this->delete($uuid);

            return null;
        }
        if (empty($post)) {
            $erreur='Veuillez donner un identifiant valable';

            return $erreur;
        }
    }

    public function updatePost($post_id)
    {
        // tester le formulaire
        // 1- Un des elements du formulaire vide

        if (empty($_POST['title']) || empty($_POST['chapo'])|| empty($_POST['content']) || empty($_POST['author'])) {
            $erreur='Veuillez remplir tous les champs';

            return $erreur;
        }

        $title = strip_tags($_POST['title']);
        $chapo = strip_tags($_POST['chapo']);
        $content = strip_tags($_POST['content']);
        $author = strip_tags($_POST['author']);
        $date_modify = date("Y-m-d h:i:s");

        // On instencie et hydrater le model;
        $post = new Post;
        $post->setTitle($title)
            ->setChapo($chapo)
            ->setContent($content)
            ->setAuthor($author)
            ->setDate_modify($date_modify);

        // On enregistre

        $sql = $this->pdo->prepare('UPDATE posts SET date_modify = :date_modify, chapo = :chapo,
         content = :content, title = :title, author = :author WHERE post_id = '.$post_id.'');
         
        $sql->bindValue(':date_modify', $post->getDate_modify());
        $sql->bindValue(':chapo', $post->getChapo());
        $sql->bindValue(':content', $post->getContent());
        $sql->bindValue(':title', $post->getTitle());
        $sql->bindValue(':author', $post->getAuthor());
        $sql->execute();

        return null;
    }
}