<?php
    include("../config.php");
    session_start();
    $id = null;
    if(isset($_SESSION['id'])){
        $id = $_SESSION['id'];
    }
    if(isset($_GET['memberid'])){
        $memberid = $_GET['memberid'];
        $memberProfile = new User(User::getUserByID($memberid));
    }
?>
<!DOCTYPE HTML>
<html lang=en>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    	
    	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
        <script src="https://kit.fontawesome.com/c1620fa448.js" crossorigin="anonymous"></script>
        <script src="https://kit.fontawesome.com/c1620fa448.js" crossorigin="anonymous"></script>
    	<link rel="stylesheet" href="../css/theme1.css">
    	<script src="../include/Template/javascript.js"></script>
    	<title>Software Engineering</title>
    </head>
    <body>
        
        <?php 
        include(TEMPLATE_PATH."header.php");
        ?>
        <!-- report user modal -->
        <div class="modal fade" role="dialog" id="report_user_box">
	        <div class="modal-dialog" role="document">
	            <div class="modal-content">
	                <div class="modal-header">
                        <h5 class="modal-title">Report user</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
	                </div>
	                <div class="modal-body">
	                    <form action="action.php?action=reportUser" method="POST">
	                        <div class="form-group">
	                            <textarea class="form-control" name="report_user_input" id="report_user_input" placeholder="Whats wrong with this user?" required></textarea>
	                            <input type="text" name="reportUserId" id="reportUserId" value="" hidden/>
	   	                    </div>
	   	                    <button type="submit" class="btn btn-danger" id="report_user_btn">Report</button>
	   	                </form>
	    	        </div>
	    	        <div class="modal-footer">
	    	        </div>
	    	    </div>
	    	</div>
	    </div>
	    <!-- end of modal-->
        
        <!-- report user modal -->
        <div class="modal fade" role="dialog" id="reject_reason_box">
	        <div class="modal-dialog" role="document">
	            <div class="modal-content">
	                <div class="modal-header">
                        <h5 class="modal-title">Reason for rejection</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
	                </div>
	                <div class="modal-body">
	                    <p id="rejectRaeason">Reasons :D</p>
	    	        </div>
	    	        <div class="modal-footer">
	    	        </div>
	    	    </div>
	    	</div>
	    </div>
	    <!-- end of modal-->
        
        <!-- if viewing profile -->
        <div class="container" id="view_article_div">
            <div class="row" id="view_article_div2">
                <h1>View Profile</h1>
                <div class="col-sm-2">
                <div>
                <!-- report button -->
                <?php
                if($memberid != $id){
                echo '<div id="report_box">
                    <button class="btn btn-danger" data-toggle="modal" data-target="#report_user_box" 
                    onclick="reportUser(\''. $memberid.'\')">Report this user</button>
                </div>';
                //end report
                //edit profile button
                }
                if($memberid == $id){
                echo '<div id="edit_own_profile" style="display: flex;">
                    <button class="btn btn-info" id="edit_profile_btn" onclick="show_edit_profile()">Edit profile</button>
                    <button class="btn btn-info" id="update_pw_btn" onclick="show_update_pw2()" >Update password</button>
                </div>';
                }?>
                    
                    </div> 
                </div>
                <div class="col-sm-8">
                    <table>
                        <tr>
                            <td rowspan="6">
                                <img src="<?php echo $memberProfile->getProfilePic(); ?>" style="width: 64px; height: 64px; border-radius: 32px;">
                            </td>
                            <td>
                                Name: <?php echo htmlspecialchars($memberProfile->name); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Student ID: <?php echo $memberProfile->id; ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Email: <?php echo htmlspecialchars($memberProfile->email); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Contact: <?php echo htmlspecialchars($memberProfile->contact); ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Date Joined: <?php echo $memberProfile->date_joined; ?>
                            </td>
                        </tr>
                        <tr>
                            <td>
                                Description: <?php echo htmlspecialchars($memberProfile->description); ?>
                            </td>
                        </tr>
                    </table>
                </div>
                <!-- end viewing profile -->
            </div>
             
            <?php
            echo '<!-- if editting profile -->
            <div class="container" id="edit_article_div" style = "display:none;">
                <h1>Edit Profile</h1>
                <div class="row">
                    <div class="col-sm-2">
                        
                    </div>
                    <div class="col-sm-8">
                        <form action="action.php?action=editProfile" method="POST" enctype="multipart/form-data">
                            <div class="form-group">
                                <img id="editProfilePic" src="'.$memberProfile->getProfilePic().'"style="width: 64px; height: 64px; border-radius: 32px;">
                                <label for="usr_edit_picture_label">Change profile picture: </label>
                                <input type="file" name="usr_edit_picture" id="usr_edit_picture" accept="image/*" 
                                onchange="ValidateSingleInput(this);">
                            </div>
                            <div class="form-group">
                                <label for="usr_edit_name_label">Profile name: </label>
                                <input type="text" class="form-control" name="usr_edit_name" id="usr_edit_name" 
                                value="'. htmlspecialchars($memberProfile->name).'" required>
                            </div>
                            <div class="form-group">
                                <label for="usr_edit_email_label">Email: </label>
                                <input type="text" class="form-control" name="usr_edit_email" id="usr_edit_email" 
                                value="'. htmlspecialchars($memberProfile->email).'" required>
                            </div>
                            <div class="form-group">
                                <label for="usr_edit_contact_label">Contact: </label>
                                <input type="text" class="form-control" name="usr_edit_contact" id="usr_edit_contact" 
                                value="'. htmlspecialchars($memberProfile->contact).'">
                            </div>
                            <div class="form-group">
                                <label for="usr_edit_desc_label">Description: </label>
                                <input type="text" class="form-control" name="usr_edit_desc" id="usr_edit_desc" 
                                value="'. htmlspecialchars($memberProfile->description).'">
                            </div>
                            
                            <div class="form-group">
                            <button type="submit" class="btn btn-success">Update</button>
                            <button type="button" class="btn btn-danger" id="cancel_profile_btn" onclick="hide_edit_profile()">Cancel</button>
                            </div>
                        </form>
                        
                    </div>
                    <div class="col-sm-2">
                    
                    </div>
                </div>
            </div>';
            ?>
            <!-- end editting profile -->
            <!-- update password -->
            <div id="update_pw_div" style="display: none;">
                <form action="action.php?action=update_pw" method="POST">
                    <h3>Update password</h3>
                    <br>
                    <div class="form-group">
                        <label for="current_pw_label">Current password</label>
                        <input type="password" id="current_pw_input" name="current_pw_input" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="new_pw_label">New password</label>
                        <input type="password" id="new_pw_input" name="new_pw_input" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="confirm_pw_input">Confirm password</label>
                        <input type="password" id="confirm_pw_input" name="confirm_pw_input" class="form-control" required>
                    </div>
                    <button type="submit" class="btn btn-success">Confirm</button>
                    <button type="button" class="btn btn-danger" onclick="hide_update_pw()">Cancel</button>
                </form>
            </div>
        </div>
        
        <?php
        if($memberid==$id){
            $articles = Article::getArticleByUser($memberid);
        }else{
            $articles = Article::getApprovedArticles($memberid);
        }
        foreach($articles as $article){
            $content = $article->content;
            if (strlen($article->content) > 500){
                $content = substr($article->content, 0, 500) . '...';
            }
            echo '<!-- view profile article -->
            <div class="container id="viewAllWroteArticle">
                <div class="row">
                    <div class="col-sm-2"></div>
                    <div class="col-sm-8">
                        <div id="article_box">
                        <div id="article_content">
                            <div id="categoryBox">
                            Category : General';
                            if($memberid==$id){
                            echo '
                            <p style="float:right">Status:';
                                switch($article->status){
                                    case 0:
                                        echo ' Pending';
                                        break;
                                    case 1:
                                        echo ' Approved';
                                        break;
                                    case 2:
                                        $reason =  $article->getRejectReason();
                                        echo '<button id="articleRejectedBtn" onclick="getRejectReason(\''.htmlspecialchars($reason['reason']).'\')">Rejected</button></p></div>
                                        <button id="showReasonModal" data-toggle="modal" data-target="#reject_reason_box" hidden></button>';
                                        break;
                                    case 3:
                                        $reason =  $article->getRejectReason();
                                        echo '<button id="articleRejectedBtn" onclick="getRejectReason(\''.htmlspecialchars($reason['reason']).'\')">Blocked</button></p></div>
                                        <button id="showReasonModal" data-toggle="modal" data-target="#reject_reason_box" hidden></button>'
                                        ;
                                        break;
                                }
                            }
                            echo '
                            <p><a href="view_article.php?aid='. $article->id .'">'. htmlspecialchars($article->title) .'</a></p>
                            <div id="article_content_box">
                            <pre>'. htmlspecialchars($content) .'</pre></div>
                            <p class="article_total_likes" id="likestotal48">'. $article->getTotalLikes() .' Likes</p>
                            <p id="article_total_comments">'. $article->getTotalComments().' Comments</p>
                        </div>
                        <br>
                        </div>
                    <div class="col-sm-2"></div>
                    </div>
                    </div>
                </div>
            </div>
            <br>
                <!-- end profile article -->';
        }
        ?>
        </div>
    </body>
</html>