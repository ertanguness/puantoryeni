<?php
!defined('ROOT') ? define('ROOT', $_SERVER['DOCUMENT_ROOT']) : null;

require_once ROOT ."/Database/db.php";
require_once ROOT ."/Model/Projects.php";

use App\Helper\Security;
use Database\Db;

class ProjectHelper extends Db
{

    protected $Projects ;
    public function __construct()
    {
        parent::__construct();
        $this->Projects = new Projects();


    }
    public function getProjectSelect($name = "projects", $project_id = null )
    {
        // $query = $this->db->prepare("SELECT * FROM projects where firm_id = ? "); // Tüm sütunları seç
        // $query->execute([$_SESSION["firm_id"]]); // Sorguyu çalıştır
        // $results = $query->fetchAll(PDO::FETCH_OBJ); // Tüm sonuçları al

        $results = $this->Projects->getProjectsByFirm($_SESSION["firm_id"]);

        $select = '<select name="' . $name . '" class="form-select select2" id="' . $name . '" style="width:100%">';
        $select .= '<option value="0">Proje Seçiniz</option>';
        foreach ($results as $row) { // $results üzerinde döngü
            $selected = $project_id == $row->id ? ' selected' : ''; // Eğer id varsa seçili yap
            $select .= '<option value="' . ($row->id) . '"'  . $selected . '>' . $row->project_name . '</option>'; // $row->title yerine $row->name kullanıldı
        }
        $select .= '</select>';
        return $select;
    }

    //Çoklu proje seçimi
    public function getProjectSelectMultiple($name = "projects", $project_ids = null )
    {
       //kayıtlı projeler varsa diziye çevir
        if($project_ids != null){
            $project_ids = explode(",",$project_ids);
        }else{
            $project_ids = [];
        }
        
        $results = $this->Projects->getProjectsByFirm($_SESSION["firm_id"]);

        $select = '<select name="' . $name . '[]" class="form-select select2" id="' . $name . '" style="width:100%" multiple>';
        $select .= '<option value="0" disabled>Projeleri Seçiniz</option>';
        foreach ($results as $row) { // $results üzerinde döngü
            $selected = in_array($row->id, $project_ids) ? ' selected' : ''; // Eğer id varsa seçili yap
            $select .= '<option value="' . ($row->id) . '"'  . $selected . '>' . $row->project_name . '</option>'; // $row->title yerine $row->name kullanıldı
        }
        $select .= '</select>';
        return $select;
    }

    public function getProjectSelectByType($name = "projects", $project_id = null, $type = null )
    {
        $query = $this->db->prepare("SELECT * FROM projects where firm_id = ? and type = ?"); // Tüm sütunları seç
        $query->execute([$_SESSION["firm_id"],$type]); // Sorguyu çalıştır
        $results = $query->fetchAll(PDO::FETCH_OBJ); // Tüm sonuçları al

        $select = '<select name="' . $name . '" class="form-select select2" id="' . $name . '" style="width:100%">';
        $select .= '<option value="0">Proje Seçiniz</option>';
        foreach ($results as $row) { // $results üzerinde döngü
            $selected = $project_id == $row->id ? ' selected' : ''; // Eğer id varsa seçili yap
            $select .= '<option value="' . Security::encrypt($row->id) . '"'  . $selected . '>' . $row->project_name . '</option>'; // $row->title yerine $row->name kullanıldı
        }
        $select .= '</select>';
        return $select;
    }

    public function getProjectName($id)
    {
    
            $query = $this->db->prepare("SELECT project_name FROM projects WHERE id = :id");
            $query->execute(array("id" => $id));
            $result = $query->fetch(PDO::FETCH_OBJ);
            if ($result) {
                return $result->project_name;
            } else {
                return "Proje Yok";
            }
        
    }

        
    //Projenin durumu ile ilgili select 
    public function projectStatusSelect($name = 'status', $selected = null)
    {
        $query = $this->db->prepare('SELECT * FROM defines WHERE firm_id = ? and type_id = 5');
        $query->execute([$_SESSION['firm_id']]);
        $results = $query->fetchAll(PDO::FETCH_OBJ);
        $select = '<select id="' . $name . '" name="' . $name . '" class="select2 form-control" style="width:100%">';
        $select .= '<option value="">Durum Seçiniz</option>';
        foreach ($results as $row) {
            $selectedAttr = $selected == $row->id ? 'selected' : '';
            $select .= "<option value='$row->id' $selectedAttr>$row->name</option>";
        }
        $select .= '</select>';
        return $select;
    }

}
