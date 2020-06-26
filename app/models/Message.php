<?php
// namespace App\Models;
class Message
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

        // $this->db->query("SELECT *
        //                   FROM chat      
        //                   WHERE user1=:id
        //                   OR user2=:id
        //                   ");
        // $this->db->query("SELECT Message.id, Message.from, Message.body, Message.isread, Message.timestamp, User.id, User.username,
        // FROM messages as Message 
        //  LEFT JOIN users as User ON (Message.from = User.id)
        // ORDER BY Message.timestamp DESC
        // Limit 0, 1");
        // $this->db->query("SELECT 
        //     messages.id, messages.sender_id,messages.receiver_id, messages.message, messages.status, messages.timestamp,
        //     users.id, users.firstname,
        //     COUNT(messages.sender_id) AS NumberOfmessages
        //    FROM messages 
        //    INNER JOIN users
        //    ON (messages.sender_id = users.id)
        //    WHERE sender_id = :id
        //    OR receiver_id = :id
        //    GROUP BY sender_id,receiver_id
        //    ORDER BY messages.timestamp DESC
        //    LIMIT 0 , 30
        //    ");


        //         $this->db->query("SELECT 
        // messages.sender_id,messages.receiver_id,
        // users.id, users.firstname,
        // COUNT(messages.sender_id) AS NumberOfmessages
        // FROM messages 
        // INNER JOIN users
        // ON (messages.sender_id = users.id)
        // WHERE sender_id = :id
        // OR receiver_id = :id
        // GROUP BY sender_id,receiver_id
        // -- ORDER BY messages.timestamp DESC
        // LIMIT 0 , 30
        // ");
        // $this->db->query("SELECT messages.message,
        // messages.sender_id,messages.receiver_id,
        // users.id, users.firstname
        // FROM messages 
        // INNER JOIN users
        // ON (messages.sender_id = users.id)
        // WHERE sender_id = :id
        // OR receiver_id = :id
        // -- GROUP BY sender_id,receiver_id
        // ORDER BY messages.timestamp DESC,messages.message
        // LIMIT 0 , 30
        // ");
        $this->db->query("SELECT sender_id, receiver_id, MAX(timestamp) AS max_date
        FROM messages
        WHERE sender_id = :id OR receiver_id = :id
        GROUP BY sender_id, receiver_id");
        $this->db->bind(':id', $user_id);
        $results = $this->db->resultSet();
        return $results;
    }
    public function getmessages($data)
    {

        // $this->db->query("SELECT messages.id, messages.sender_id,messages.receiver_id, messages.message, messages.status, messages.timestamp,
        //                   users.id, users.firstname
        //                   FROM messages 
        //                   LEFT JOIN users ON (messages.sender_id = users.id)      
        //                   WHERE (sender_id=:senderid
        //                   AND receiver_id=:receiverid)
        //                   OR(sender_id=:receiver_id AND receiver_id=:senderid)
        //                   ");
        //   $this->db->query("SELECT *
        //   FROM messages 

        //   WHERE (sender_id=:senderid
        //   AND receiver_id=:receiverid)
        //   OR(sender_id=:receiver_id AND receiver_id=:senderid)
        //   ");
        $this->db->query("SELECT *
                        FROM messages 

                        WHERE (sender_id=:senderid
                        AND receiver_id=:receiverid)
                        OR(sender_id=:receiverid AND receiver_id=:senderid)
                        ");
        $this->db->bind(':senderid', $data['sender_id']);
        $this->db->bind(':receiverid', $data['receiver_id']);
        $results = $this->db->resultSet();
        return $results;
    }
    public function sendmessage($data)
    {

        // var_dump($data);
        $this->db->query("INSERT INTO messages (sender_id, receiver_id, message, status, type, file, chat_id) VALUES(:sender_id, :receiver_id, :message, :status, :type, :file, :chat_id)");
        $this->db->bind(':sender_id', $data['sender_id']);
        $this->db->bind(':receiver_id', $data['receiver_id']);
        $this->db->bind(':message', $data['message']);
        $this->db->bind(':status', $data['status']);
        $this->db->bind(':type', $data['type']);
        $this->db->bind(':file', $data['file']);
        $this->db->bind(':chat_id', $data['chat_id']);
        if ($this->db->execute()) {
            return $this->db->lastId();
        } else {
            return false;
        }
    }
    public function validatechat($id)
    {

        $this->db->query("SELECT *
                          FROM chats
                          WHERE id=:id
                          ");
        $this->db->bind(':id', $id);
        $results = $this->db->single();
        return $results;
    }
    public function createchat($data)
    {
        $this->db->query("INSERT 
        INTO chat      
        (user1, user2, last_message, status) VALUES(:sender_id, :receiver_id, :message,:status)
        ");
        $this->db->bind(':sender_id', $data['sender_id']);
        $this->db->bind(':receiver_id', $data['receiver_id']);
        $this->db->bind(':message', $data['message']);
        $this->db->bind(':status', $data['status']);
        if ($this->db->execute()) {
            return $this->db->lastId();
        } else {
            return false;
        }
    }
    public function updatechat($data)
    {
        $this->db->query('UPDATE posts SET last_message=:message, status=:status WHERE id =:id');
        $this->db->bind(':id', $data['chat_id']);
        $this->db->bind(':message', $data['message']);
        $this->db->bind(':status', $data['status']);
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
    public function check($data)
    {
        $this->db->query('SELECT *
                          FROM messages
                          WHERE (sender_id=:senderid
                          AND receiver_id=:receiverid)
                          OR(sender_id=:receiver_id AND receiver_id=:senderid)
                          ');
        $this->db->bind(':senderid', $data['sender_id']);
        $this->db->bind(':receiverid', $data['sender_id']);
        $results = $this->db->resultSet();
        return $results;
    }
}
