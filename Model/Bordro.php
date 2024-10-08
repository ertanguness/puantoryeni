<?php
require_once 'BaseModel.php';
require_once 'Bordro.php';
require_once 'Puantaj.php';
require_once 'Persons.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/App/Helper/helper.php';
require_once $_SERVER['DOCUMENT_ROOT'] . '/App/Helper/date.php';

use App\Helper\Date;
use App\Helper\Helper;

class Bordro extends Model
{
    protected $table = 'maas_gelir_kesinti';
    protected $sql_table = 'sqlmaas_gelir_kesinti';

    public function __construct()
    {
        parent::__construct($this->table);
    }
    // getAll() metodu ile tüm kayıtları çeker

    public function getAll()
    {
        $sql = $this->db->query("SELECT * FROM $this->sql_table");
        return $sql->fetchAll(PDO::FETCH_OBJ);
    }

    // get() metodu ile id'ye göre kayıt çeker
    public function getPersonWorkTransactions($id)
    {
        $sql = $this->db->prepare("SELECT * FROM $this->sql_table WHERE person_id = :id AND tutar > 0");
        $sql->execute([':id' => $id]);
        return $sql->fetchAll(PDO::FETCH_OBJ);
    }

    public function getPersonSalaryAndWageCut($person_id, $start_date, $end_date, $wage_tpe = 2)
    {
        $query = $this->db->prepare('
    SELECT 
        (SELECT SUM(tutar) FROM ' . $this->sql_table . ' mgk WHERE mgk.person_id = :person_id AND (kategori = :gelir or kategori = :maas) AND CAST(gun AS UNSIGNED) >= :start_date AND CAST(gun AS UNSIGNED) <= :end_date) AS gelir,
        (SELECT SUM(tutar) FROM ' . $this->sql_table . ' mgk WHERE mgk.person_id = :person_id AND kategori = :kesinti  AND CAST(gun AS UNSIGNED) >= :start_date AND CAST(gun AS UNSIGNED) <= :end_date) AS kesinti,
        (SELECT SUM(tutar) FROM ' . $this->sql_table . ' mgk WHERE mgk.person_id = :person_id AND kategori = :odeme  AND CAST(gun AS UNSIGNED) >= :start_date AND CAST(gun AS UNSIGNED) <= :end_date) AS odeme
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


    //Devreden Bakiye Hesaplama
    public function getCarryOverBalance($person_id, $start_date)
    {
        $query = $this->db->prepare('SELECT (
                        -COALESCE((
                            SELECT SUM(tutar) FROM maas_gelir_kesinti mgkk
                            WHERE mgkk.person_id = :person_id
                            AND mgkk.kategori IN (2, 3)
                            AND mgkk.gun < :start_date  -- Kesinti Toplamı
                        ), 0) +
                        COALESCE((
                            SELECT SUM(tutar) FROM maas_gelir_kesinti mgkg
                            WHERE mgkg.person_id = :person_id
                            AND mgkg.kategori IN (1, 4)
                            AND mgkg.gun < :start_date  -- Maaş veya diğer ödemeler toplamı
                        ), 0) +
                        COALESCE((
                            SELECT SUM(tutar) FROM puantaj p
                            WHERE p.person = :person_id  
                            AND p.gun < :start_date  -- puantaj çalışmaları toplamı
                        ), 0)
                    ) AS toplam;');

        $query->execute(
            [
                ':person_id' => $person_id,
                ':start_date' => $start_date
            ]
        );
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

    // public function getPersonTotalIncomeExpensePayment($person_id)
    // {
    //     $sql = $this->db->prepare("
    //         SELECT 
    //             SUM(CASE WHEN (kategori = 1) THEN tutar ELSE 0 END) as total_income,
    //             SUM(CASE WHEN kategori = 2 THEN tutar ELSE 0 END) as total_expense,
    //             SUM(CASE WHEN kategori = 3 THEN tutar ELSE 0 END) as total_payment,
    //             SUM(CASE WHEN kategori = 4 THEN tutar ELSE 0 END) as total_monthly_income
    //         FROM $this->sql_table 
    //         WHERE person_id = :person_id
    //     ");
    //     $sql->execute([':person_id' => $person_id]);
    //     return $sql->fetch(PDO::FETCH_OBJ);
    // }


    // Personelin toplam gelir, gider ve ödeme bilgilerini döndürür
    function sumAllIncomeExpense($person_id)
    {
        $sql = $this->db->prepare('SELECT 
                                                COALESCE(SUM(CASE WHEN (kategori = 1 or kategori = 4) THEN tutar END), 0) AS total_income,
                                                COALESCE(SUM(CASE WHEN kategori = 2 THEN tutar END), 0) AS total_expense,
                                                COALESCE(SUM(CASE WHEN kategori = 3 THEN tutar END), 0) AS total_payment
                                            FROM sqlmaas_gelir_kesinti 
                                            WHERE person_id = :person_id;');
        $sql->execute(['person_id' => $person_id]);
        return $sql->fetch(PDO::FETCH_OBJ);
    }

    //Personelin Bakiyesini döndürür
    function getBalance($person_id)
    {
        $result = $this->sumAllIncomeExpense($person_id);
        return $result->total_income - $result->total_expense - $result->total_payment;
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
        $person = new Persons();
        $gun = sprintf('%2d%02d15', $year, $month);
        $job_start_date = Date::ymd($person->getPersonById($person_id, 'job_start_date')->job_start_date);
        // ayın son gününe kadar olan gün sayısı
        $last_day = Date::lastDay($month, $year);

        // O ayın gün sayısı
        $month_day = Date::getDay($last_day);

        // personelin işe başladığı tarihten itibaren geçen gün sayısı
        $work_day = $last_day - $job_start_date + 1;

        // personelin işe başladığı tarihten itibaren geçen gün sayısı ayın gün sayısından küçükse,
        // o ayın maaşını günlük olarak hesapla
        if ($work_day < $month_day) {
            $tutar = $tutar * $work_day / $month_day;
            $description = "$work_day günlük Maaş";
        } else {
            $description = 'Aylık Maaş';
        }

        $sql = $this->db->prepare("INSERT INTO $this->table SET person_id = :person_id, gun = :gun, ay = :month, yil = :year, tutar = :tutar, kategori = 4 , turu = :turu , aciklama = :aciklama");
        $sql->execute([':person_id' => $person_id, ':gun' => $gun, ':month' => $month, ':year' => $year, ':tutar' => $tutar, ':turu' => $turu, ':aciklama' => $description]);
        return $this->db->lastInsertId();
    }

    public static function getIncomeExpenseData($person_id)
    {
        $payment = new Bordro();
        $puantaj = new Puantaj();

        $income_expense = $payment->sumAllIncomeExpense($person_id);
        $income_puantaj = $puantaj->getPuantajIncomeByPerson($person_id);

        $total_income_puantaj = $income_expense->total_income + $income_puantaj->total_income;

        $balance = Helper::formattedMoney($total_income_puantaj - $income_expense->total_payment - $income_expense->total_expense);
        $income_expense->total_income = Helper::formattedMoney($total_income_puantaj ?? 0);
        $income_expense->total_payment = Helper::formattedMoney($income_expense->total_payment ?? 0);
        $income_expense->total_expense = Helper::formattedMoney($income_expense->total_expense ?? 0);
        $income_expense->balance = $balance;

        return $income_expense;
    }
}
