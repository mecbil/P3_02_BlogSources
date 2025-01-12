<?php
namespace Controllers ;

use Models\CommentManager;
use application\Renderer;
use Models\PostManager;

class PostController
{
    // Montrer la page d'un post identifier par son uuid'
    public function showOnePost()
    {
        $postManager = new PostManager();
        $commentManager = new CommentManager();

        // Get a post with uuid
        $guuid = filter_input(INPUT_GET, 'uuid');
        $uuid = isset($guuid) ? filter_var($guuid, FILTER_SANITIZE_STRING):"";
        $post = $postManager->findPost('uuid', $uuid);
        if (!$post) {
            $rendu = new renderer;
            $rendu->render(array(
                'page' => '404',
                'pageTitle' => "Page d'erreur"
            ));
        }
        $post_id = $post->getPost_id();
        $comments = $commentManager->searchcomments('post_id', $post_id);
  
        // Affichage (Show)
        $user = '';
        $role = '';
        $user_id = '';

        if (isset($_SESSION['user'])) {
            $user = filter_var($_SESSION['user']);
            $role = filter_var($_SESSION['role']);
            $user_id = filter_var($_SESSION['user_id']);
        }

        $rendu = new renderer;
        $rendu->render(array(
            'page' => 'posts/post',
            'pageTitle' => 'Blog Posts',
            'post' => $post,
            'comments' => $comments,
            'user' => $user,
            'role' => $role,
            'user_id' => $user_id
        ));
    }

    // Ajouter un Blog post
    public function insertPost()
    {
        $commentManager= new CommentManager();
        $comments = $commentManager->findAllcomments("date_modify DESC");
        $postManager = new PostManager();
        $erreur= $postManager->insert();

        if (empty($erreur)) {
      
            // Get all posts
            $posts = $postManager->findAllPosts("date_modify DESC");

            // Affichage (Show)
            $rendu = new renderer;
            $rendu->render(array(
                'page' => 'posts/posts',
                'pageTitle' => 'Blog Posts',
                'posts' => $posts
            ));
        }

        $pageTitle = $_SESSION['user'];

        $chapo = filter_input(INPUT_POST, 'chapo');
        $content = filter_input(INPUT_POST, 'content');
        $author = filter_input(INPUT_POST, 'author');
        $post_id = filter_input(INPUT_POST, 'post_id');

        $rendu = new renderer;
        $rendu->render(array(
            'page' => 'users/indexuser',
            'pageTitle' => $pageTitle,
            'comments' => $comments,
            'edit' => false,
            'erreur' => $erreur,
            'title'=> filter_input(INPUT_POST, 'title'),
            'chapo' => $chapo,
            'content' => $content,
            'author' => $author,
            'post_id' => $post_id
        ));
    }

    public function deletePost()
    {
        $uuid = filter_input(INPUT_GET, 'uuid');

        $postManager = new PostManager();
        $erreur = $postManager->deletePost($uuid);

        // Pas d'erreur de suppression
        if (empty($erreur)) {

            // Get all posts
            $posts = $postManager->findAllPosts("date_modify DESC");
    
            // Affichage (Show)
            $rendu = new renderer;
            $rendu->render(array(
                'page' => 'posts/posts',
                'pageTitle' => 'Blog Posts',
                'posts' => $posts
            ));
        }
    }

    // Liste des posts non validé
    public function validPosts()
    {
        $commentManager= new CommentManager();

        // Get all posts
        $comments = $commentManager->findAllcomments("date_modify DESC");

        // Affichage (Show)

        $rendu = new renderer;
        $rendu->render(array(
            'page' => 'users/indexuser',
            'comments' => $comments,
            'edit' => false,
            'title' => '',
            'chapo' => '',
            'content' => '',
            'author' => ''
        ));
    }

    // Editer un posts
    public function editPost()
    {
        $postManager= new PostManager();
        $commentManager= new CommentManager();
        $comments = $commentManager->findAllcomments("date_modify DESC");

        // Get a posts with uuid
        $guuid = filter_input(INPUT_GET, 'uuid');
        $uuid = isset($guuid)  ? $guuid :"";
        $post = $postManager->findPost("uuid", $uuid);

        // Affichage (Show)
        $pageTitle = "Admin - ".$_SESSION['user'];

        $rendu = new renderer;
        $rendu->render(array(
            'page' => 'users/indexuser',
            'pageTitle' => $pageTitle,
            'comments' => $comments,
            'edit' => true,
            'post' => $post
        ));
    }

    public function updatePost()
    {
        $gpost = filter_input(INPUT_POST, 'post_id');
        
        $postManager = new PostManager();
        $erreur = $postManager->updatePost($gpost);

        if (empty($erreur)) {
        
            // Get all posts
            $posts = $postManager->findAllPosts("date_modify DESC");
    
            // Affichage (Show)
            $pageTitle = "Blog Posts";

            $rendu = new renderer;
            $rendu->render(array(
                'page' => 'posts/posts',
                'pageTitle' => $pageTitle,
                'posts' => $posts
            ));
        }
        
        if (!empty($erreur)) {
            if (isset($_SESSION['user'])) {
                $commentManager= new CommentManager();
                $comments = $commentManager->findAllcomments("date_modify DESC");
                $pageTitle = $_SESSION['user'];
                
                $rendu = new renderer;
                $rendu->render(array(
                    'page' => 'users/indexuser',
                    'pageTitle' => $pageTitle,
                    'erreur' => $erreur,
                    'comments' => $comments,
                    'edit' => true
                ));
            }
        }
    }
}
