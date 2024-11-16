<?php
!defined("ROOT") ? define("ROOT", $_SERVER['DOCUMENT_ROOT']): null;
require_once "BaseModel.php";
require_once "Cases.php";
require_once ROOT ."/App/Helper/helper.php";
require_once ROOT."/App/Helper/security.php";

use App\Helper\Helper;
use App\Helper\Security;
class CaseTransactions extends Model
{
    protected $table = "case_transactions";
    protected $caseObj;

    public function __construct()
    {
        parent::__construct($this->table);
        $this->caseObj = new Cases();
    }

    public function allByCase($case_id)
    {
        $sql = $this->db->prepare("SELECT * FROM $this->table WHERE case_id = ?");
        $sql->execute([$case_id]);
        return $sql->fetchAll(PDO::FETCH_OBJ);
    }

    //firmanın kasalarının işlemlerini getirir
    public function allTransactionByFirm($firm_id)
    {
        $cases = $this->caseObj->allCaseWithFirmId();
        $case_ids = array_map(function ($case) {
            return $case->id;
        }, $cases);

        if (empty($case_ids)) {
            return []; // Veya uygun bir boş sonuç döndürme işlemi
        }

        $case_ids = implode(",", $case_ids);
        $sql = $this->db->prepare("SELECT * FROM $this->table WHERE case_id IN ($case_ids)");
        $sql->execute();
        return $sql->fetchAll(PDO::FETCH_OBJ);
    }

    //All transactions by case id
    public function allTransactionByCase($case_id)
    {
        //case_id boş ise firmanın varsayılan kasa id'sini al
        if (empty($case_id)) {
            $case_id = $this->caseObj->getDefaultCaseIdByFirm();
        }

        $sql = $this->db->prepare("SELECT * FROM $this->table WHERE case_id = ?");
        $sql->execute([$case_id]);
        return $sql->fetchAll(PDO::FETCH_OBJ);
    }

    public function deleteCaseTransactions($case_id)
    {
        $sql = $this->db->prepare("DELETE FROM $this->table WHERE case_id = ?");
        $sql->execute([$case_id]);
    }

    //Kasanın tüm hareketlerini getir
    public function allTransactionByCaseId($case_id)
    {
        $sql = $this->db->prepare("SELECT * FROM $this->table WHERE case_id = ?");
        $sql->execute([$case_id]);
        return $sql->fetchAll(PDO::FETCH_OBJ);
    }

    //Kasanın gelir-gider bilgilerini getir

    public function sumAllIncomeExpense($case_id)
    {
        $sql = $this->db->prepare("SELECT 
                                                SUM(CASE WHEN type_id = 1 THEN amount ELSE 0 END) AS income,
                                                SUM(CASE WHEN type_id = 2 THEN amount ELSE 0 END) AS expense
                                            FROM $this->table WHERE case_id = ?");
        $sql->execute([$case_id]);
        return $sql->fetch(PDO::FETCH_OBJ);
    }


    public function getCaseBalance($case_id)
    {
        $query = "
            SELECT 
                COALESCE(SUM(CASE WHEN type_id = 1 THEN amount ELSE 0 END),0) AS total_income,
                COALESCE(SUM(CASE WHEN type_id = 2 THEN amount ELSE 0 END),0) AS total_expense,
                COALESCE(SUM(CASE WHEN type_id = 1 THEN amount ELSE 0 END) - SUM(CASE WHEN type_id = 2 THEN amount ELSE 0 END),0) AS balance
            FROM 
                case_transactions
            WHERE 
                case_id = :case_id
        ";

        $stmt = $this->db->prepare($query);
        $stmt->execute(["case_id" => $case_id]);

        return $stmt->fetch(PDO::FETCH_OBJ);
    }


    //Kasalar arası transfer işlemi
    public function transfer($from_case, $to_case, $amount, $description)
    {
       
        $amount = Helper::formattedMoneyToNumber($amount);
        $description = Security::escape($description);

        //aktarılacak kasa boş gelirse
        if (empty($from_case) || empty($to_case)) {
            return ["status" => "error", "message" => "Transfer yapılacak kasayı seçiniz!"];
        }
        //Transfer işlemi için gerekli kontroller
        if ($from_case == $to_case) {
            return ["status" => "error", "message" => "Aynı kasaya transfer yapılamaz."];
        }

        if ($amount <= 0) {
            return ["status" => "error", "message" => "Geçersiz miktar."];
        }

        $from_case_balance = $this->getCaseBalance($from_case)->balance;

        if ($from_case_balance < $amount) {
            return ["status" => "error", "message" => "Yetersiz bakiye.<br> Yapabileceğiniz maksimum transfer miktarı: <br>" . Helper::formattedMoney($from_case_balance)];
        }

        $data = [
            "date" => date("Y-m-d H:i:s"),
            "case_id" => $from_case,
            "type_id" => 2,
            "sub_type" => 3,
            "amount" => Helper::formattedMoneyToNumber($amount),
            "description" => $description ?? "Virman",
            "created_at" => date("Y-m-d H:i:s")
        ];
        $this->saveWithAttr($data);

        $data = [
            "date" => date("Y-m-d H:i:s"),
            "case_id" => $to_case,
            "type_id" => 1,
            "sub_type" => 3,
            "amount" => Helper::formattedMoneyToNumber($amount),
            "description" => $description ?? "Virman",
            "created_at" => date("Y-m-d H:i:s")
        ];
        $this->saveWithAttr($data);

        return ["status" => "success", "message" => "Transfer işlemi başarılı."];
    }

}
