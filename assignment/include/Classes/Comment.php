<?php
class Comment{
    public $id = null;
    public $article = null; //new article()
    public $user = null; // new User()
    public $comment = null;
    public $date_posted = null;

    public function __construct($data = array()){
        if (isset($data['id'])) {
            $this->id = (int) $data['id'];
        }
        if (isset($data['article_id'])) {
            $this->article = $data['article_id'];
        }
        if (isset($data['user_id'])) {
            $this->user = $data['user_id'];
        }
        if (isset($data['comment'])) {
            $this->comment = $data['comment'];
        }
        if (isset($data['date_posted'])) {
            $this->date_posted = $data['date_posted'];
        }
    }

    public static function getCommentById($id){
        $con = new PDO(DBhost,DBuser,DBpass);
        $query = "SELECT * FROM comments WHERE id=:id ORDER BY date_posted DESC" ;
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

    public static function getAllComment($count = 10,$articleid){
        $list = array();
        $con = new PDO(DBhost,DBuser,DBpass);
        $query = "SELECT * FROM comments WHERE article_id=:article_id 
        ORDER BY date_posted DESC LIMIT :numRows";
        $st = $con->prepare($query);
        $st->bindValue(":article_id", $articleid, PDO::PARAM_INT);
        $st->bindValue(":numRows", $count, PDO::PARAM_INT);
        $st->execute();
        while ($row = $st->fetch()) {
            $comment = new Comment($row);
            $list[] = $comment;
        }
        $con = null;
        return $list;
    }

    public function insert(){
        $con = new PDO(DBhost,DBuser,DBpass);
        $query = "INSERT INTO comments (article_id,user_id,comment,date_posted)
        VALUES (:article_id,:user_id,:comment,:date_posted)";
        $st = $con->prepare($query);
        $st->bindValue(":article_id", $this->article, PDO::PARAM_INT);
        $st->bindValue(":user_id", $this->user, PDO::PARAM_STR);
        $st->bindValue(":comment", $this->comment, PDO::PARAM_STR);
        $st->bindValue(":date_posted", date('Y-m-d H:i:s'), PDO::PARAM_STR);
        if($st->execute()){
            return true;
        }else{
            return false;
        }
        $con = null;
    }

    public function update(){
        $con = new PDO(DBhost,DBuser,DBpass);
        $query = "UPDATE comments SET comment=:comment
        WHERE id=:id";
        $st = $con->prepare($query);
        $st->bindValue(":comment", $this->comment, PDO::PARAM_STR);
        $st->bindValue(":upvote", $this->upvote, PDO::PARAM_INT);
        $st->bindValue(":id", $this->id, PDO::PARAM_INT);
        $st->execute();
        $con = null;
    }

    public function delete(){
        $con = new PDO(DBhost,DBuser,DBpass);
        $query = "DELETE FROM comments WHERE id=:id";
        $st = $con->prepare($query);
        $st->bindValue(":id", $this->id,PDO::PARAM_INT);
        $st->execute();
        $con = null;
    }
}
?>