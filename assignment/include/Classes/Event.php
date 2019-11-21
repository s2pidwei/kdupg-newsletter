<?php
class Event{
    public $id = null;
    public $title = null;
    public $content = null;
    public $start_date = null;
    public $end_date = null;
    public $start_time = null;
    public $end_time = null;
    public $date_posted = null;
    public $post_by = null;
    public $status = null;

    public function __construct($data = array()){
        if (isset($data['id'])){
            $this->id = (int) $data['id'];
        }
        if (isset($data['title'])){
            $this->title = $data['title'];
        }
        if (isset($data['content'])){
            $this->content = $data['content'];
        }
        if (isset($data['start_date'])){
            $this->start_date = $data['start_date'];
        }
        if (isset($data['end_date'])){
            $this->end_date = $data['end_date'];
        }
        if (isset($data['start_time'])){
            $this->start_time = $data['start_time'];
        }
        if (isset($data['end_time'])){
            $this->end_time = $data['end_time'];
        }
        if (isset($data['date_posted'])){
            $this->date_posted = $data['date_posted'];
        }
        if (isset($data['post_by'])){
            $this->post_by = $data['post_by'];
        }
        if (isset($data['status'])){
            $this->status = $data['status'];
        }
    }

    public static function getAllEvents($count = 1000){
        $list = array();
        $con = new PDO(DBhost,DBuser,DBpass);
        $query = "SELECT * FROM event_desc WHERE status = 1 
        ORDER BY date_posted DESC LIMIT :numRows";
        $st = $con->prepare($query);
        $st->bindValue(":numRows", $count, PDO::PARAM_INT);
        $st->execute();
        while($row = $st->fetch()){
            $event = new Event($row);
            $list[] = $event;
        }
        $con = null;
        return $list;
    }
    
    public static function getPendingEvents($count = 20){
        $list = array();
        $con = new PDO(DBhost,DBuser,DBpass);
        $query = "SELECT * FROM event_desc WHERE status = 0 
        ORDER BY date_posted DESC LIMIT :numRows";
        $st = $con->prepare($query);
        $st->bindValue(":numRows", $count, PDO::PARAM_INT);
        $st->execute();
        while($row = $st->fetch()){
            $event = new Event($row);
            $list[] = $event;
        }
        $con = null;
        return $list;
    }

    public static function getEventByID2($id){
        $con = new PDO(DBhost,DBuser,DBpass);
        $query = "SELECT * FROM event_desc WHERE id = :id";
        $st = $con->prepare($query);
        $st->bindValue(":id", $id, PDO::PARAM_INT);
        $st->execute();
        $row = $st->fetch();
        if($row){
            return $row;
        }
    }

    public static function getEventByID(){
        $con = new PDO(DBhost,DBuser,DBpass);
        $query = "SELECT * FROM event_desc WHERE id = :id";
        $st = $con->prepare($query);
        $st->bindValue(":id", $this->id, PDO::PARAM_INT);
        $st->execute();
        $row = $st->fetch();
        if($row){
            return $row;
        }else{
            return false;
        }
    }

    public function insert(){
        $con = new PDO(DBhost,DBuser,DBpass);
        $query = "INSERT INTO event_desc(title,content,start_date,end_date,start_time,end_time,date_posted,post_by,status)
        VALUES(:title, :content, :start_date, :end_date, :start_time, :end_time, :date_posted, :post_by,:status)";
        $st = $con->prepare($query);
        $st->bindValue(":title", $this->title, PDO::PARAM_STR);
        $st->bindValue(":content", $this->content, PDO::PARAM_STR);
        $st->bindValue(":start_date", $this->start_date, PDO::PARAM_STR);
        $st->bindValue(":end_date", $this->end_date, PDO::PARAM_STR);
        $st->bindValue(":start_time", $this->start_time, PDO::PARAM_STR);
        $st->bindValue(":end_time", $this->end_time, PDO::PARAM_STR);
        $st->bindValue(":date_posted", $this->date_posted, PDO::PARAM_STR);
        $st->bindValue(":post_by", $this->post_by, PDO::PARAM_STR);
        $st->bindValue(":status", $this->status, PDO::PARAM_INT);
        if($st->execute()){
            $this->id = $con->lastInsertId();
            return true;
        }else{
            return false;
        }
        $con = null;
    }

    public function update(){
        $con = new PDO(DBhost,DBuser,DBpass);
        $query = "UPDATE event_desc SET title=:title, content=:content, start_date=:start_date, 
        end_date=:end_date, start_time=:start_time, end_time=:end_time, date_posted=:date_posted, 
        post_by=:post_by, status=:status WHERE id=:id";
        $st = $con->prepare($query);
        $st->bindValue(":title", $this->title, PDO::PARAM_STR);
        $st->bindValue(":content", $this->content, PDO::PARAM_STR);
        $st->bindValue(":start_date", $this->start_date, PDO::PARAM_STR);
        $st->bindValue(":end_date", $this->end_date, PDO::PARAM_STR);
        $st->bindValue(":start_time", $this->start_time, PDO::PARAM_STR);
        $st->bindValue(":end_time", $this->end_time, PDO::PARAM_STR);
        $st->bindValue(":date_posted", $this->date_posted, PDO::PARAM_STR);
        $st->bindValue(":post_by", $this->post_by, PDO::PARAM_STR);
        $st->bindValue(":status", $this->status, PDO::PARAM_INT);
        $st->bindValue(":id", $this->id, PDO::PARAM_INT);
        if($st->execute()){
            return true;
        }else{
            return false;
        }
        $con = null;
    }
    
    public function updateStatus(){
        $con = new PDO(DBhost,DBuser,DBpass);
        $query = "UPDATE event_desc SET status=:status WHERE id=:id";
        $st = $con->prepare($query);
        $st->bindValue(":status", $this->status, PDO::PARAM_INT);
        $st->bindValue(":id", $this->id, PDO::PARAM_INT);
        if($st->execute()){
            return true;
        }else{
            return false;
        }
        $con = null;
    } 

    public function delete(){
        $con = new PDO(DBhost,DBuser,DBpass);
        $query = "DELETE FROM event_desc WHERE id=:id";
        $st = $con->prepare($query);
        $st->bindValue(":id", $this->id, PDO::PARAM_INT);
        if($st->execute()){
            return true;
        }else{
            return false;
        }
        $con = null;
    }
    
    public function getEventPic(){
        $pictureLoc = null;
        if (file_exists('../upload/event_compress/'.$this->id.'.jpeg')){
            $pictureLoc = '../upload/event_compress/'.$this->id.'.jpeg?='.rand(1,32000);
        }
        return $pictureLoc;
    }
}
?>