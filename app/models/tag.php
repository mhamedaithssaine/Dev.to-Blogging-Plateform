<?php

namespace App\Models;

class Tag {
    private $name ;

    public function __construct(string $name){

            $this->name=$name;        
    }
   

    public function getName(){
        return $this->name;
    }
    public function setName($name){
        $this->name=$name;
    }

}


