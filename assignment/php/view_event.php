<?php
    include("../config.php");
    session_start();
    $member = new User();
    $account = new Account();
    $account->type = -1;
    if(isset($_SESSION['id'])){
        $id = $_SESSION['id'];
        $member = new User(User::getUserByID($id));
        $account = Account::getAccByID($id);
    }
    if(isset($_GET['eid'])){
        $eventid = $_GET['eid'];
        $event = new Event(Event::getEventByID2($eventid));
    } else {
        header('Location: '. SITEURL .'php/home.php');
        exit();
    }
    
    function getDateDiff($datePost){
        $datenow = date('Y-m-d H:i:s');
        $dateDiff = abs(strtotime($datenow) - strtotime($datePost));
        $printDateDiff = null;
        $text = null;
        if($dateDiff<60){ //second
            $dateDiff = "Less than 1 minute"; 
        }elseif($dateDiff < 60*60){ //minute
            $dateDiff = intval($dateDiff/(60));
            if($dateDiff == 1)
                $text = " minutes"; 
            else
                $text = " minutes";
        }elseif($dateDiff < 60*60*24){ //hour
            $dateDiff = intval($dateDiff/(60*60));
            if($dateDiff == 1)
                $text = " hour"; 
            else
                $text = " hours";
        }elseif($dateDiff < 60*60*24*30){ //day
            $dateDiff = intval($dateDiff/(60*60*24));
            if($dateDiff == 1)
                $text = " day"; 
            else
                $text = " days";
        }elseif($dateDiff < 60*60*24*30*12){ //month
            $dateDiff = intval($dateDiff/(60*60*24*30));
            if($dateDiff == 1)
                $text = " month"; 
            else
                $text = " months";
        }else{ //year
            $dateDiff = intval($dateDiff/(60*60*24*30*12));
            if($dateDiff == 1)
                $text = " year"; 
            else
                $text = " years";
        }
        $printDateDiff = $dateDiff . $text . " ago";
        return $printDateDiff;
    }
?>

<!DOCTYPE HTML>
<html lang=en>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    	
    	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    	<link rel="stylesheet" href="../css/theme1.css">
    	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
        <script src="https://kit.fontawesome.com/c1620fa448.js" crossorigin="anonymous"></script>
    	<script type="text/javascript" src="../include/Template/javascript.js"></script>
    	<script type="text/javascript" src="../include/Template/ajax.js"></script>
    	<title>Software Engineering</title>
    </head>
    <body>
        
        <?php 
        include(TEMPLATE_PATH."header.php");
        $eventUser = new User(User::getUserById($event->post_by));
        ?>
        <!-- report event modal -->
        <div class="modal fade" role="dialog" id="report_event_box">
	        <div class="modal-dialog" role="document">
	            <div class="modal-content">
	                <div class="modal-header">
                        <h5 class="modal-title">Report event</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
	                </div>
	                <div class="modal-body">
	                    <form action="action.php?action=reportEvent" method="POST">
	                        <div class="form-group">
	                            <textarea class="form-control" name="report_event_input" id="report_event_input" placeholder="Whats wrong with this event?" required></textarea>
	                            <input type="text" name="reportEventId" id="reportEventId" value="<?php echo $event->id;?>" hidden/>
	   	                    </div>
	   	                    <button type="submit" class="btn btn-danger" id="report_event_btn">Report</button>
	   	                </form>
	    	        </div>
	    	        <div class="modal-footer">
	    	        </div>
	    	    </div>
	    	</div>
	    </div>
	    <!-- end of modal-->
        <!-- invite to event modal -->
        <div class="modal fade" role="dialog" id="invite_to_event_box">
	        <div class="modal-dialog" role="document">
	            <div class="modal-content">
	                <div class="modal-header">
                        <h5 class="modal-title">Invite to event</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
	                </div>
	                <div class="modal-body">
	                    <form action="action.php?action=inviteToEvent" method="POST">
	                        <div class="form-group">
	                            <label for="invite_email_label">Emails</label>
	                            <textarea class="form-control" name="invite_to_event_input" id="invite_to_event_input" placeholder="Type in email, separate by ','" required></textarea>
	                            <label for="invite_message_label">Message</label>
	                            <textarea class="form-control" name="invite_to_event_msg" id="invite_to_event_msg" placeholder="An invite message..."></textarea>
	                            <input type="text" name="inviteEventId" id="inviteEventId" value="<?php echo $event->id;?>" hidden/>
	   	                    </div>
	   	                    <button type="submit" class="btn btn-success" id="invite_to_event_btn">Invite</button>
	   	                </form>
	    	        </div>
	    	        <div class="modal-footer">
	    	        </div>
	    	    </div>
	    	</div>
	    </div>
	    <!-- end of modal-->
        <div class="container-fluid" id="">
            <div class="row">
                <div class="col-sm-8">
                    <!-- event details -->
                    <div id="event_detail">
                        <h1><?php echo $event->title ?></h1>
                        <p>by: <img src="<?php echo $eventUser->getProfilePic(); ?>" style="width: 32px; height: 32px; border-radius: 32px;">
                        <?php echo '<a href="view_edit_profile.php?memberid='. $eventUser->id . '"> '. 
                        htmlspecialchars($eventUser->name) . '</a>&nbsp&nbsp' . getDateDiff($event->date_posted);?></p>
                        <hr>
                        <div id="event_picture">
                            <img src="<?php echo $event->getEventPic(); ?>" height="400">
                        </div>
                        <br>
                        <table style="width:100%; margin-bottom:5px;">
                            <tr>
                                <th>Start Date: <?php echo date("j F Y",strtotime($event->start_date)) ?></th>
                            <?php
                            if($event->end_date!=null){
                            echo '
                                <th>End Date: '. date("j F Y",strtotime($event->end_date)) .'</th>';
                            }
                            echo '</tr>';
                            if($event->start_time!=null && $event->end_time){
                                echo '<tr><th colspan="2">Duration: '. date("G:i",strtotime($event->start_time)) .
                                    ' - '. date("G:i",strtotime($event->end_time)) .'</th>
                                </tr>';
                            }
                            ?>
                        </table>
                        
                        <p><?php echo htmlspecialchars($event->content) ?></p>
                    </div>
                    <!-- end event details -->
                </div>
                <div class="col-sm-4">
                    <div id="event_side_box">
                    <!-- report article -->
                    <div id="report_box">
                        <?php
                        if($eventUser->id == $member->id || $account->type == 0){
                            echo '<!-- if is creator -->
                            <button class="btn btn-danger" onclick="deleteEvent('.$event->id.')" id="event_button">Close Event</button>';
                        }else if($eventUser->id != $member->id){
                            echo '<button class="btn btn-danger" data-toggle="modal" data-target="#report_event_box" onclick="reportEvent(\''. $event->id.'\')" id="event_button">Report Event</button>';
                        }
                        ?>
                    </div>
                    <!-- end report article -->
                    <!-- join/leave event -->
                    <div id="join_status" <?php if(!isset($_SESSION['id'])){echo 'hidden'; } ?>  >
                        <!-- if not joined -->
                        <button class="btn btn-success joinLeaveEvent" id="joinEventBtn<?php echo $event->id?>"
                        onclick="joinEvent(<?php echo "'" . $member->id . "'," . $event->id ?>)" 
                        <?php
                        if(Event_Participant::getMemberJoin($member->id,$event->id)){
                            echo ' style = "display:none;"';
                        }
                        if(strtotime($event->end_date)<strtotime(date('m/d/Y', time()))){
                            echo ' hidden';
                        }
                        ?>
                        >Join Event</button>
                        <!-- if joined -->
                        <button class="btn btn-danger joinLeaveEvent" id="leaveEventBtn<?php echo $event->id?>"
                        onclick="leaveEvent(<?php echo "'" . $member->id . "'," . $event->id?>)" 
                        <?php
                        if(!Event_Participant::getMemberJoin($member->id,$event->id)){
                            echo ' style = "display:none;"';
                        }
                        if(strtotime($event->end_date)<strtotime(date('m/d/Y', time()))){
                            echo ' hidden"';
                        }
                        ?>
                        >Leave Event</button>
                    </div>
                    <!-- invite to join -->
                    <?php
                    if(isset($_SESSION['id'])) {
                        echo '
                        <button type="button" class="btn btn-secondary" id="inviteToJoin" data-toggle="modal" data-target="#invite_to_event_box">Invite to event</button>
                        ';
                    }
                    ?>
                    </div>
                    <div id="event_side_box2">
                        <h4>Participants</h4>
                        <?php
                        $participants = new Event_Participant();
                        $participants->event = $event->id;
                        $participants_id = $participants->getAllParticipant(20);
                        foreach ($participants_id as $user_id) {
                            $participant_name = new User(User::getUserByID($user_id->user));
                            echo '<a href="view_edit_profile.php?memberid='. $participant_name->id .'">'
                            .htmlspecialchars($participant_name->name).'</a><br>';
                        }
                        if(sizeof($participants_id)==0){
                            echo "No Participant";
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>
    </body>
</html>