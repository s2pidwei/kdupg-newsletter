<?php
    include("../config.php");
?>
<!DOCTYPE HTML>
<html lang=en>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    	
    	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    	<link rel="stylesheet" href="../css/theme1.css">
    	<title>Software Engineering</title>
    </head>
    <body>
        <?php 
            include("../include/Template/header.php");
        ?>
        <div class="container">
            <div class="row">
                <div class="col-sm-3">
                    
                </div>
                <div class="col-sm-6" id="sign_up_box">
                    <form action="action.php?action=signup" method="POST">
                        <div class="form-group">
                            <label for="usr_id_label" id="sign_up_info">Student ID (Max Character 11):</label>
                            <input type="text" class="form-control" id="usr_id" name="usr_id" placeholder="Student ID" maxlength="11">
                        </div>
                        <div class="form-group">
                            <label for="usr_name_label" id="sign_up_info">Name:</label>
                            <input type="text" class="form-control" id="usr_name" name="usr_name" placeholder="User name">
                        </div>
                        <div class="form-group">
                            <label for="usr_email_label" id="sign_up_info">Email:</label>
                            <input type="email" class="form-control" id="usr_email" name="usr_email" placeholder="user@email.com">
                        </div>
                        <div class="form-group">
                            <label for="usr_pass_label" id="sign_up_info">Password:</label>
                            <input type="password" class="form-control" id="usr_pass" name="usr_pass" placeholder="Password">
                        </div>
                        <div class="form-group">
                            <label for="usr_passc_label" id="sign_up_info">Confirm password:</label>
                            <input type="password" class="form-control" id="usr_passc"  name="usr_passc" placeholder="Confirm password">
                        </div>
                        <div class="form-group">
                            <label for="usr_contact_label" id="sign_up_info">Contact number:</label>
                            <input type="tel" pattern="[0-9]{3}-[0-9]{7}" class="form-control" id="usr_contact" name="usr_contact" placeholder="XXX-XXXXXXX" minlength="9" maxlength="14">
                        </div>
                        <button type="submit" class="btn btn-primary">Sign up</button>
                    </form>
                </div>
                <div class="col-sm-3">
                    
                </div>
            </div>
        </div>
    </body>
</html>