<?php

class ClientTable
{
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function isValidLogin($username, $password)
    {
        $query = 'SELECT `password` FROM `client`
                  WHERE username = :username';
        $statement = $this->db->prepare($query);
        $statement->bindValue(':username', $username);
        $statement->execute();
        $row = $statement->fetch();
        $statement->closeCursor();
        
        if (!$row) return false;
        
        $hash = $row['password'];
        return password_verify($password, $hash);
    }

    public function clientExists($username)
    {
        $query = 'SELECT 1 FROM `client` WHERE username = :username';
        $statement = $this->db->prepare($query);
        $statement->bindValue(':username', $username);
        $statement->execute();
        $result = $statement->fetch();
        $statement->closeCursor();

        return $result >= 1;
    }

    public function addClient($username, $password, $first_name, $last_name, $email)
    {

    }
}