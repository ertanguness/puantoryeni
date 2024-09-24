<?php
require_once 'BaseModel.php';

class Bordro extends Model
{
    protected $table = 'maas_gelir_kesinti';

    public function __construct()
    {
        parent::__construct($this->table);
    }

    public function getPersonSalaryAndWageCut($person_id, $start_date, $end_date, $wage_tpe = 2)
    {
        $gun = 'CAST(gun AS UNSIGNED)';
        if ($wage_tpe == 2) {
            $query = $this->db->prepare('
        SELECT 
            (SELECT SUM(tutar) FROM puantaj pu WHERE pu.person = :person_id AND CAST(gun AS UNSIGNED) >= :start_date AND CAST(gun AS UNSIGNED) <= :end_date) AS maas,
            (SELECT SUM(tutar) FROM maas_gelir_kesinti mgk WHERE mgk.person_id = :person_id AND kategori = :gelir AND CAST(gun AS UNSIGNED) >= :start_date AND CAST(gun AS UNSIGNED) <= :end_date) AS gelir,
            (SELECT SUM(tutar) FROM maas_gelir_kesinti mgk WHERE mgk.person_id = :person_id AND kategori = :kesinti  AND CAST(gun AS UNSIGNED) >= :start_date AND CAST(gun AS UNSIGNED) <= :end_date) AS kesinti,
            (SELECT SUM(tutar) FROM maas_gelir_kesinti mgk WHERE mgk.person_id = :person_id AND kategori = :odeme  AND CAST(gun AS UNSIGNED) >= :start_date AND CAST(gun AS UNSIGNED) <= :end_date) AS odeme
    ');
        } else {
            $query = $this->db->prepare('
    SELECT 
         (SELECT SUM(tutar) FROM maas_gelir_kesinti mgk WHERE mgk.person_id = :person_id AND kategori = :maas AND CAST(gun AS UNSIGNED) >= :start_date AND CAST(gun AS UNSIGNED) <= :end_date) AS maas,
        (SELECT SUM(tutar) FROM maas_gelir_kesinti mgk WHERE mgk.person_id = :person_id AND kategori = :gelir AND CAST(gun AS UNSIGNED) >= :start_date AND CAST(gun AS UNSIGNED) <= :end_date) AS gelir,
        (SELECT SUM(tutar) FROM maas_gelir_kesinti mgk WHERE mgk.person_id = :person_id AND kategori = :kesinti AND CAST(gun AS UNSIGNED) >= :start_date AND CAST(gun AS UNSIGNED) <= :end_date) AS kesinti,
        (SELECT SUM(tutar) FROM maas_gelir_kesinti mgk WHERE mgk.person_id = :person_id AND kategori = :odeme AND CAST(gun AS UNSIGNED) >= :start_date AND CAST(gun AS UNSIGNED) <= :end_date) AS odeme
');
        }
        $query->execute([
            'gelir' => 1,
            'kesinti' => 2,
            'odeme' => 3,
            "maas" => 4,
            'wage_type' => $wage_tpe,
            ':person_id' => $person_id,
            ':start_date' => $start_date,
            ':end_date' => $end_date
        ]);
        return $query->fetch(PDO::FETCH_OBJ);
    }

    public function getPersonIncomeExpenseInfo($person_id)
    {
        $sql = $this->db->prepare("SELECT * FROM $this->table WHERE person_id = :person_id");
        $sql->execute([':person_id' => $person_id]);
        return $sql->fetchAll(PDO::FETCH_OBJ);
    }

    // kayıt id'sine göre sorulama yapar
    public function getPersonIncomeExpensePayment($id)
    {
        $sql = $this->db->prepare("SELECT * FROM $this->table WHERE id = :id");
        $sql->execute([':id' => $id]);
        return $sql->fetch(PDO::FETCH_OBJ);
    }

    public function getPersonTotalIncomeExpensePayment($person_id)
    {
        $sql = $this->db->prepare("
            SELECT 
                SUM(CASE WHEN kategori = 1 THEN tutar ELSE 0 END) as total_income,
                SUM(CASE WHEN kategori = 2 THEN tutar ELSE 0 END) as total_expense,
                SUM(CASE WHEN kategori = 3 THEN tutar ELSE 0 END) as total_payment,
                SUM(CASE WHEN kategori = 4 THEN tutar ELSE 0 END) as total_monthly_income
            FROM $this->table 
            WHERE person_id = :person_id
        ");
        $sql->execute([':person_id' => $person_id]);
        return $sql->fetch(PDO::FETCH_OBJ);
    }

    public function isPersonMonthlyIncomeAdded($person_id, $month, $year)
    {
        $sql = $this->db->prepare("SELECT * FROM $this->table WHERE person_id = :person_id AND ay = :month AND yil = :year AND kategori = 4");
        $sql->execute([':person_id' => $person_id, ':month' => $month, ':year' => $year]);
        return $sql->fetch(PDO::FETCH_OBJ);
    }

    public function addPersonMonthlyIncome($person_id, $month, $year, $tutar, $turu)
    {
        $sql = $this->db->prepare("INSERT INTO $this->table SET person_id = :person_id, ay = :month, yil = :year, tutar = :tutar, kategori = 4 , turu = :turu");
        $sql->execute([':person_id' => $person_id, ':month' => $month, ':year' => $year, ':tutar' => $tutar, ':turu' => $turu]);
        return $this->db->lastInsertId();
    }
}
