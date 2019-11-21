<?php
class Event_Participant{
    public $event = null; //new Event()
    public $user = null; //new User()
    public $status = null;
    public $emails = null;
    public $msg = null;

    public function __construct($data = array()){
        if (isset($data['event_id'])) {
            $this->event = (int) $data['event_id'];
        }
        if (isset($data['user_id'])) {
            $this->user = $data['user_id'];
        }
        if (isset($data['status'])) {
            $this->status = $data['status'];
        }
        if (isset($data['emails'])) {
            $this->emails = $data['emails'];
        }
        if (isset($data['msg'])) {
            $this->msg = $data['msg'];
        }
    }

    public function getMemberStatus(){
        $con = new PDO(DBhost,DBuser,DBpass);
        $query = "SELECT status FROM event_participant 
        WHERE user_id=:user_id AND event_id=:event_id";
        $st = $con->prepare($query);
        $st->bindValue(":user_id", $this->user, PDO::PARAM_STR);
        $st->bindValue(":event_id", $this->event, PDO::PARAM_INT);
        $st->execute();
        $row = $st->fetch();
        if ($row){
            return $row;
        }else{
            return false;
        }
    }

    public function getAllParticipant($count){
        $list = array();
        $con = new PDO(DBhost,DBuser,DBpass);
        $query = "SELECT * FROM event_participant WHERE event_id=:event_id LIMIT :numRows";
        $st = $con->prepare($query);
        $st->bindValue(":event_id", $this->event, PDO::PARAM_INT);
        $st->bindValue(":numRows", $count, PDO::PARAM_INT);
        $st->execute();
        while ($row = $st->fetch()) {
            $joined = new Event_Participant($row);
            $list[] = $joined;
        }
        $con = null;
        return $list;
    }

    public function insert(){
        $con = new PDO(DBhost,DBuser,DBpass);
        $query = "INSERT INTO event_participant (event_id,user_id,status)
        VALUES (:event_id,:user_id,:status)";
        $st = $con->prepare($query);
        $st->bindValue(":event_id", $this->event, PDO::PARAM_INT);
        $st->bindValue(":user_id", $this->user, PDO::PARAM_STR);
        $st->bindValue(":status", $this->status, PDO::PARAM_STR);
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
        $query = "UPDATE event_participant SET status=:status
        WHERE event_id=:event_id AND user_id=:user_id";
        $st = $con->prepare($query);
        $st->bindValue(":status", $this->status, PDO::PARAM_STR);
        $st->bindValue(":event_id", $this->event, PDO::PARAM_INT);
        $st->bindValue(":user_id", $this->user, PDO::PARAM_STR);
        $st->execute();
        if(!$st){
            return false;
        }else{
            return true;
        }
        $con = null;
    }

    public function delete(){
        $con = new PDO(DBhost,DBuser,DBpass);
        $query = "DELETE FROM event_participant 
        WHERE event_id=:event_id AND user_id=:user_id";
        $st = $con->prepare($query);
        $st->bindValue(":event_id", $this->event,PDO::PARAM_INT);
        $st->bindValue(":user_id", $this->user,PDO::PARAM_STR);
        $st->execute();
        if(!$st){
            return false;
        }else{
            return true;
        }
        $con = null;
    }
    
    public function deleteAll(){
        $con = new PDO(DBhost,DBuser,DBpass);
        $query = "DELETE FROM event_participant 
        WHERE event_id=:event_id";
        $st = $con->prepare($query);
        $st->bindValue(":event_id", $this->event,PDO::PARAM_INT);
        if($st->execute()){
            return true;
        }else{
            return false;
        }
        $con = null;
    }

    public function getMemberJoin($userid,$eventid){
        $con = new PDO(DBhost,DBuser,DBpass);
        $query = "SELECT * FROM event_participant 
        WHERE user_id=:user_id AND event_id=:event_id AND status=1";
        $st = $con->prepare($query);
        $st->bindValue(":user_id", $userid, PDO::PARAM_STR);
        $st->bindValue(":event_id", $eventid, PDO::PARAM_INT);
        $st->execute();
        $row = $st->fetch();
        if ($row){
            return true;
        }else{
            return false;
        }
    }
}
?>