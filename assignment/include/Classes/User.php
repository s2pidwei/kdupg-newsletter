<?php
class User{
    public $id = null;
    public $name = null;
    public $email = null;
    public $contact = null;
    public $description = null;
    public $date_joined = null;

    public function __construct($data = array()){
        if (isset($data['id'])){
            $this->id =  $data['id'];
        }
        if (isset($data['name'])){
            $this->name = $data['name'];
        }
        if (isset($data['email'])){
            $this->email = $data['email'];
        }
        if (isset($data['contact'])){
            $this->contact = $data['contact'];
        }
        if (isset($data['description'])){
            $this->description = $data['description'];
        }
        if (isset($data['date_joined'])){
            $this->date_joined = $data['date_joined'];
        }
    }

    /*
    public static function getAllUser($count = 1000){
    }*/
    public static function getUserByID($id){
        $con = new PDO(DBhost,DBuser,DBpass);
        $query = "SELECT * FROM user WHERE id=:id";
        $st = $con->prepare($query);
        $st->bindValue(":id", $id, PDO::PARAM_STR);
        $st->execute();
        $row = $st->fetch();
        if ($row){
            return $row;
        }else{
            return false;
        }
    }

    public static function getUserByEmail($email){
        $con = new PDO(DBhost,DBuser,DBpass);
        $query = "SELECT * FROM user WHERE email=:email";
        $st = $con->prepare($query);
        $st->bindValue(":email", $email, PDO::PARAM_STR);
        $st->execute();
        $row = $st->fetch();
        if ($row){
            return new user($row);
        }else
            return false;
    }

    public function insert(){
        $con = new PDO(DBhost,DBuser,DBpass);
        $query = "INSERT INTO user(id, name, email ,contact, date_joined)
        VALUES (:id, :name, :email, :contact, :date_joined)";
        $st = $con->prepare($query);
        $st->bindValue(":id", $this->id, PDO::PARAM_STR);
        $st->bindValue(":name", $this->name, PDO::PARAM_STR);
        $st->bindValue(":email", $this->email, PDO::PARAM_STR);
        $st->bindValue(":contact", $this->contact, PDO::PARAM_STR);
        $st->bindValue(":date_joined", $this->date_joined ,PDO::PARAM_STR);
        $st->execute();
        $con = null;
    }

    public function update(){
        $con = new PDO(DBhost,DBuser,DBpass);
        $query = "UPDATE user SET name=:name, email=:email, contact=:contact, description=:description
        WHERE id=:id";
        $st = $con->prepare($query);
        $st->bindValue(":name", $this->name, PDO::PARAM_STR);
        $st->bindValue(":email", $this->email, PDO::PARAM_STR);
        $st->bindValue(":contact", $this->contact, PDO::PARAM_STR);
        $st->bindValue(":description", $this->description, PDO::PARAM_STR);
        $st->bindValue(":id", $this->id, PDO::PARAM_STR);
        $st->execute() or die('execute() failed: ' . htmlspecialchars($st->error));
        $con = null;
    }

    public function delete(){
        $con = new PDO(DBhost,DBuser,DBpass);
        $query = "DELETE FROM user WHERE id=:id";
        $st = $con->prepare($query);
        $st->bindValue(":id", $this->id,PDO::PARAM_STR);
        if($st->execute()){
            return true;
        }else{
            return false;
        }
        $con = null;
    }
    
    public function getProfilePic(){
        if (file_exists('../upload/profile/'.$this->id.'.png')){
            $pictureLoc = '../upload/profile/'.$this->id.'.png?='.rand(1,32000);
        }else{
            $pictureLoc = '../img/default_icon.png';
        }
        return $pictureLoc;
    }
}
?>