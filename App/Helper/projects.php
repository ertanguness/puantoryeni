<?php

require_once "Database/db.php";

use Database\Db;

class ProjectHelper extends Db
{

    public function getProjectSelect($name = "projects", $project_id = null)
    {
        $query = $this->db->prepare("SELECT * FROM projects where company_id = ?"); // Tüm sütunları seç
        $query->execute([$_SESSION["firm_id"]]);
        $results = $query->fetchAll(PDO::FETCH_OBJ); // Tüm sonuçları al

        $select = '<select name="' . $name . '" class="form-select select2" id="' . $name . '" style="width:100%">';
        $select .= '<option value="">Proje Seçiniz</option>';
        foreach ($results as $row) { // $results üzerinde döngü
            $selected = $project_id == $row->id ? ' selected' : ''; // Eğer id varsa seçili yap
            $select .= '<option value="' . $row->id . '"'  . $selected . '>' . $row->project_name . '</option>'; // $row->title yerine $row->name kullanıldı
        }
        $select .= '</select>';
        return $select;
    }

    public function getProjectName($id)
    {
        try {
            $query = $this->db->prepare("SELECT project_name FROM projects WHERE id = :id");
            $query->execute(array("id" => $id));
            $result = $query->fetch(PDO::FETCH_OBJ);
            if ($result) {
                return $result->project_name;
            } else {
                return "";
            }
        } catch (PDOException $e) {
            return "Veritabanı hatası: " . $e->getMessage();
        }
    }
}
