<?php
require_once 'model/SkillTable.php';

class DadTable
{
    private $db;

    public function __construct($db)
    {
        $this->db = $db;
    }

    public function getAllDads()
    {
        $query = 'SELECT * FROM `dad`';
        $statement = $this->db->prepare($query);
        $statement->execute();
        $dads = $statement->fetchAll(PDO::FETCH_ASSOC);
        $statement->closeCursor();

        $this->linkSkills($dads);

        return $dads;
    }

    private function linkSkills(&$dads)
    {
        $skill_table = new SkillTable($this->db);

        for ($i = 0; $i < count($dads); $i++) {
            $skills = $skill_table->dadHasSkills($dads[$i]['id']);
            $dads[$i]['skills'] = &$skills;
        }
    }
}
