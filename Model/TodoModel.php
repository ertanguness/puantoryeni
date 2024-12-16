<?php

class Todo extends Model
{
    protected $table = 'todos';
    
    public function __construct()
    {
        parent::__construct($this->table);
    }
}
