<?php

require_once 'Database/db.php';
require_once 'Model/MyFirmModel.php';
require_once "App\Helper\security.php";

use Database\Db;
use App\Helper\Security;

class CompanyHelper extends Db
{
    protected $table = 'companies';
    protected $MyFirmModel = null;
    public function __construct()
    {
        parent::__construct();
        $this->MyFirmModel = new MyFirmModel();

    }
    public function companySelect($name = 'companies', $id = null)
    {
        $query = $this->db->prepare('SELECT * FROM companies');  // Tüm sütunları seç
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_OBJ);  // Tüm sonuçları al

        $select = '<select name="' . $name . '" class="form-select select2" id="' . $name . '" style="width:100%">';
        $select .= '<option value="">Firma Seçiniz</option>';
        foreach ($results as $row) {  // $results üzerinde döngü
            $selected = $id == $row->id ? ' selected' : '';  // Eğer id varsa seçili yap
            $select .= '<option value="' . Security::encrypt($row->id) . '"' . $selected . '>' . $row->company_name . '</option>';  // $row->title yerine $row->name kullanıldı
        }
        $select .= '</select>';
        return $select;
    }

    public function getCompanyName($id)
    {
        $query = $this->db->prepare('SELECT company_name FROM companies WHERE id = :id');
        $query->execute(array('id' => $id));
        $result = $query->fetch(PDO::FETCH_OBJ);
        if ($result) {
            return $result->company;
        } else {
            return '';
        }
    }

    public function myCompanySelect($name = 'companies', $id = null, $disabled = null)
    {



        $results = $this->MyFirmModel->getMyFirmByUserId();
        $select = '<select name="' . $name . '" class="form-select select2" id="' . $name . '" style="min-width:200px;width:100%" '. $disabled . '>';
        $select .= '<option value="">Firma Seçiniz</option>';
        foreach ($results as $row) {  // $results üzerinde döngü
            $selected = $id == $row->id ? ' selected' : '';  // Eğer id varsa seçili yap
            $select .= '<option value="' . Security::encrypt($row->id) . '"' . $selected . '>' . $row->firm_name . '</option>';  // $row->title yerine $row->name kullanıldı
        }
        $select .= '</select>';
        return $select;
    }

    public function getFirmName($id)
    {
        $query = $this->db->prepare('SELECT firm_name FROM myfirms WHERE id = :id');
        $query->execute(array('id' => $id));
        $result = $query->fetch(PDO::FETCH_OBJ);
        if ($result) {
            return $result->firm_name;
        } else {
            return 'Bilinmiyor';
        }
    }
}
