<?php
class model_Holder extends Model {
    public function __set($name, $value){
        $this->$name = $value;
    }
}