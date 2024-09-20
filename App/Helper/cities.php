<?php

require_once "Database/db.php";

use Database\Db;

class Cities extends Db
{
    public function citySelect($name = "city", $id = null)
    {
        try {
            $query = $this->db->prepare("SELECT * FROM il"); // Tüm sütunları seç
            $query->execute();
            $results = $query->fetchAll(PDO::FETCH_OBJ); // Tüm sonuçları al

            $select = '<select name="' . $name . '" class="form-select select2" id="' . $name . '" style="width:100%">';
            $select .= '<option value="">Şehir Seçiniz</option>';
            foreach ($results as $row) { // $results üzerinde döngü
                $selected = $id == $row->id ? ' selected' : ''; // Eğer id varsa seçili yap
                $select .= '<option value="' . $row->id . '"'  . $selected . '>' . $row->city_name . '</option>'; // $row->title yerine $row->name kullanıldı
            }
            $select .= '</select>';
            return $select;
        } catch (PDOException $e) {
            return "Veritabanı hatası: " . $e->getMessage();
        }
    }

    public function getCityName($id)
    {
        try {
            $query = $this->db->prepare("SELECT city_name FROM il WHERE id = :id");
            $query->execute(array("id" => $id));
            $result = $query->fetch(PDO::FETCH_OBJ);
            if ($result) {
                return $result->city_name;
            } else {
                return "";
            }
        } catch (PDOException $e) {
            return "Veritabanı hatası: " . $e->getMessage();
        }
    }

    public function getTownName($id){
        

    }
}
