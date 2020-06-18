<?php
// namespace App\Models;
class Connection
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
    public function sendrequest($data)
    {
        $this->db->query('INSERT INTO connection_request (sender_id, receiver_id) VALUES(:user_id, :receiver_id)');
        $this->db->bind(':receiver_id', $data['rid']);
        $this->db->bind(':user_id', $data['user_id']);

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
    public function acceptrequest($data)
    {
        $this->db->query('INSERT INTO connections (user1, user2, status) VALUES(:uid, :uid2, :status)');
        $this->db->bind(':uid', $data['user_id']);
        $this->db->bind(':uid2', $data['rid']);
        $this->db->bind(':status', 'connected');
        if ($this->db->execute()) {
            $this->deleterequest($data);
            return true;
        } else {
            return false;
        }
    }
    public function blockconnection($data)
    {
        $this->db->query('UPDATE connections SET status=:status
                          WHERE (user1 =:uid AND user2=:uid2) 
                          OR (user1 =:uid2 AND user2=:uid)');
        $this->db->bind(':uid', $data['user_id']);
        $this->db->bind(':uid2', $data['rid']);
        $this->db->bind(':status', 'blocked');


        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
    public function deleterequest($data)
    {
        $this->db->query('DELETE FROM connection_request where receiver_id=:uid AND sender_id=:uid2');
        $this->db->bind(':uid', $data['user_id']);
        $this->db->bind(':uid2', $data['rid']);

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
    public function check($data)
    {
        $this->db->query('SELECT * FROM connections
        WHERE (user1 =:uid AND user2=:uid2) 
        OR (user1 =:uid2 AND user2=:uid)');
        $this->db->bind(':uid', $data['user_id']);
        $this->db->bind(':uid2', $data['rid']);


        $results = $this->db->resultSet();

        return $results;
    }
}
