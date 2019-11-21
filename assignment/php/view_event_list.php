<?php
    include("../config.php");
    session_start();
    $id = null;
    if(isset($_SESSION['id'])){
        $id = $_SESSION['id'];
        $member = new User(User::getUserByID($id));
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
    	<script type="text/javascript" src="../include/Template/ajax.js"></script>
    	<title>Software Engineering</title>
    </head>
    <body>
        
        <?php 
        include(TEMPLATE_PATH."header.php");
        $eventList = Event::getAllEvents(10);
        ?>
        
        <div class="container" id="view_article_div">
            <br>
            <div>
                <div class="row">
                <div class="col-sm-10"><h1>Available Events</h1></div>  <!-- PAGE TITLE -->  
                <div class="col-sm-2">
                    <!-- create event -->
                    <?php
                    if(isset($_SESSION['id'])){
                    echo '<button class="btn btn-primary" id="create_event" onclick="location.href=\'create_event.php\'">Create an Event</button>';
                    }
                    ?>
                </div>
                </div>
            <br>
            <div >
                <div>
                    
                </div>
                
                <!-- Print all events -->
                <?php 
                for($x=0; $x<sizeof($eventList);$x++){
                    $userEvent = new User(User::getUserById($eventList[$x]->post_by));
                    echo '
                    <div>
                        <div id="view_event_box"';
                    $picURL = $eventList[$x]->getEventPic();
                    if($picURL != null){
                    echo '                        
                        style="background-image: url('. $picURL .'); background-color: rgba(255,255,255,0.8); background-blend-mode: lighten"; 
                        ';
                    }
                    echo '>
                            <div class="row">
                                <div class="col-sm-10">
                                    <a href="view_event.php?eid='.$eventList[$x]->id.'"><h4>
                                        '.htmlspecialchars($eventList[$x]->title);
                                    if(strtotime($eventList[$x]->end_date)<strtotime(date('m/d/Y', time()))){
                                        echo ' (Ended)';
                                    }    
                                    echo    ' </h4></a>
                                    
                                    <p>'.date("j F Y",strtotime($eventList[$x]->start_date));
                                    if($eventList[$x]->end_date != null || 
                                       $eventList[$x]->start_date != $eventList[$x]->end_date){
                                        echo ' - ' .date("j F Y",strtotime($eventList[$x]->end_date));
                                    }
                                    echo '</p>
                                    <table>
                                        <tr>
                                            <td rowspan="3">
                                            <a href="view_edit_profile.php?memberid='.$userEvent->id.'">
					                        <img src="'.$userEvent->getProfilePic().'" style="width: 32px; height: 32px; border-radius: 32px;">
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                            <a href="view_edit_profile.php?memberid='.$userEvent->id.'">
                                                '.htmlspecialchars($userEvent->name).' </a>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                '.getDateDiff($eventList[$x]->date_posted) .'
                                            </td>
                                        </tr>
                                    </table>
                                    
                                </div>';
                                if(isset($_SESSION['id'])){
                                    echo '<div class="col-sm-2">
                                    <!-- if not joined -->
                                    <button class="btn btn-success" id="joinEventBtn'.$eventList[$x]->id.'" 
                                    onclick="joinEvent('. $member->id . ',' . $eventList[$x]->id.')"';
                                    if(Event_Participant::getMemberJoin($member->id,$eventList[$x]->id)){
                                        echo ' style = "display:none;"';
                                    }
                                    if(strtotime($eventList[$x]->end_date)<strtotime(date('m/d/Y', time()))){
                                        echo ' hidden';
                                    }
                                    echo '
                                    >Join Event</button>
                                    <!-- if joined -->
                                    <button class="btn btn-danger" id="leaveEventBtn'.$eventList[$x]->id.'" 
                                    onclick="leaveEvent('. $member->id . ',' . $eventList[$x]->id.')"';
    
                                    if(!Event_Participant::getMemberJoin($member->id,$eventList[$x]->id)){
                                        echo ' style = "display:none;"';
                                    }
                                    if(strtotime($eventList[$x]->end_date)<strtotime(date('m/d/Y', time()))){
                                        echo ' hidden';
                                    } 
                                    
                                        echo '>Leave Event</button></div> ';
                                }
                                echo '
                            </div>
                    </div>';
                        
                    }
                ?>
        </div>
    </body>
</html>