<?php
class Category {
    public $id = null;
    public $type = null;
    
    public function __construct($data = array()){
        if (isset($data['id'])){
            $this->id =  $data['id'];
        }
        if (isset($data['type'])){
            $this->type = $data['type'];
        }
    }
    
    public function insert() {
        $con = new PDO(DBhost,DBuser,DBpass);
        $query = "INSERT INTO category(type)
        VALUES (:type)";
        $st = $con->prepare($query);
        $st->bindValue(":type", $this->type, PDO::PARAM_STR);
        $st->execute();
        $con = null;
    }

    public static function getAllCategory(){
        $con = new PDO(DBhost,DBuser,DBpass);
        $query = "SELECT * FROM category";
        $st = $con->prepare($query);
        $st->execute();
        $row = $st->fetchAll();
        if ($row){
            return $row;
        }else{
            return false;
        }
    }
}
?>