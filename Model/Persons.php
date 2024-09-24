<?php

require_once 'BaseModel.php';

class Persons extends Model
{
    protected $table = 'persons';

    public function __construct()
    {
        parent::__construct($this->table);
    }

    public function getByFirm($firm_id)
    {
        $query = $this->db->prepare('SELECT * FROM persons WHERE firm_id = ?');
        $query->execute([$firm_id]);
        return $query->fetchAll(PDO::FETCH_OBJ);
    }
    public function getPersonIdByFirm($firm_id)
    {
        $query = $this->db->prepare('SELECT id FROM persons WHERE firm_id = ?');
        $query->execute([$firm_id]);
        return $query->fetchAll(PDO::FETCH_OBJ);
    }
    public function getPersonIdByFirmBlueCollar($firm_id)
    {
        $query = $this->db->prepare('SELECT id FROM persons WHERE firm_id = ? AND wage_type = ?');
        $query->execute([$firm_id,2]);
        return $query->fetchAll(PDO::FETCH_OBJ);
    }



    public function getPersonById($person_id,$field)
    {
        $query = $this->db->prepare("SELECT {$field} FROM persons WHERE id = ?");
        $query->execute([$person_id]);
        return $query->fetch(PDO::FETCH_OBJ);
    }
    public function getDailyWages($person_id)
    {
        $query = $this->db->prepare('SELECT daily_wages FROM persons WHERE id = ?');
        $query->execute([$person_id]);
        return $query->fetch(PDO::FETCH_OBJ);
    }

    public function getPersonSalary($person_id, $start_date, $end_date)
    {
        $query = $this->db->prepare('SELECT SUM(TUTAR) as tutar FROM puantaj WHERE person=? AND GUN >= ? AND GUN <= ?');
        $query->execute([$person_id, $start_date, $end_date]);
        return $query->fetch(PDO::FETCH_OBJ)->tutar;
    }


}