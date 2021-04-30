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
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $query = 'INSERT INTO `client` (`username`, `password`, `first_name`, `last_name`, `email`)
                  VALUES (:username, :password, :first_name, :last_name, :email)';
        $statement = $this->db->prepare($query);
        $statement->bindValue(':username', $username);
        $statement->bindValue(':password', $hash);
        $statement->bindValue(':first_name', $first_name);
        $statement->bindValue(':last_name', $last_name);
        $statement->bindValue(':email', $email);
        $statement->execute();
        $statement->closeCursor();
    }

    public function getFirstNameViaUsername($username)
    {
        $query = 'SELECT `first_name` FROM `client` WHERE `username` = :username';
        $statement = $this->db->prepare($query);
        $statement->bindValue(':username', $username);
        $found_match = $statement->execute();
        $first_name = $found_match ? $statement->fetch()['first_name'] : 'bud';
        $statement->closeCursor();

        return $first_name;
    }
}