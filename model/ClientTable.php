<?php

class ClientTable
{
    private $db;

    public function __construct($db)
    {
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
        $statement->execute();
        $result = $statement->fetch(PDO::FETCH_ASSOC);
        $first_name = $result ? $result['first_name'] : 'bud';
        $statement->closeCursor();

        return $first_name;
    }
    
    public function getIDViaUsername($username)
    {
        $query = 'SELECT `id` FROM `client` WHERE `username` = :username';
        $statement = $this->db->prepare($query);
        $statement->bindValue(':username', $username);
        $statement->execute();
        $result = $statement->fetchColumn();
        $statement->closeCursor();
    
        return $result;
    }

    public function getClientDads($client_id)
    {
        $query = 'SELECT dad.first_name, dad.last_name, client_has_dad.start_time, client_has_dad.end_time
                  FROM dad
                  INNER JOIN client_has_dad
                  ON client_has_dad.dad_id = dad.id
                  WHERE client_id = :client_id';
        $statement = $this->db->prepare($query);
        $statement->bindValue(':client_id', $client_id);
        $statement->execute();
        $result = $statement->fetchAll(PDO::FETCH_ASSOC);
        $statement->closeCursor();

        return $result;
    }
}
