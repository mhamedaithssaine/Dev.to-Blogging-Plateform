<?php

namespace App\Models;
use App\Crud\crud;

class User extends crud {

    private $table = 'users';

    public function __construct(){
        parent::__construct();
    }

    public function selectAllusers(){
        return $this->selectRecords($this->table);
    }

    public function selectusers($id){
        return $this->selectRecords($this->table, '*', 'id = ' . $id);
    }

    public function addusers(array $data){
        return $this->insertRecord($this->table, $data);
    }

    public function updateusers(array $data, int $id){
        return $this->updateRecord($this->table, $data, $id);
    }

    public function deleteusers(int $id){
        return $this->deleteRecord($this->table, $id);
    }

    public function countusers(){
        $result = $this->selectRecords($this->table, 'COUNT(*) as total');
        return $result[0]['total'];
    }
    public static function getTopUsers($limit = 5){
        return parent::getTopUsers($limit);
    }
 
}