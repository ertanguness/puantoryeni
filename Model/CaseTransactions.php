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
        $cases = $this->caseObj->allWithFirmId($firm_id);
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
}
