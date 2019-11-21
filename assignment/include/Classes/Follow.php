<?php
class Follow{
    public $user = null;
    public $follower = null;

    public function __construct($data = array()){
        if (isset($data['user_id'])){
            $this->user =  $data['user_id'];
        }
        if (isset($data['user_id2'])){
            $this->follower = $data['user_id2'];
        }
    }

    public static function getFollowerByID($id){
        $list = array();
        $con = new PDO(DBhost,DBuser,DBpass);
        $query = "SELECT * FROM subscription WHERE user_id=:user_id";
        $st = $con->prepare($query);
        $st->bindValue(":user_id", $id, PDO::PARAM_STR);
        $st->execute();
        while ($row = $st->fetch()) {
            $list[] = new Follow($row);
        }
        return $list;    
    }

    public static function getFollower($user1,$user2){
        $list = array();
        $con = new PDO(DBhost,DBuser,DBpass);
        $query = "SELECT * FROM subscription WHERE user_id=:user_id AND user_id2=:user_id2";
        $st = $con->prepare($query);
        $st->bindValue(":user_id", $user1, PDO::PARAM_STR);
        $st->bindValue(":user_id2", $user2, PDO::PARAM_STR);
        $st->execute();
        $row = $st->fetch();
        $con = null;
        if($row){
            return true;
        }else{
            return false;
        }
    }

    public function insert(){
        $con = new PDO(DBhost,DBuser,DBpass);
        $query = "INSERT INTO subscription(user_id, user_id2)
        VALUES (:user_id, :user_id2)";
        $st = $con->prepare($query);
        $st->bindValue(":user_id", $this->user, PDO::PARAM_STR);
        $st->bindValue(":user_id2", $this->follower, PDO::PARAM_STR);
        if($st->execute()){
            return true;
        }else{
            return false;
        }
        $con = null;
    }

    public function delete(){
        $con = new PDO(DBhost,DBuser,DBpass);
        $query = "DELETE FROM subscription WHERE user_id=:user_id AND user_id2=:user_id2";
        $st = $con->prepare($query);
        $st->bindValue(":user_id", $this->user, PDO::PARAM_STR);
        $st->bindValue(":user_id2", $this->follower, PDO::PARAM_STR);
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