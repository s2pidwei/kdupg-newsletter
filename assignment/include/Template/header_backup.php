<?php
	echo '<div class="container-fluid" id="top_bar">
		<div class="row" style="width: 100%;">
			<div class="col-sm-1" >
			    <a href="home.php" >
				    <img src="../img/kdupg_logo.png" width="100%"></a>
			</div>
			<div class="col-sm-7">
				<a href="home.php" style="color: white;"><h2>Home</h2></a>
				<a href="" style="color: white;"><h2>Event</h2></a>
			</div>';
	if(!isset($_SESSION['id'])){ //show these if not member
	    echo '<div class="col-sm-4" style="text-align: right;">
	            <a href="sign_up.php" style="color: white;"><h2>Sign Up</h2></a>
    		    <a href="sign_in.php" style="color: white;"><h2>Login</h2></a>
    		  </div>';
	}else{ //show this if member
	    echo '<div class="col-sm-2" style="text-align: right;">
    		    <a href="" style="color: white;"><h2>Profile</h2></a>
    		</div>
	    <div class="col-sm-2" style="text-align: right;">
    		    <a href="action.php?action=logout" style="color: white;"><h2>Logout</h2></a>
    		</div>';
	}
	echo '</div></div>';

?>
