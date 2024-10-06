<?php
require_once 'BaseModel.php';

class Projects extends Model
{
    protected $table = 'projects';

    public function __construct()
    {
        parent::__construct($this->table);
    }

    public function allWithFirm($firm_id)
    {
        $sql = $this->db->prepare('SELECT * FROM projects WHERE company_id = ?');
        $sql->execute([$firm_id]);
        return $sql->fetchAll(PDO::FETCH_OBJ);
    }

    public function addPersontoProject($data)
    {
        $this->table = 'project_person';
        return $this->saveWithAttr($data);
    }

    // Proje ve firma id'sine göre personelleri getirir
    public function getPersontoProject($firm_id,$project_id)
    {
        $sql = $this->db->prepare('SELECT 
                                            p.*, 
                                            (CASE 
                                                WHEN FIND_IN_SET(p.id, (SELECT GROUP_CONCAT(person_id) FROM project_person WHERE project_id = ?)) > 0 THEN 1 
                                                ELSE 0 
                                            END) AS is_added
                                        FROM 
                                            persons p
                                        WHERE 
                                            p.firm_id = ? AND
                                            p.wage_type = 2;');
        $sql->execute([$project_id,$firm_id]);
        return $sql->fetchAll(PDO::FETCH_OBJ);
    }

    public function getPersonFromProject($project_id)
    {
        $sql = $this->db->prepare('SELECT *
                                            FROM persons
                                            WHERE wage_type = 2
                                            AND (
                                                FIND_IN_SET(id, (SELECT person_id FROM project_person WHERE project_id = ?))
                                            );');
        $sql->execute([$project_id]);
        return $sql->fetchAll(PDO::FETCH_OBJ);
    }

    public function getPersonIdByFromProjectCurrentMonth($project_id, $last_day)
    {
        $sql = $this->db->prepare('SELECT id
                                            FROM persons
                                            WHERE wage_type = 2
                                            AND (
                                                FIND_IN_SET(id, (SELECT person_id FROM project_person WHERE project_id = ?))
                                            )
                                            AND STR_TO_DATE(job_start_date, "%d.%m.%Y") <= ?;');
        $sql->execute([$project_id, $last_day]);
        return $sql->fetchAll(PDO::FETCH_OBJ);
    }

    public function findById($id)
    {
        $sql = $this->db->prepare('SELECT id FROM project_person WHERE project_id = ?');
        $sql->execute([$id]);
        $result = $sql->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['id'] : 0;
    }

    function saveProgressPayment($data)
    {
        $this->table = 'project_gelir_gider';
        return $this->saveWithAttr($data);
    }

}
