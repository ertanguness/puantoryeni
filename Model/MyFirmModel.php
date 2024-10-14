<?php
require_once "BaseModel.php";

class MyFirmModel extends Model
{
    protected $table = 'myfirms';
    protected $db;
    public function __construct()
    {
        parent::__construct($this->table);
    }


    //all myfirms
    public function allByUser($user_id)
    {
        $sql = $this->db->prepare("SELECT * FROM $this->table WHERE user_id = :user_id");
        $sql->execute(['user_id' => $user_id]);
        return $sql->fetchAll(PDO::FETCH_OBJ);
    }

    public function getAuthorizedMyFirmsByEmail($email)
    {
        $sql = $this->db->prepare("SELECT DISTINCT id,firm_name FROM (
                                        SELECT id, user_id, firm_name, '' AS email FROM myfirms 
                                        WHERE email = :email
                                        UNION ALL
                                    SELECT firm_id,u.id,firm_name,u.email FROM users u
                                    LEFT JOIN myfirms mf ON mf.id = u.firm_id
                                    WHERE u.email = :email
                                    ) AS authorize_firm;");
        $sql->execute(['email' => $email]);
        return $sql->fetchAll(PDO::FETCH_OBJ);
    }
  
}