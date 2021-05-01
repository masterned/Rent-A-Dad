<?php

class ClientHasDadTable
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function setAppointment($client_id, $dad_id, $start_time, $end_time)
    {
        $query = 'INSERT INTO `client_has_dad` VALUES (:client_id, :dad_id, :start_time, :end_time)';
        $statement = $this->db->prepare($query);
        $statement->bindValue(':client_id', $client_id);
        $statement->bindValue(':dad_id', $dad_id);
        $statement->bindValue(':start_time', $start_time);
        $statement->bindValue(':end_time', $end_time);
        $statement->execute();
        $statement->closeCursor();
    }
}
