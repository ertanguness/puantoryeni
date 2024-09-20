<?php
require_once "Database/Db.php";

use Database\Db;


class UserHelper extends Db
{

    public function userSelect($name = "users")
    {
        $query = $this->db->prepare("SELECT * FROM users"); // Tüm sütunları seç
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_OBJ); // Tüm sonuçları al

        $id = str_replace('[]', ' ', $name);
        $id = $id . uniqid();
        $select = '<select name="' . $name . '" class="form-select select2" id="' . $id . '" style="width:100%">';
        $select .= '<option value="">Personel Seçiniz</option>';
        foreach ($results as $row) { // $results üzerinde döngü
            $select .= '<option value="' . $row->id . '">' . $row->full_name . '</option>'; // $row->title yerine $row->name kullanıldı
        }
        $select .= '</select>';
        return $select;
    }


    public function userSelectwithJob($name = "userwithJob", $selectedId = null)
    {
        $query = $this->db->prepare("SELECT * FROM users "); // Tüm sütunları seç
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_OBJ); // Tüm sonuçları al

        // Benzersiz bir ID oluşturmak için uniqid() kullanılıyor.
        $selectId = $name . "_" . uniqid();
        $select = '<select name="' . $name . '" class="form-select select2" id="' . $selectId . '" style="width:100%">';
        $select .= '<option value="">Personel Seçiniz</option>';
        foreach ($results as $row) { // $results üzerinde döngü
            // Kullanıcıdan gelen $selectedId ile karşılaştırma yapılıyor.
            $selected = $selectedId == $row->id ? ' selected' : ''; // Eğer id varsa seçili yap
            $select .= '<option value="' . $row->id . '"'  . $selected . '>' . $row->full_name . " - " . $row->job . '</option>';
        }
        $select .= '</select>';
        return $select;
    }

    public function userRoles($name = "user_roles", $id = null)
    {
        $query = $this->db->prepare("SELECT * FROM userroles"); // Tüm sütunları seç
        $query->execute();
        $results = $query->fetchAll(PDO::FETCH_OBJ); // Tüm sonuçları al

        // Benzersiz bir ID oluşturmak için uniqid() kullanılıyor.
        $selectId = $name . "_" . uniqid();
        $select = '<select name="' . $name . '" class="form-select select2" id="' . $selectId . '" style="width:100%">';
        $select .= '<option value="">Rol Seçiniz</option>';
        foreach ($results as $row) { // $results üzerinde döngü
            // Kullanıcıdan gelen $selectedId ile karşılaştırma yapılıyor.
            $selected = $id == $row->id ? ' selected' : ''; // Eğer id varsa seçili yap
            $select .= '<option value="' . $row->id . '"'  . $selected . '>' . $row->roleName . '</option>';
        }
        $select .= '</select>';
        return $select;
    }
}
