<?php

require_once "BaseModel.php";

class Puantaj extends Model
{
    protected $table = "puantaj";

    public function __construct()
    {
        parent::__construct($this->table);
    }


    public function getPuantajSaati($id)
    {
        $sql = $this->db->prepare("SELECT PuantajSaati FROM puantajturu WHERE id = ?");
        $sql->execute([$id]);
        return $sql->fetch(PDO::FETCH_OBJ)->PuantajSaati ?? 0;
    }

    //Personelin puantaj tablosundaki çalışmaları getirilir
    public function getPuantajInfoByPerson($person_id)
    {
        $sql = $this->db->prepare("SELECT * FROM puantaj WHERE person = ?");
        $sql->execute([$person_id]);
        return $sql->fetchAll(PDO::FETCH_OBJ);
    }


    //Puantaj id'sine göre puantaj bilgilerini getiri
    public function getPuantajTuruById($id)
    {
        $sql = $this->db->prepare("SELECT * FROM puantajturu WHERE id = ?");
        $sql->execute([$id]);
        return $sql->fetch(PDO::FETCH_OBJ);
    }
    //puantaj gununden puantaj tablosundaki id'yi çekiyoruz
    public function getPuantajId($person_id, $date)
    {
        $sql = $this->db->prepare("SELECT id FROM puantaj WHERE person = ? and gun = ?");
        $sql->execute([$person_id, $date]);
        return $sql->fetch(PDO::FETCH_OBJ)->id ?? 0;
    }

    //Puantaj tablosundan kayıtlı puantaj turu id'sini bul
    public function getPuantajTuruId($person_id, $date)
    {
        $sql = $this->db->prepare("SELECT puantaj_id FROM puantaj WHERE person = ? and gun = ?");
        $sql->execute([$person_id, $date]);
        return $sql->fetch(PDO::FETCH_OBJ)->puantaj_id ?? 0;
    }

    //Puantaj tablosundan kayıtlı proje id'sini bul
    public function getPuantajProjectId($person_id, $date)
    {
        $sql = $this->db->prepare("SELECT project_id FROM puantaj WHERE person = ? and gun = ?");
        $sql->execute([$person_id, $date]);
        return $sql->fetch(PDO::FETCH_OBJ)->project_id ?? 0;
    }

    //Puantaj tablosundan personel id'sine göre toplam tutarları çek
    public function getPuantajIncomeByPerson($person_id)
    {
        $sql = $this->db->prepare("SELECT SUM(tutar) as total_income FROM puantaj WHERE person = ?");
        $sql->execute([$person_id]);
        return $sql->fetch(PDO::FETCH_OBJ) ?? 0;
    }
    
}
