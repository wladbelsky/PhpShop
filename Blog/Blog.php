<?php
/**
 * Created by PhpStorm.
 * User: wladb
 * Date: 25-Nov-16
 * Time: 15:19
 */

class Blog
{
    private $mysqli;

    function __construct()
    {
        $this->mysqli = $this->connect();
    }

    private function connect()
    {
        $mysqli = new \mysqli("localhost", "myhost", "myhost", "Blog");
        $mysqli->set_charset("utf8");
        if ($mysqli->connect_errno) {
            echo "Не удалось подключиться к MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
        }
        else
        { return $mysqli; }
    }
    //POSTs
    public function getPosts()
    {
        return $this->mysqli->query("SELECT * FROM Blog.Posts");
    }

    public function addPost($text)
    {
        $this->mysqli->query("INSERT INTO Blog.Posts (Text) VALUES ('$text')");
    }
    
    public function removePost($postId)
    {
        $this->mysqli->query("DELETE FROM Blog.Posts WHERE PostId = $postId");
    }
    //COMMENTs
    public function addComment($userId, $postId, $text)
    {
        $this->mysqli->query("INSERT INTO Blog.Comments (UserId, PostId, Text) VALUES ($userId, $postId, '$text')");
    }

    public function removeComment($commentId)
    {
        //
    }
    

}