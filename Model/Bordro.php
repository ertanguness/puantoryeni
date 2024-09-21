<?php 
require_once "BaseModel.php";

class Bordro extends Model{
    protected $table = "maas_gelir_kesinti";

    public function __construct(){
        parent::__construct($this->table);
    }

    
    public function getPersonSalaryAndWageCut($person_id, $start_date, $end_date)
    {
        $gun = "CAST(gun AS UNSIGNED)";
        $query = $this->db->prepare("
        SELECT 
            (SELECT SUM(tutar) FROM puantaj pu WHERE pu.person = :person_id AND CAST(gun AS UNSIGNED) >= :start_date AND CAST(gun AS UNSIGNED) <= :end_date) AS maas,
            (SELECT SUM(tutar) FROM maas_gelir_kesinti mgk WHERE mgk.person_id = :person_id AND kategori = :gelir AND CAST(gun AS UNSIGNED) >= :start_date AND CAST(gun AS UNSIGNED) <= :end_date) AS gelir,
            (SELECT SUM(tutar) FROM maas_gelir_kesinti mgk WHERE mgk.person_id = :person_id AND kategori = :kesinti AND CAST(gun AS UNSIGNED) >= :start_date AND CAST(gun AS UNSIGNED) <= :end_date) AS kesinti
    ");
        $query->execute([
            "gelir" => 1,
            "kesinti" => 2,
            ':person_id' => $person_id,
            ':start_date' => $start_date,
            ':end_date' => $end_date
        ]);
        return $query->fetch(PDO::FETCH_OBJ);
    }

}