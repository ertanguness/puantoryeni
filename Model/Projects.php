<?php
require_once "BaseModel.php";

class Projects extends Model
{
    protected $table = "projects";

    public function __construct()
    {
        parent::__construct($this->table);
    }

    public function allWithFirm($firm_id)
    {
        $sql = $this->db->prepare("SELECT * FROM projects WHERE company_id = ?");
        $sql->execute([$firm_id]);
        return $sql->fetchAll(PDO::FETCH_OBJ);
    }
    public function addPersontoProject($data)
    {
        $this->table =  "project_person";
        return $this->saveWithAttr($data);
    }
    public function getPersontoProject($project_id, $firm_id)
    {
        // $sql = $this->db->prepare("SELECT 
        //                                 p.id, 
        //                                 p.full_name, 
        //                                 p.job, 
        //                                 COALESCE(pp.state, 0) AS state,
        //                                 CASE 
        //                                     WHEN p.job_end_date IS NULL  THEN 'Aktif'
        //                                     ELSE 'Pasif' 
        //                                     END AS job_status 
        //                             FROM 
        //                                 persons p
        //                             LEFT JOIN 
        //                                 project_person pp ON FIND_IN_SET(p.id, pp.person_id) > 0 AND pp.project_id = ?
        //                             WHERE 
        //                                 p.firm_id = ?");
        $sql = $this->db->prepare("CALL GetPersonsByProjectAndFirm(?, ?)");
        $sql->execute([$project_id, $firm_id]);
        return $sql->fetchAll(PDO::FETCH_OBJ);
    }

    public function findById($id)
    {
        $sql = $this->db->prepare("SELECT id FROM project_person WHERE project_id = ?");
        $sql->execute([$id]);
        $result = $sql->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['id'] : 0;
    }
}
