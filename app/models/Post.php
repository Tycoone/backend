<?php

// namespace App\Models;

class Post
{
    private $db;

    /**
     * Post constructor.
     * @param null $data
     */
    public function __construct()
    {
        $this->db = new Database;
    }

    public function getPosts()
    {
        $this->db->query('SELECT *,
                          posts.id as postId,
                          users.id as userId
                          FROM posts
                          INNER JOIN users
                          ON posts.user_id = users.id
                          ORDER BY posts.created_at DESC
                          ');
        $results = $this->db->resultSet();
        return $results;
    }
    public function addPost($data)
    {
        $this->db->query('INSERT INTO posts (caption, user_id, no_of_likes, no_of_comments, no_of_shares) VALUES(:caption, :user_id, :no_of_likes, :no_of_comments, :no_of_shares)');
        $this->db->bind(':caption', $data['caption']);
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':no_of_shares', 0);
        $this->db->bind(':no_of_likes', 0);
        $this->db->bind(':no_of_comments', 0);

        if ($this->db->execute()) {
            $last_id = $this->db->lastId();
            if (isset($data['file'])) {
                foreach ($data['file'] as $file) {
                    $arr = [
                        'post_id' => $last_id,
                        'file' => $file
                    ];
                    $this->uploadfile($arr);
                }
            }
            return true;
        } else {
            return false;
        }
    }
    public function show($id)
    {
        $this->db->query("SELECT *
                          FROM posts      
                          WHERE id=:id
                          ");
        $this->db->bind(':id', $id);
        $results = $this->db->single();
        return $results;
    }
    public function updatePost($data)
    {
        $this->db->query('UPDATE posts SET caption=:caption WHERE id =:id');
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':caption', $data['title']);
        $this->db->bind(':body', $data['body']);



        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
    public function deletePost($id)
    {
        $this->db->query('DELETE FROM posts where id=:id');
        $this->db->bind(':id', $id);

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
    public function likepost($data)
    {
        $this->db->query('INSERT INTO likes (user_id, post_id) VALUES(:user_id, :post_id)');
        $this->db->bind(':post_id', $data['post_id']);
        $this->db->bind(':user_id', $data['user_id']);

        if ($this->db->execute()) {
            $this->update_no_of_likes($data['post_id']);
            return true;
        } else {
            return false;
        }
    }
    public function commentonpost($data)
    {
        # code...
    }
    protected function update_no_of_comments($post_id)
    {
        $post = $this->show($post_id);
        $updatedno = $post->no_of_likes + 1;

        $this->db->query('UPDATE posts SET no_of_comments=:comment WHERE id =:id');
        $this->db->bind(':id', $post['id']);
        $this->db->bind(':comment', $updatedno);
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
    protected function update_no_of_likes($post_id)
    {
        $post = $this->show($post_id);
        $updatedno = $post->no_of_likes + 1;
        $this->db->query('UPDATE posts SET no_of_likes=:likes WHERE id =:id');
        $this->db->bind(':id', $post_id);
        $this->db->bind(':likes', $updatedno);

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
    // public function validate_if_liked(Type $var = null)
    // {
    //     # code...
    // }
    protected function uploadfile($data)
    {
        $this->db->query('INSERT INTO post_files (post_id, file) VALUES(:post_id, :file)');
        $this->db->bind(':post_id', $data['post_id']);
        $this->db->bind(':file', $data['file']);

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
}
