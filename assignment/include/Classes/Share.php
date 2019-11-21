<?php
class Share{
    public $id = null;
    public $user = null; //new User()
    public $article = null; //new Article()
    public $reply = null;
    public $date_shared = null;

    public function __construct($data = array()){
        if (isset($data['id'])) {
            $this->id = (int) $data['id'];
        }
        if (isset($data['user_id'])) {
            $this->user = $data['user_id'];
        }
        if (isset($data['article_id'])) {
            $this->article = $data['article_id'];
        }
        if (isset($data['reply'])) {
            $this->reply = $data['reply'];
        }
        if (isset($data['date_shared'])) {
            $this->date_shared = $data['date_shared'];
        }
    }

    public static function getShareByID($id){
        $con = new PDO(DBhost,DBuser,DBpass);
        $query = "SELECT * FROM share WHERE id=:id";
        $st = $con->prepare($query);
        $st->bindValue(":id", $id, PDO::PARAM_INT);
        $st->execute();
        $row = $st->fetch();
        if ($row){
            return $row;
        }
    }

    public static function deleteShareByArticle($articleId){
        $list = array();
        $con = new PDO(DBhost,DBuser,DBpass);
        $query = "DELETE FROM share WHERE article_id=:article_Id";
        $st = $con->prepare($query);
        $st->bindValue(":article_Id", $articleId, PDO::PARAM_INT);
        return $st->execute();
    }

    public static function getAllShare($count = 10){
        $list = array();
        $con = new PDO(DBhost,DBuser,DBpass);
        $query = "SELECT * FROM share 
        ORDER BY date_shared DESC LIMIT :numRows";
        $st = $con->prepare($query);
        $st->bindValue(":numRows", $count, PDO::PARAM_INT);
        $st->execute();
        while ($row = $st->fetch()) {
            $share = new Share($row);
            $list[] = $share;
        }
        $con = null;
        return $list;
    }

    public function insert(){
        $con = new PDO(DBhost,DBuser,DBpass);
        $query = "INSERT INTO share (user_id,article_id,reply,date_shared)
        VALUES (:user_id,:article_id,:reply,:date_shared)";
        
        $st = $con->prepare($query);
        $st->bindValue(":user_id", $this->user, PDO::PARAM_STR);
        $st->bindValue(":article_id", $this->article, PDO::PARAM_INT);
        $st->bindValue(":reply", $this->reply, PDO::PARAM_STR);
        $st->bindValue(":date_shared", $this->date_shared, PDO::PARAM_STR);
        $st->execute();
        if(!$st){
            return false;
        }else{
            return true;
        }
        $con = null;
    }

    public function update(){
        $con = new PDO(DBhost,DBuser,DBpass);
        $query = "UPDATE share SET reply=:reply
        WHERE id=:id";
        $st = $con->prepare($query);
        $st->bindValue(":reply", $this->reply, PDO::PARAM_STR);
        $st->bindValue(":id", $this->id, PDO::PARAM_INT);
        $st->execute();
        $con = null;
    }

    public function delete(){
        $con = new PDO(DBhost,DBuser,DBpass);
        $query = "DELETE FROM share WHERE id=:id";
        $st = $con->prepare($query);
        $st->bindValue(":id", $this->id,PDO::PARAM_INT);
        $st->execute();
        if(!$st){
            return false;
        }else{
            return true;
        }
        $con = null;
    }
}
?>