<?php
require_once 'BaseModel.php';
require_once 'Bordro.php';
require_once 'Puantaj.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/App/Helper/helper.php';

use App\Helper\Helper;

class Bordro extends Model
{
    protected $table = 'maas_gelir_kesinti';
    protected $sql_table = "sqlmaas_gelir_kesinti";

    public function __construct()
    {
        parent::__construct($this->table);
    }
    //getAll() metodu ile tüm kayıtları çeker

    public function getAll()
    {
        $sql = $this->db->query("SELECT * FROM $this->sql_table");
        return $sql->fetchAll(PDO::FETCH_OBJ);
    }

    //get() metodu ile id'ye göre kayıt çeker
    public function getPersonWorkTransactions($id)
    {
        $sql = $this->db->prepare("SELECT * FROM $this->sql_table WHERE person_id = :id");
        $sql->execute([':id' => $id]);
        return $sql->fetchAll(PDO::FETCH_OBJ);
    }

    public function getPersonSalaryAndWageCut($person_id, $start_date, $end_date, $wage_tpe = 2)
    {
        $query = $this->db->prepare('
    SELECT 
        (SELECT SUM(tutar) FROM '. $this->sql_table. ' mgk WHERE mgk.person_id = :person_id AND (kategori = :gelir or kategori = :maas) AND CAST(gun AS UNSIGNED) >= :start_date AND CAST(gun AS UNSIGNED) <= :end_date) AS gelir,
        (SELECT SUM(tutar) FROM '. $this->sql_table. ' mgk WHERE mgk.person_id = :person_id AND kategori = :kesinti  AND CAST(gun AS UNSIGNED) >= :start_date AND CAST(gun AS UNSIGNED) <= :end_date) AS kesinti,
        (SELECT SUM(tutar) FROM '. $this->sql_table. ' mgk WHERE mgk.person_id = :person_id AND kategori = :odeme  AND CAST(gun AS UNSIGNED) >= :start_date AND CAST(gun AS UNSIGNED) <= :end_date) AS odeme
');
    
    $query->execute([
        'gelir' => 1,
        'kesinti' => 2,
        'odeme' => 3,
        'maas' => 4,
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
            FROM $this->sql_table 
            WHERE person_id = :person_id
        ");
        $sql->execute([':person_id' => $person_id]);
        return $sql->fetch(PDO::FETCH_OBJ);
    }
    // public function getPersonTotalIncomeExpensePayment($person_id)
    // {
    //     $sql = $this->db->prepare("
    //         SELECT 
    //             SUM(CASE WHEN kategori = 1 THEN tutar ELSE 0 END) as total_income,
    //             SUM(CASE WHEN kategori = 2 THEN tutar ELSE 0 END) as total_expense,
    //             SUM(CASE WHEN kategori = 3 THEN tutar ELSE 0 END) as total_payment,
    //             SUM(CASE WHEN kategori = 4 THEN tutar ELSE 0 END) as total_monthly_income
    //         FROM $this->table 
    //         WHERE person_id = :person_id
    //     ");
    //     $sql->execute([':person_id' => $person_id]);
    //     return $sql->fetch(PDO::FETCH_OBJ);
    // }

    public function isPersonMonthlyIncomeAdded($person_id, $month, $year)
    {
        $sql = $this->db->prepare("SELECT * FROM $this->table WHERE person_id = :person_id AND ay = :month AND yil = :year AND kategori = 4");
        $sql->execute([':person_id' => $person_id, ':month' => $month, ':year' => $year]);
        return $sql->fetch(PDO::FETCH_OBJ);
    }

    public function addPersonMonthlyIncome($person_id, $month, $year, $tutar, $turu)
    {
        $gun = sprintf('%2d%02d15', $year, $month);
        $sql = $this->db->prepare("INSERT INTO $this->table SET person_id = :person_id, gun = :gun, ay = :month, yil = :year, tutar = :tutar, kategori = 4 , turu = :turu");
        $sql->execute([':person_id' => $person_id, ':gun' => $gun, ':month' => $month, ':year' => $year, ':tutar' => $tutar, ':turu' => $turu]);
        return $this->db->lastInsertId();
    }

    public static function getIncomeExpenseData($person_id)
    {
        $payment = new Bordro();
        $puantaj = new Puantaj();

        $income_expense = $payment->getPersonTotalIncomeExpensePayment($person_id);
        $income_puantaj = $puantaj->getPuantajIncomeByPerson($person_id);

        $total_income_puantaj =$income_expense->total_monthly_income + $income_expense->total_income + $income_puantaj->total_income;

        $balance = Helper::formattedMoney( $total_income_puantaj - $income_expense->total_payment - $income_expense->total_expense);
        $income_expense->total_income = Helper::formattedMoney($total_income_puantaj ?? 0);
        $income_expense->total_monthly_income = Helper::formattedMoney($total_monthly_income ?? 0);
        $income_expense->total_payment = Helper::formattedMoney($income_expense->total_payment ?? 0);
        $income_expense->total_expense = Helper::formattedMoney($income_expense->total_expense ?? 0);
        $income_expense->balance = $balance;

        return $income_expense;
    }
}
