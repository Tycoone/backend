<?php
// namespace App\Models;
class Messages
{
    private $db;

    /**
     * User constructor.
     * @param null $data
     */
    public function __construct()
    {
        $this->db = new Database;
    }
    public function getchats($user_id)
    {

        $this->db->query("SELECT *
                          FROM chats      
                          WHERE sender_id=:id
                          OR receiver_id=:id
                          ");
        $this->db->bind(':id', $user_id);
        $results = $this->db->resultSet();
        return $results;
    }
    public function getmessages($id)
    {

        $this->db->query("SELECT *
                          FROM messages      
                          WHERE chat_id=:id
                          ");
        $this->db->bind(':id', $id);
        $results = $this->db->resultSet();
        return $results;
    }
    public function sendmessage($sender_id, $receiver_id, $message)
    {

        $this->db->query("SELECT *
                          FROM messages      
                          WHERE chat_id=:id
                          ");
        $this->db->bind(':id', $sender_id);
        $results = $this->db->resultSet();
        return $results;
    }
    public function showchat($id)
    {

        $this->db->query("SELECT *
                          FROM chats
                          WHERE id=:id
                          ");
        $this->db->bind(':id', $id);
        $results = $this->db->resultSet();
        return $results;
    }
    public function createchat(Type $var = null)
    {
        # code...
    }
}
