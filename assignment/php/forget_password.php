<?php
	include("../config.php");
	session_start();
	if(isset($_SESSION['id'])){
	    header("Location: ".SITEURL."php/home.php");
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
    <script type="text/javascript" src="../include/Template/ajax2.js"></script>
	<title>Software Engineering</title>
</head>
<body>
    <?php
    include(TEMPLATE_PATH."header.php");
    ?>
    <div class="container">
        <div class="row">
            <div class="col-sm-2"></div>
            <div class="col-sm-8">
                <div id="forget_pw_box">
                    <form action="action.php?action=forget_pw" method="POST">
                        <div class="form-group">
                            <h2 style="padding: 0px;">Forgot password</h2>
                            <div id="forget_pw_text">
                                Password will be sent to the following email.
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="forget_pw_email_label">Email</label>
                            <input type="text" class="form-control" id="forget_pw_email_input" name="forget_pw_email_input">
                        </div>
                        <div id="forget_pw_btn" style="text-align: right;">
                            <button type="submit" class="btn btn-success">Confirm</button>
                            <button type="button" class="btn btn-danger" onclick="window.history.back();">Cancel</button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-sm-2"></div>
        </div>
    </div>
</body>