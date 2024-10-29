<?php

require_once "BaseModel.php";
require_once "Cases.php";

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
        $sql =  $this->db->prepare("SELECT * FROM $this->table WHERE case_id = ?");
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

    
}
