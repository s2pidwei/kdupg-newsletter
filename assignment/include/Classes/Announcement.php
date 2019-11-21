<?php
class Announcement {
    public $id = null;
    public $content = null;
    public $date_posted = null;
    
    public function __construct($data = array()){
        if (isset($data['id'])){
            $this->id =  $data['id'];
        }
        if (isset($data['content'])){
            $this->content = $data['content'];
        }
        if (isset($data['date_posted'])){
            $this->date_posted = $data['date_posted'];
        }
    }
    
    public function insert() {
        $con = new PDO(DBhost,DBuser,DBpass);
        $query = "INSERT INTO annoucement(content,date_posted)
        VALUES (:content, :date_posted)";
        $st = $con->prepare($query);
        $st->bindValue(":content", $this->content, PDO::PARAM_STR);
        $st->bindValue(":date_posted", $this->date_posted , PDO::PARAM_STR);
        $st->execute();
        $con = null;
    }
    
    public static function getAnnouncement() {
        $con = new PDO(DBhost,DBuser,DBpass);
        $query = "SELECT * FROM annoucement ORDER BY id DESC LIMIT 1";
        $st = $con->prepare($query);
        $st->execute();
        $return = $st->fetch();
        if($return) {
            return $return;
        }
    }
}
?>