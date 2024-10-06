<?php

require_once $_SERVER["DOCUMENT_ROOT"]. "/Database/db.php";

use Database\Db;

class Financial extends Db
{
    public function getCasesSelect($name = "case_id", $firm_id = null) 
    {
        $query = $this->db->prepare("SELECT * FROM cases WHERE firm_id = ?");
        $query->execute([$firm_id]);
        $results = $query->fetchAll(PDO::FETCH_OBJ);

        $select = '<select name="' . $name . '" class="form-select modal-select select2" id="' . $name . '" style="width:100%">';
        $select .= '<option value="">Kasa Seçiniz</option>';
        foreach ($results as $row) {
            $selected = $row->isDefault == 1 ? "selected" : "";
            $select .= '<option value="' . $row->id . '" ' . $selected . ' >' . $row->case_name . "-" . $row->bank_name . "/" . $row->branch_name . '</option>';
        }
        $select .= '</select>';
        return $select;
    }

    public function getCaseName($id)
    {
        $query = $this->db->prepare("SELECT case_name FROM cases WHERE id = :id");
        $query->execute(array("id" => $id));
        $result = $query->fetch(PDO::FETCH_OBJ);
        if ($result) {
            return $result->case_name;
        } else {
            return "";
        }
    }


    //firma id'ye göre gelir veya gider türlerini getirir
    public function getIncExpTypeSelect($name = "inc_exp_type", $type = 1)
    {
        $firm_id = $_SESSION['firm_id'];
        $query = $this->db->prepare("SELECT * FROM defines WHERE firm_id = ? AND type = ?");
        $query->execute([$firm_id, $type]);
        $results = $query->fetchAll(PDO::FETCH_OBJ);

        $select = '<select name="' . $name . '" class="form-select modal-select select2" id="' . $name . '" style="width:100%">';
        $select .= '<option value="">Tür Seçiniz</option>';
        foreach ($results as $row) {
            $select .= '<option value="' . $row->id . '">' . $row->name . '</option>';
        }
        $select .= '</select>';
        return $select;
    }
}
