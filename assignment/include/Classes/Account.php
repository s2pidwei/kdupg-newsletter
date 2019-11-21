<?php
class Account{
    public $id = null;
    public $pass = null;
    public $type = null;

    public function __construct($data = array()){
        if (isset($data['id'])){
            $this->id =  $data['id'];
        }
        if (isset($data['pass'])){
            $this->pass = $data['pass'];
        }
        if (isset($data['type'])){
            $this->type = $data['type'];
        }
    }

    public static function getAccByID($id){
        $con = new PDO(DBhost,DBuser,DBpass);
        $query = "SELECT * FROM accounts WHERE id = :id";
        $st = $con->prepare($query);
        $st->bindValue(":id", $id, PDO::PARAM_STR);
        $st->execute();
        $row = $st->fetch();
        $con = null;
        if ($row){
            return new Account($row);
        }
        else{
            return false;
        }      
    }

    public function insert(){
        $con = new PDO(DBhost,DBuser,DBpass);
        $query = "INSERT INTO accounts(id, pass, type)
        VALUES (:id, :pass, :type)";
        $st = $con->prepare($query);
        $st->bindValue(":id", $this->id, PDO::PARAM_STR);
        $st->bindValue(":pass", $this->pass, PDO::PARAM_STR);
        $st->bindValue(":type", $this->type, PDO::PARAM_INT);
        $st->execute();
        $con = null;
    }

    public function update(){
        $con = new PDO(DBhost,DBuser,DBpass);
        $query = "UPDATE accounts SET pass=:pass, type=:type
        WHERE id=:id";
        $st = $con->prepare($query);
        $st->bindValue(":pass", $this->pass, PDO::PARAM_STR);
        $st->bindValue(":type", $this->type, PDO::PARAM_INT);
        $st->bindValue(":id", $this->id,PDO::PARAM_STR);
        if($st->execute()){
            return true;
        }else{
            return false;
        }
        $con = null;
    }

    public function delete(){
        $con = new PDO(DBhost,DBuser,DBpass);
        $query = "DELETE FROM accounts WHERE id=:id";
        $st = $con->prepare($query);
        $st->bindValue(":id", $this->id, PDO::PARAM_STR);
        if($st->execute()){
            return true;
        }else{
            return false;
        }
        $con = null;
    }
    
    public function getPendingUsers(){
        $list = array();
        $con = new PDO(DBhost,DBuser,DBpass);
        $query = "SELECT id FROM accounts WHERE type=1";
        $st = $con->prepare($query);
        $st->execute();
        while ($row = $st->fetch()) {
            $list[] = $row;
        }
        return $list;
    }
}
?>