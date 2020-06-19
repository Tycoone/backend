<?php
// namespace App\Models;
class User
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
    //find user through email
    public function findUserByEmail($email)
    {
        // $this->db->bind(':email', $email);
        $this->db->query('SELECT * FROM users WHERE email= :email');
        $this->db->bind(':email', $email);

        $row = $this->db->single();
        //check row
        if ($this->db->rowCount() > 0) {
            return true;
        } else {
            return false;
        }
    }

    public function register($data)
    {
        $this->db->query('INSERT INTO users (firstname,lastname, email, password) VALUES(:firstname, :lastname, :email, :password)');
        $this->db->bind(':firstname', $data['firstname']);
        $this->db->bind(':lastname', $data['lastname']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', $data['password']);

        if ($this->db->execute()) {
            $this->createprofile($data);
            return true;
        } else {
            return false;
        }
    }
    public function login($email,  $password)
    {
        $this->db->query('SELECT *FROM users WHERE email=:email');
        $this->db->bind(':email', $email);
        $row = $this->db->single();
        $hashed_password = $row->password;
        if (password_verify($password, $hashed_password)) {
            return $row;
        } else {
            return false;
        }
    }
    public function getUserbyId($user_id)
    {
        $this->db->query('SELECT * FROM users WHERE id= :user_id OR email=:user_id');
        $this->db->bind(':user_id', $user_id);

        $row = $this->db->single();
        return $row;
    }
    public function createprofile($data)
    {

        $user = $this->getUserbyId($data['email']);

        $data['user_id'] = $user->id;
        $this->db->query('INSERT INTO profiles (user_id,gender) VALUES(:user_id, :gender)');
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':gender', $data['gender']);

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
    public function getprofile($user_id)
    {
        $this->db->query('SELECT * FROM users
                          INNER JOIN profiles
                          ON users.id = profiles.user_id
                         WHERE users.id= :user_id
                       ');
        $this->db->bind(':user_id', $user_id);
        $results = $this->db->resultSet();
        return $results;
    }
}
