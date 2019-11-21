<?php
class Article{
    public $id = null;
    public $title = null;
    public $content = null;
    public $post_by = null;
    public $date_posted = null;
    public $type = null;
    public $status = null;
    
    public function __construct($data = array()){
        if (isset($data['id'])) {
            $this->id = (int) $data['id'];
        }
        if (isset($data['title'])) {
            $this->title = $data['title'];
        }
        if (isset($data['content'])) {
            $this->content = $data['content'];
        }
        if (isset($data['post_by'])) {
            $this->post_by = $data['post_by'];
        }
        if (isset($data['date_posted'])) {
            $this->date_posted = $data['date_posted'];
        }
        if (isset($data['type'])) {
            $this->type = $data['type'];
        }
        if (isset($data['status'])) {
            $this->status = $data['status'];
        }
    }

    public static function getArticleByID($id){
        $con = new PDO(DBhost,DBuser,DBpass);
        $query = "SELECT * FROM article WHERE id = :id";
	    $st = $con->prepare($query);
        $st->bindValue(":id", $id, PDO::PARAM_INT);
        $st->execute();
        $row = $st->fetch();
        if ($row){
            return $row;
        }
    }

    public static function getArticleByUser($post_by){
        $list = array();
        $con = new PDO(DBhost,DBuser,DBpass);
        $query = "SELECT * FROM article WHERE post_by=:post_by";
	    $st = $con->prepare($query);
        $st->bindValue(":post_by", $post_by, PDO::PARAM_STR);
        $st->execute();
        while ($row = $st->fetch()) {
            $article = new Article($row);
            $list[] = $article;
        }
        return $list;
    }
    
    public static function getFollowedArticle($post_by){
        $row = array();
        $con = new PDO(DBhost,DBuser,DBpass);
        $query = "SELECT * FROM article WHERE post_by=:post_by 
        ORDER BY date_posted DESC LIMIT 1";
	    $st = $con->prepare($query);
        $st->bindValue(":post_by", $post_by, PDO::PARAM_STR);
        $st->execute();
        $row = $st->fetch();
        if ($row){
            return $row;
        }
    }
    
    public static function getApprovedArticles($post_by){
        $list = array();
        $con = new PDO(DBhost,DBuser,DBpass);
        $query = "SELECT * FROM article WHERE post_by=:post_by AND
        status=1 ORDER BY date_posted DESC";
	    $st = $con->prepare($query);
        $st->bindValue(":post_by", $post_by, PDO::PARAM_STR);
        $st->execute();
        while ($row = $st->fetch()) {
            $article = new Article($row);
            $list[] = $article;
        }
        return $list;
    }

    public static function getAllArticle($count = 100){
        $con = new PDO(DBhost,DBuser,DBpass);
        $query = "SELECT * FROM article WHERE status=1 ORDER BY date_posted DESC 
        LIMIT :numRows";
        $st = $con->prepare($query);
        $st->bindValue(":numRows", $count, PDO::PARAM_INT);
        $st->execute();
        while ($row = $st->fetch()) {
            $article = new Article($row);
            $list[] = $article;
        }
        $con = null;
        return $list;
    }
    
    public function getPendingArticles($count = 10){
        $list = array();
        $con = new PDO(DBhost,DBuser,DBpass);
        $query = "SELECT * FROM article WHERE status=0 ORDER BY date_posted ASC 
        LIMIT :numRows";
        $st = $con->prepare($query);
        $st->bindValue(":numRows", $count, PDO::PARAM_INT);
        $st->execute();
        while ($row = $st->fetch()) {
            $article = new Article($row);
            $list[] = $article;
        }
        $con = null;
        return $list;
    }
    
    public function searchArticles($content, $type){
        $list = array();
        $con = new PDO(DBhost,DBuser,DBpass);
        $content = '%' . $content . '%';
        $type = '%' . $type . '%';
        $query = "SELECT * FROM `article` WHERE status=1 
        AND (title LIKE (:content) OR content LIKE (:content)) 
        AND type LIKE (:type)
        ORDER BY date_posted DESC
        LIMIT :numRows";
        $st = $con->prepare($query);
        $st->bindValue(":content", $content, PDO::PARAM_STR);
        $st->bindValue(":type", $type, PDO::PARAM_STR);
        $st->bindValue(":numRows", 20, PDO::PARAM_INT);
        $st->execute();
        while ($row = $st->fetch()) {
            $article = new Article($row);
            $list[] = $article;
        }
        $con = null;
        return $list;
    }
    
    //Add new article
    public function insert(){
        $con = new PDO(DBhost,DBuser,DBpass);
        $query = "INSERT INTO article (title, content, post_by, date_posted,type,status)
        VALUES (:title, :content, :post_by , :date_posted, :type, :status)";
        $st = $con->prepare($query);
        $st->bindValue(":title", $this->title, PDO::PARAM_STR);
        $st->bindValue(":content", $this->content, PDO::PARAM_STR);
        $st->bindValue(":post_by", $this->post_by, PDO::PARAM_STR);
        $st->bindValue(":type", $this->type, PDO::PARAM_STR);
        $st->bindValue(":status", $this->status, PDO::PARAM_INT);
        $st->bindValue(":date_posted", date('Y-m-d H:i:s'), PDO::PARAM_STR);
        if($st->execute()){
            $this->id = $con->lastInsertId();
            return true;
        }else{
            return false;
        }
        $con = null;
    }
    

    //Update title and content
    public function update(){
        $con = new PDO(DBhost,DBuser,DBpass);
        $query = "UPDATE article SET title=:title, content=:content, type=:type, status=:status 
        WHERE id=:id";
        $st = $con->prepare($query);
        $st->bindValue(":title",$this->title, PDO::PARAM_STR);
        $st->bindValue(":content",$this->content, PDO::PARAM_STR);
        $st->bindValue(":type",$this->type, PDO::PARAM_STR);
        $st->bindValue(":status",$this->status, PDO::PARAM_INT);
        $st->bindValue(":id",$this->id, PDO::PARAM_INT);
        $con = null;
        return $st->execute();
    }

    //Delete article
    public function delete(){
        $con = new PDO(DBhost,DBuser,DBpass);
        $query = "DELETE FROM article WHERE id =:id";
        $st = $con->prepare($query);
        $st->bindValue(":id",$this->id, PDO::PARAM_INT);
        $st->execute();
        if(!$st){
            return false;
        }else{
            return true;
        }
        $con = null;
    }

    //--------Article Likes--------------
    //Get number of likes of the article
    public function getTotalLikes(){
        $con = new PDO(DBhost,DBuser,DBpass);
        $query = "SELECT COUNT(*) FROM article_likes WHERE article_id =:article_id";
        $st = $con->prepare($query);
        $st->bindValue(":article_id",$this->id, PDO::PARAM_INT);
        $st->execute();
        $row = $st->fetch();
        $con = null;
        if ($row){
            return $row[0];
        }
    }
    
    public function getLikeById($user_id){
        $con = new PDO(DBhost,DBuser,DBpass);
        $query = "SELECT * FROM article_likes WHERE article_id =:article_id
        AND user_id=:user_id";
	    $st = $con->prepare($query);
        $st->bindValue(":article_id", $this->id, PDO::PARAM_INT);
        $st->bindValue(":user_id", $user_id, PDO::PARAM_STR);
        $st->execute();
        $row = $st->fetch();
        if ($row){
            return true;
        }else{
            return false;
        }
    }
    
    public function insertLike($user_id){
        $con = new PDO(DBhost,DBuser,DBpass);
        $query = "INSERT INTO article_likes (article_id,user_id)
        VALUES (:article_id, :user_id)";
        $st = $con->prepare($query);
        $st->bindValue(":article_id", $this->id, PDO::PARAM_INT);
        $st->bindValue(":user_id", $user_id, PDO::PARAM_STR);
        $st->execute();
        if(!$st){
            echo $con->errorInfo();
        }
        $con = null;
    }
    
    public function deleteLike($user_id){
        $con = new PDO(DBhost,DBuser,DBpass);
        $query = "DELETE FROM article_likes WHERE article_id =:article_id
        AND user_id=:user_id";
        $st = $con->prepare($query);
        $st->bindValue(":article_id", $this->id, PDO::PARAM_INT);
        $st->bindValue(":user_id", $user_id, PDO::PARAM_STR);
        $st->execute();
        $con = null;
    }
    
    //Get total comments
    public function getTotalComments(){
        $con = new PDO(DBhost,DBuser,DBpass);
        $query = "SELECT COUNT(*) FROM comments WHERE article_id =:article_id";
        $st = $con->prepare($query);
        $st->bindValue(":article_id",$this->id, PDO::PARAM_INT);
        $st->execute();
        $row = $st->fetch();
        $con = null;
        if ($row){
            return $row[0];
        }
    }

    //
    public function getLikesList(){
        $con = new PDO(DBhost,DBuser,DBpass);
        $query = "SELECT user_id FROM article_likes WHERE article_id =:article_id";
        $st = $con->prepare($query);
        $st->bindValue(":article_id",$this->id, PDO::PARAM_INT);
        $st->execute();
        $row = $st->fetch();
        $con = null;
        if ($row){
            return $row;
        }
    }
    
    //Article rejected
    public function rejectArticle($reason){
        $con = new PDO(DBhost,DBuser,DBpass);
        $query = "INSERT INTO article_rejected (article_id,reason)
        VALUES (:article_id, :reason)";
        $st = $con->prepare($query);
        $st->bindValue(":article_id", $this->id, PDO::PARAM_INT);
        $st->bindValue(":reason", $reason, PDO::PARAM_STR);
        if($st->execute()){
            return true;
        }else{
            return false;
        }
        $con = null;
    }
    
     public function getRejectReason(){
        $row = array();
        $con = new PDO(DBhost,DBuser,DBpass);
        $query = "SELECT reason FROM article_rejected WHERE article_id =:article_id";
        $st = $con->prepare($query);
        $st->bindValue(":article_id",$this->id, PDO::PARAM_INT);
        $st->execute();
        $row = $st->fetch();
        $con = null;
        return $row;
    }
    
    public function removeReject(){
        $con = new PDO(DBhost,DBuser,DBpass);
        $query = "DELETE FROM article_rejected WHERE article_id =:article_id";
        $st = $con->prepare($query);
        $st->bindValue(":article_id", $this->id, PDO::PARAM_INT);
        if($st->execute()){
            return true;
        }else{
            return false;
        }
        $con = null;
    }
}
?>