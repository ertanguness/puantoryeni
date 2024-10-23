
<?php

require_once 'BaseModel.php';

class ProjectIncomeExpense extends Model
{
    protected $table = 'project_gelir_gider';

    public function __construct()
    {
        parent::__construct($this->table);
    }

    public function getAllIncomeExpenseByFirm($firm_id)
    {
        $sql = $this->db->prepare("SELECT * FROM $this->table WHERE firm_id = :firm_id  ORDER BY id DESC");
        $sql->execute(['firm_id' => $firm_id]);
        return $sql->fetchAll(PDO::FETCH_OBJ);
    }

    public function getAllIncomeExpenseByProject($project_id)
    {
        $sql = $this->db->prepare("SELECT * FROM $this->table WHERE project_id = :project_id and project_id != 0 ORDER BY id DESC");
        $sql->execute(['project_id' => $project_id]);
        return $sql->fetchAll(PDO::FETCH_OBJ);
    }


    function sumAllIncomeExpense($project_id)
    {
        $sql = $this->db->prepare('SELECT 
                                                COALESCE(SUM(CASE WHEN kategori = 6 THEN tutar END), 0) AS hakedis,
                                                COALESCE(SUM(CASE WHEN kategori = 2 THEN tutar END), 0) AS kesinti,
                                                COALESCE(SUM(CASE WHEN kategori = 3 THEN tutar END), 0) AS odeme
                                            FROM project_gelir_gider 
                                            WHERE project_id = :project_id and project_id != 0;');
        $sql->execute(['project_id' => $project_id]);
        return $sql->fetch(PDO::FETCH_OBJ);
    }

    function getBalance($project_id)
    {
        $result = $this->sumAllIncomeExpense($project_id);
        return $result->hakedis - $result->kesinti - $result->odeme;
    }
}
