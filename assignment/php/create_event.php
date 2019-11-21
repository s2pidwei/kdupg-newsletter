<?php
    include("../config.php");
    session_start();
    $id = null;
    if(isset($_SESSION['id'])){
        $id = $_SESSION['id'];
        $member = new User(User::getUserByID($id));
    }
?>
    
<!DOCTYPE HTML>
<html lang=en>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    	
    	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
        <link href="http://cdn.jsdelivr.net/timepicker.js/latest/timepicker.min.css" rel="stylesheet"/>
        <script src="http://cdn.jsdelivr.net/timepicker.js/latest/timepicker.min.js"></script>
    	<script src="../include/Template/javascript.js"></script>
    	<link rel="stylesheet" href="../css/theme1.css">
    	<title>Software Engineering</title>
    </head>
    <body>
        
        <?php 
        include(TEMPLATE_PATH."header.php");
        ?>
        
        <div class="container" id="view_article_div">
            
            <h1>Create Event</h1>
            <div class="row">
                <div class="col-sm-2">
                    
                </div>
                <div class="col-sm-8">
                    <form action="action.php?action=createEvent" method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="event_name_label">Event Name</label>
                            <input type="text" class="form-control" name="event_name_input" id="event_name_input" placeholder="Event Name" required>
                        </div>
                        <div class="form-group">
                            <label for="event_desc_label">Event Description</label>
                            <textarea class="form-control" name="event_desc_input" id="event_desc_input" placeholder="Description..." required></textarea>
                        </div>
                        <div class="form-group">
                            Event start date:
                            <input type="date"  class="form-control" name="event_start_date" id="event_start_date" required>
                        </div>
                        <div class="form-group">
                            Event end date:
                            <input type="date"  class="form-control" name="event_end_date" id="event_end_date" required>
                        </div>
                        <div class="form-inline" style="margin-bottom:10px">
                            Time : &nbsp
                            <input type="time" class="form-control" name="event_start_time" id="event_start_time">
                            &nbsp to &nbsp
                            <input type="time" class="form-control" name="event_end_time" id="event_end_time">
                        </div>
                        <div class="form-group">
                            <label for="event_pic_label">1 picture may be uploaded</label>
                            <input type="file" name="event_picture_input" id="event_picture_input" accept="image/*" onchange="ValidateSingleInput(this);">
                        </div>
                        <button type="submit" name="submit" class="btn btn-success">Create</button>
                    </form>
                </div>
                <div class="col-sm-2">
                    
                </div>
            </div>
        </div>
    </body>
</html>