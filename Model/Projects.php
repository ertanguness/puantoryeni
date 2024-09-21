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

    // Proje ve firma id'sine göre personelleri getirir
    public function getPersontoProject($project_id, $firm_id)
    {
        $sql = $this->db->prepare("CALL GetPersonsByProjectAndFirm(?, ?)");
        $sql->execute([$project_id, $firm_id]);
        return $sql->fetchAll(PDO::FETCH_OBJ);
    }


    public function getPersonFromProject($project_id)
    {
        $sql = $this->db->prepare("SELECT person_id FROM project_person WHERE project_id = ?");
        $sql->execute([$project_id]);
        $result = $sql->fetchAll(PDO::FETCH_OBJ);
        if ($result) {
            $persons = array_map(function ($item) {
                return empty($item->person_id) ? null : (object) ['id' => $item->person_id];
            }, $result);
            $persons = array_filter($persons); // Remove null values
        }
        return $persons ?? [];
    }

    public function findById($id)
    {
        $sql = $this->db->prepare("SELECT id FROM project_person WHERE project_id = ?");
        $sql->execute([$id]);
        $result = $sql->fetch(PDO::FETCH_ASSOC);
        return $result ? $result['id'] : 0;
    }
}