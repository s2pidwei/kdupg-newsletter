<?php
	echo '
	<!-- scrolling text -->
	<div id="annContainer">
    	<div id="announcement">
    	    <marquee behaviour="scroll" direction="left" scrollamount="10" id="scrollingText">';
    	     $announcement = new Announcement(Announcement::getAnnouncement());
    	     echo htmlspecialchars($announcement->content);
    	    echo'</marquee>
    	</div>
	</div>
	<!-- end scrolling text -->
	<div class="container-fluid" id="top_bar">
		<div class="row" style="width: 100%;">
			<div class="col-sm-1" >
			    <a href="home.php" >
				    <img src="../img/kdupg_logo.png" width="100%"></a>
			</div>
			<div class="col-sm-7">
				<a href="home.php" style="color: white;"><h2>Home</h2></a> 
				<a href="view_event_list.php" style="color: white;"><h2>Event</h2></a> 
			</div>';
	if(!isset($_SESSION['id'])){ //show these if not member
	    echo '<div class="col-sm-4" style="text-align: right;">
    		    <a href="sign_in.php" id="header_link"><h2>Sign In</h2></a>
    		  </div>';
	}else{ //show this if member
	    $headerMember = new User(User::getUserByID($_SESSION['id']));
	    echo '<div class="col-sm-4" style="text-align: right;">
    		    <a href="view_edit_profile.php?memberid='.$headerMember->id.'">
    		    <img src="'.$headerMember->getProfilePic().'" id="header_profile_pic"></a>
    		    <a href="action.php?action=logout" id="header_link"><h2>Sign Out</h2></a>
    		</div>';
	}
	echo '</div></div>';

?>
