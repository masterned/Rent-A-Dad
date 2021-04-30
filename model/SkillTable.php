<?php

class SkillTable
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function dadHasSkills($dad_id)
    {
        $query = 'SELECT `skill`.`name`, `skill`.`description`, `dad_has_skill`.`proficiency`
                  FROM `dad_has_skill`
                  INNER JOIN `skill`
                  ON `skill`.`id` = `dad_has_skill`.`skill_id`
                  WHERE `dad_has_skill`.`dad_id` = :dad_id';
        $statement = $this->db->prepare($query);
        $statement->bindValue(':dad_id', $dad_id);
        $statement->execute();
        $skills = $statement->fetchAll(PDO::FETCH_ASSOC);
        $statement->closeCursor();

        return $skills;
    }
}
