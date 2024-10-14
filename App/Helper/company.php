<?php

require_once 'Database/db.php';

use Database\Db;

class CompanyHelper extends Db
{
    public function companySelect($name = 'companies', $id = null)
    {
        $query = $this->db->prepare('SELECT * FROM companies');  // Tüm sütunları seç
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_OBJ);  // Tüm sonuçları al

        $select = '<select name="' . $name . '" class="form-select select2" id="' . $name . '" style="width:100%">';
        $select .= '<option value="">Firma Seçiniz</option>';
        foreach ($results as $row) {  // $results üzerinde döngü
            $selected = $id == $row->id ? ' selected' : '';  // Eğer id varsa seçili yap
            $select .= '<option value="' . $row->id . '"' . $selected . '>' . $row->company_name . '</option>';  // $row->title yerine $row->name kullanıldı
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

    public function myCompanySelect($name = 'companies', $id = null)
    {


        
        $email = $_SESSION['user']->email;
        $query = $this->db->prepare('SELECT DISTINCT id,firm_name FROM (
                                                SELECT id, user_id, firm_name, "" AS email FROM myfirms 
                                                WHERE email = :email
                                                UNION ALL
                                            SELECT firm_id,u.id,firm_name,u.email FROM users u
                                            LEFT JOIN myfirms mf ON mf.id = u.firm_id
                                            WHERE u.email = :email
                                            ) AS authorize_firm;');  // Tüm sütunları seç
        $query->execute(['email' => $email]);
        $results = $query->fetchAll(PDO::FETCH_OBJ);  // Tüm sonuçları al

        $select = '<select name="' . $name . '" class="form-select select2" id="' . $name . '" style="min-width:200px">';
        $select .= '<option value="">Firma Seçiniz</option>';
        foreach ($results as $row) {  // $results üzerinde döngü
            $selected = $id == $row->id ? ' selected' : '';  // Eğer id varsa seçili yap
            $select .= '<option value="' . $row->id . '"' . $selected . '>' . $row->firm_name . '</option>';  // $row->title yerine $row->name kullanıldı
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
