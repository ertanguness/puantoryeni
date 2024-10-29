<?php
!defined('ROOT') ? define('ROOT', $_SERVER['DOCUMENT_ROOT']) : null;
require_once "BaseModel.php";
require_once "Persons.php";
require_once ROOT . "/App/Helper/date.php";

use App\Helper\Date;


class Puantaj extends Model
{
    protected $table = "puantaj";
    //private $persons;

    public function __construct()
    {
        parent::__construct($this->table);
        //Person sınıfını buraya dahil et
        //$this->persons = new Persons();
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
      
        $sql = $this->db->prepare("SELECT * FROM puantaj WHERE person = ? ");
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
        return $sql->fetch(PDO::FETCH_OBJ)->puantaj_id ?? '';
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

    //Personelin göreve başlama tarihinden önceki ve işten ayrılma tarihinden sonraki tüm puantajları sil
    public function deletePastAttendanceRecords($person_id, $job_start_date, $job_end_date)
    {

        $job_start_date = Date::Ymd($job_start_date);
        $job_end_date = Date::Ymd($job_end_date);
        $sql = $this->db->prepare("DELETE FROM $this->table WHERE person = ? and (gun < ? or gun > ?)");
        $sql->execute([$person_id, $job_start_date, $job_end_date]);
    }

}
