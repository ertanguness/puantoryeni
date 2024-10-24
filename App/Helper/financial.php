<?php
require_once ROOT . "/Database/db.php";
require_once ROOT . "/Model/Cases.php";

use Database\Db;
class Financial extends Db
{


    protected $caseObj;
   

    public function __construct()
    {
        parent::__construct();
        $this->caseObj = new Cases();
    }


    //firma id'ye göre gelir veya gider türlerini getirir
    public function getIncExpTypeSelect($name = "inc_exp_type", $type_id = 1)
    {
        $firm_id = $_SESSION['firm_id'];
        $query = $this->db->prepare("SELECT * FROM defines WHERE firm_id = ? AND type_id = ?");
        $query->execute([$firm_id, $type_id]);
        $results = $query->fetchAll(PDO::FETCH_OBJ);

        $select = "<select name=\"{$name}\" class=\"form-select modal-select select2\" id=\"{$name}\" style=\"width:100%\">";
        $select .= '<option value="">Tür Seçiniz</option>';
        foreach ($results as $row) {
            $select .= "<option value=\"{$row->id}\">{$row->name}</option>";
        }
        $select .= '</select>';
        return $select;
    }

    //CaseSelect
    public function getCasesSelectByFirm($name = "case_id", $case_id = "")
    {
       
        //case_id boş ise firmanın varsayılan kasa id'sini al
        if (empty($case_id)) {
            $case_id = $this->caseObj->getDefaultCaseIdByFirm();
        }
    
        $cases = $this->caseObj->allCaseWithFirmId();
        $select = "<select name='".$name."' class=\"form-control select2\" id='".$name."' style='width:100%'>";
        $select .= "<option value=''>Kasa Seçiniz</option>";
        
        foreach ($cases as $case) {
            $selectedAttr = $case_id == $case->id ? 'selected' : '';
            $select .= "<option value=\"{$case->id}\" {$selectedAttr}>{$case->case_name}-{$case->bank_name}/{$case->branch_name}</option>";
        }
        $select .= '</select>';
        return $select;
    }
}
