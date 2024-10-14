<?php 

require_once "BaseModel.php";

class SettingsModel extends Model{
    protected $table = 'settings';
    public function __construct()
    {
        parent::__construct($this->table);
    }

    public function getSettings($set_name)
    {
        $firm_id = $_SESSION['user']->firm_id;
        $sql = $this->db->prepare("SELECT * FROM $this->table WHERE firm_id = ? AND set_name = ?");
        $sql->execute([$firm_id,$set_name]);
        return $sql->fetch(PDO::FETCH_OBJ);
    }

      //Program açıldığında tamamlanmamış görevleri getir veya getirme
      public function updateShowCompletedMissions($firm_id, $visible)
      {
          $sql =  $this->db->prepare("UPDATE $this->table SET set_value = ? WHERE firm_id = ? and set_name = ?");
          return $sql->execute([$visible, $firm_id, "completed_tasks_visible"]);
      }
}