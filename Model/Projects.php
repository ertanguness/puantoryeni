<?php
require_once 'BaseModel.php';

class Projects extends Model
{
    protected $table = 'projects';

    public function __construct()
    {
        parent::__construct($this->table);
    }

    public function getProjectsByFirm($firm_id)
    {
        $sql = $this->db->prepare('SELECT * FROM projects WHERE firm_id = ?');
        $sql->execute([$firm_id]);
        return $sql->fetchAll(PDO::FETCH_OBJ);
    }

    public function addPersontoProject($data)
    {
        $this->table = 'project_person';
        return $this->saveWithAttr($data);
    }

    // Proje ve firma id'sine göre personelleri getirir
    public function getPersontoProject($firm_id, $project_id)
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
        $sql->execute([$project_id, $firm_id]);
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


    //Personel, gelen projede çalışıyor mu kontrol et
    //örnek: project_id = 1, person_id alanı = 275,278,279
    public function isExistPersonInProject($project_id, $person_id)
    {
        $sql = $this->db->prepare('SELECT COUNT(*) as total
                                            FROM project_person
                                            WHERE project_id = ?
                                            AND FIND_IN_SET(?, person_id);');
        $sql->execute([$project_id, $person_id]);
        return $sql->fetch(PDO::FETCH_OBJ)->total;
    }



    //Personelin kayıtlı olduğu projeleri getir
    public function getPersonProjects($person_id)
    {
        $sql = $this->db->prepare('SELECT project_id
                                            FROM project_person
                                            WHERE FIND_IN_SET(?, person_id);');
        $sql->execute([$person_id]);
        return $sql->fetchAll(PDO::FETCH_OBJ);
    }

    //Personelin kayıtlı olduğu dizileri getir
    public function getPersonInArray($person_id)
    {
        $sql = $this->db->prepare('SELECT person_id
                                            FROM project_person
                                            WHERE FIND_IN_SET(?, person_id);');
        $sql->execute([$person_id]);
        return $sql->fetchAll(PDO::FETCH_OBJ);
    }

    public function getPersonIdByFromProjectCurrentMonth($project_id, $last_day)
    {
        // $sql = $this->db->prepare('SELECT id
        //                                     FROM persons
        //                                     WHERE wage_type = 2
        //                                     AND (
        //                                         FIND_IN_SET(id, (SELECT person_id FROM project_person WHERE project_id = ?))
        //                                     )
        //                                     AND STR_TO_DATE(job_start_date, "%d.%m.%Y") <= ?;');
        // $sql->execute([$project_id, $last_day]);
        // return $sql->fetchAll(PDO::FETCH_OBJ);

        $sql = $this->db->prepare('SELECT id
                        FROM persons
                        WHERE wage_type = 2
                        AND FIND_IN_SET(id, (
                            SELECT GROUP_CONCAT(person_id) 
                            FROM project_person 
                            WHERE project_id = ?
                        ))
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


    //projede kayıtlı çalışma var mı kontrol et
    public function isExistPuantaj($id)
    {
        $sql = $this->db->prepare("SELECT COUNT(*) as total FROM puantaj WHERE project_id = ?");
        $sql->execute([$id]);
        return $sql->fetch(PDO::FETCH_OBJ)->total;
    }

    //Personeli projelere kaydet
    public function savePersonProjects($person_id, $projects)
    {
        $this->table = 'project_person';

        //Personelin kayıtlı olduğu projeleri getir
        $person_list = $this->getPersonInArray($person_id);
        foreach ($person_list as $person) {
            //Personelin kayıtlı olduğu projelerden personel id'sini sil
            $this->removePersonIdFromList($person->person_id, $person_id);

        }

        //$this->delete(['person_id' => $person_id]);
        foreach ($projects as $project_id) {
            //Eğer personel projede kayıtlı ise ekleme
            if ($this->isExistPersonInProject($project_id, $person_id) > 0)
                continue;





            //eğer proje kayıtlı ise person_id alanına ekleme yapar
            if ($this->findById($project_id) > 0) {
                $sql = $this->db->prepare('UPDATE project_person 
                        SET person_id = IF(person_id = "", ?, CONCAT(person_id, ",", ?)) 
                        WHERE project_id = ?');
                $sql->execute([$person_id, $person_id, $project_id]);
                continue;
            }
            $this->saveWithAttr([
                'person_id' => $person_id,
                'project_id' => $project_id,
                "description" => "aadd"
            ]);
        }
    }
    public static function removePersonIdFromList($person_id_list, $person_id_to_remove)
    {
        // Listeyi diziye dönüştür
        $person_ids = explode(',', $person_id_list);

        // Diziden istenen person_id'yi çıkar
        $person_ids = array_filter($person_ids, function ($id) use ($person_id_to_remove) {
            return $id != $person_id_to_remove;
        });

        // Diziyi tekrar virgülle ayrılmış bir listeye dönüştür
        return implode(',', $person_ids);
    }

}
