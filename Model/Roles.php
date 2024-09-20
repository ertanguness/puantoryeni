<?php


require_once "BaseModel.php";

class Roles extends Model
{
    protected $table = "userroles";
    public function __construct()
    {
        parent::__construct($this->table);
    }
}
