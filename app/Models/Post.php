<?php

class Post {
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function insert($dados) {
        $this->db->query("INSERT INTO posts(id_user, title_post, text_post) VALUES (:id_user, :title_post, :text_post)");
        $this->db->bind("id_user", $dados['user_id']);
        $this->db->bind("title_post", $dados['titulo']);
        $this->db->bind("text_post", $dados['texto']);

        if($this->db->executa()):
            return true;
        else:
            return false;
        endif;
    }

    public function update($dados) {
        $this->db->query("UPDATE posts SET title_post = :title_post, text_post = :text_post WHERE id = :id");
        $this->db->bind("id", $dados['id']);
        $this->db->bind("title_post", $dados['titulo']);
        $this->db->bind("text_post", $dados['texto']);

        if($this->db->executa()):
            return true;
        else:
            return false;
        endif;
    }

    public function delete($id) {

        $this->db->query("DELETE FROM posts WHERE id = :id");
        $this->db->bind("id", $id); 

        if($this->db->executa()):
            return true;
        else:
            return false;
        endif;

    }

    public function readPosts() {
        $this->db->query("SELECT *,
        posts.id as postId,
        posts.created_at as postDataRegistro,
        users.id as userId,
        users.created_at as userDataRegistro
         FROM posts
         INNER JOIN users ON
         posts.id_user = users.id
         ORDER BY posts.id DESC
        ");
        return $this->db->resultados();
    }

    public function readPostById($id) {
        $this->db->query("SELECT * FROM posts WHERE id = :id");
        $this->db->bind('id', $id);

        return $this->db->resultado();
    }

}