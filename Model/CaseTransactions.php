<?php

require_once "BaseModel.php";


class CaseTransactions extends Model
{
    protected $table = "case_transactions";
    public function __construct()
    {
        parent::__construct($this->table);
    }

    public function allByCase($case_id)
    {
        $sql =  $this->db->prepare("SELECT * FROM $this->table WHERE case_id = ?");
        $sql->execute([$case_id]);
        return $sql->fetchAll(PDO::FETCH_OBJ);
    }
}
