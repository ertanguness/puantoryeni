<?php 
require_once "BaseModel.php";

class SupportsMessagesModel extends Model{
    protected $table = 'mbeyazil_panel.supports_message';
    public function __construct(){
        parent::__construct($this->table);
    }

    public function getMessagesByTicketId($ticket_id){
        $sql = $this->db->prepare("SELECT * FROM $this->table WHERE ticket_id = :ticket_id ORDER BY id DESC");
        $sql->execute([
            'ticket_id' => $ticket_id
        ]);
        return $sql->fetchAll(PDO::FETCH_OBJ);
    }

}
