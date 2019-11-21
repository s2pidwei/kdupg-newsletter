<?php
    include("../config.php");
    session_start();
    $id = null;
    $member = new User();
    if(isset($_SESSION['id'])){
        $id = $_SESSION['id'];
        $member = new User(User::getUserByID($id));
        $account= Account::getAccByID($_SESSION['id']);
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
    <script type="text/javascript" src="../include/Template/ajax2.js"></script>
	<title>Software Engineering</title>
</head>
<body>
    
    <?php
    include(TEMPLATE_PATH."header.php");
    ?>
    
	<div class="container-fluid">
		<div class="row">
			<div class="col-sm-3">
			    <?php
			    if(isset($id)){
    			    echo '<div id="profile_container">
    					<a href="view_edit_profile.php?memberid='.$member->id.'">
    					<img src="'.$member->getProfilePic().'" style="width: 64px; height: 64px; border-radius: 32px;">
    					    <!-- profile picture -->
    					<h3 style="display:inline; vertical-align:middle; color: black;">
    					<!-- profile name -->
    					'. htmlspecialchars($member->name) .'
    					</h3></a>
    				</div>';
    				echo '
    				<div id="write_container">
    				<button type="button" class="btn btn-primary" id="write_button" data-toggle="modal" data-target="#write_article_box">Write an article</button>
    				</div>
    				<div id="become_member">';
    				
                    if($account->type == 0){
                        echo'
                            <a href="admin.php"><h4>Control panel</h4></a>
                        ';
                    }
    				echo '
    					<a href="view_edit_profile.php?memberid='.$member->id.'"><h4>Profile</h4></a>
    					<a href="view_edit_profile.php?memberid='.$member->id.'"><h4>Articles</h4></a>
    					<a href="action.php?action=logout"><h4>Sign Out</h4></a>
    				</div>';
    			    } else{
    				echo '<div id="become_member">
    					<h4>Become a member and you can:</h4>
    					<h6>-Share articles</h6>
    					<h6>-Like other articles</h6>
    					<h6>-Comment on other articles</h6>
    					<h6>-Post articles</h6>
    					<h6>-Subscribe to other user for latest updates</h6>
    					<a href="sign_up.php"><h5>Sign up now!</h5></a>
    				</div>';
    			    }
				?>
				
			</div>
			<div class="col-sm-6">
			    <div id="topSide">
			        <table style="width:100%">
			            <tr>
			                <td style="width: 80%;">
                				<!-- search box -->
                				<div class="md-form" id="search_box">
                                    <input class="form-control" type="text" placeholder="Search Article" aria-label="Search"
                                    id="searchArticleBox" onkeypress="enterToSearch()">
                                </div>
                            </td>
                            <td style="text-align: center;">
                			    <!-- end search box -->
                                <!-- category -->
                                <div class="dropdown">
                                    <button class="btn btn-secondary dropdown-toggle" type="button" id="categoryDropdown" data-toggle="dropdown">Select Category</button>
                                    <div class="dropdown-menu">
                                        <button class="dropdown-item" type="button" 
                                            onclick="articleCategory(null)">All Category</button>
                                        <?php
                                        $categories = Category::getAllCategory();
                                        foreach ($categories as $category) {
                                            echo '<button class="dropdown-item" type="button" 
                                            onclick="articleCategory(\''.$category["type"].'\')">'.$category["type"].'</button>';
                                        }
                                        ?>
                                    </div>
                                </div>
                                <!-- end category -->
                            </td>
                            </tr>
                    </table>
                </div>
				<!-- write article (pop up) -->
			    <div class="modal fade bd-example-modal-lg" role="dialog" id="write_article_box">
			        <div class="modal-dialog modal-lg" role="document">
			            <div class="modal-content">
			                <div class="modal-header" id="edit_article_header">
                                <h1 class="modal-title">Write article</h1>
                                
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
			                </div>
			                <br>
			                <div class="modal-body" id="article_edit_contents">
			                    <form action="action.php?action=newArticle" method="POST">
			                        <div class="form-group">
			                        <table style="width:100%">
			                            <tbody>
			                            <tr>
			                             <td style="width:25%">       
			                            <h5 class="modal-title" style="margin-top:20px; margin-bottom:20px ">Add Article Title</h5>
			                            </td>
			                            <!-- category -->
			                        <td style="width:75%">
                                    <div class="dropdown">
                                    
                                    <select class="btn btn-secondary dropdown-toggle-menu" name="categoryOption" required>
                                        <option value="" disabled selected>Select Category</option>
                                        <?php
                                        foreach ($categories as $category) {
                                         echo '<option value="'.$category["type"].'">'.$category["type"].'</option>';
                                        }
                                        ?>
                                    </select>
                                </div>
                                </td>
                                
                                </tr>
                                </tbody>
                                </table>
                                <!-- end category -->
                                
			                            <input type="text" class="form-control" id="write_article_title_input" 
			                                name="new_title" placeholder="Article title" required>
			                                
			                            <h5 class="modal-title" style="margin-top:20px">Write Article Contents</h5>       
			                            <textarea class="form-control" id="edit_article_input" name="new_content" placeholder="Write something..." style="white-space: pre-line;" required></textarea>
			   	                    </div>
			   	             <div class="modal-footer">
			    	            <div class="col-md-12 text-center">
			    	            <button type="submit" class="btn btn-primary" id="write_article_btn" 
			    	            onclick="this.form.submit(); this.disabled=true;">Publish</button>
			    	            </div>
			    	        </div>
			   	                </form>
			    	        </div>

			    	    </div>
			    	</div>
			    </div>
			    <!-- end write article -->
			    <!-- edit article (pop up) -->
	    <div class="modal fade bd-example-modal-lg" role="dialog" id="edit_article_box">
	        <div class="modal-dialog modal-lg" role="document" id="modal_content_box"  > <!---->
	            <div class="modal-content" >
	                <div class="modal-header" id="edit_article_header">
                        <h1 class="modal-title">Edit article</h1>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
	                </div>
	                <br>
	                <div class="modal-body" id="article_edit_contents2">
	                    
	                    <form action="action.php?action=editArticle2&articleId=<?php echo $article->id ?>" method="POST">
	                        <table style="width:100%">
	                        <tbody>
	                        <tr>
	                        <td style="width:25%">
	                        <h5 class="modal-title" style="margin-top:20px; margin-bottom:20px ">Edit Article Title</h5>
	                        </td>
	                        <!-- category -->
			                        <td style="width:75%">
                                    <div class="dropdown">
                                    <button class="btn btn-secondary dropdown-toggle" type="button" id="categoryDropdown" data-toggle="dropdown">Select Category</button>
                                    <div class="dropdown-menu">
                                        <button class="dropdown-item" type="button" 
                                            onclick="articleCategory(null)">All Category</button>
                                        <?php
                                        $categories = Category::getAllCategory();
                                        foreach ($categories as $category) {
                                            echo '<button class="dropdown-item" type="button" 
                                            onclick="articleCategory(\''.$category["type"].'\')">'.$category["type"].'</button>';
                                        }
                                        ?>
                                    </div>
                                </div>
                                </td>
                                
                                </tr>
                                </tbody>
                                </table>
                                <!-- end category -->
	                        
	                        <div class="form-group" >
	                            <input type="text" class="form-control" name="edit_article_title_input" id="edit_article_title_input" placeholder="Article title" value="" required>
	                            
	                            <h5 class="modal-title" style="margin-top:20px">Edit Article Contents</h5>
	                            <textarea class="form-control" name="edit_article_input" id="edit_article_input" placeholder="Write something..." required></textarea>
	   	                    </div>
	   	                    
	   	                    <div class="col-md-12 text-center">
	   	                    <button type="submit" class="btn btn-primary" id="edit_article_btn">Save</button>
	   	               </div>
	   	                </form>
	    	        </div>
	    	        <div class="modal-footer">
	    	            
	    	        </div>
	    	    </div>
	    	</div>
	    </div>
			    <!-- end edit article -->
			    <!-- comment article -->
			    <div class="modal fade" role="dialog" id="comment_article_box">
			        <div class="modal-dialog" role="document">
			            <div class="modal-content">
			                <div class="modal-header">
                                <h5 class="modal-title">Comment article</h5>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
			                </div>
			                <div class="modal-body">
			                    <form action="action.php?action=commentArticle" method="POST">
			                        <div class="form-group">
			                            <textarea class="form-control" name="comment_article_input" id="comment_article_input" placeholder="Write comment for this article..."></textarea>
			                            <input type="text" name="commentArticleId" id="commentArticleId" value="" hidden/>
			   	                    </div>
			   	                    <button type="submit" class="btn btn-primary" id="comment_article_btn">Comment</button>
			   	                </form>
			    	        </div>
			    	        <div class="modal-footer">
			    	        </div>
			    	    </div>
			    	</div>
			    </div>
			    <!-- end of comment article-->
			    <!-- share article -->
			    <div class="modal fade" role="dialog" id="share_article_box">
			        <div class="modal-dialog" role="document">
			            <div class="modal-content">
			                <div class="modal-header">
                                <h5 class="modal-title">Share article</h5>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
			                </div>
			                <div class="modal-body">
			                    <form action="action.php?action=shareArticle" method="POST">
			                        <div class="form-group">
			                            <textarea class="form-control" name="share_article_input" id="share_article_input" placeholder="Write something about this article..."></textarea>
			                            <input type="text" name="shareArticleId" id="shareArticleId" value="" hidden/>
			   	                    </div>
			   	                    <button type="submit" class="btn btn-primary" id="share_article_btn">Share</button>
			   	                </form>
			    	        </div>
			    	        <div class="modal-footer">
			    	        </div>
			    	    </div>
			    	</div>
			    </div>
			    <!-- end of share article-->
			    <!-- block article modal-->
			    <div class="modal fade" role="dialog" id="block_article_box">
        	        <div class="modal-dialog" role="document">
        	            <div class="modal-content">
        	                <div class="modal-header">
                                <h5 class="modal-title">Block article</h5>
                                <button type="button" class="close" data-dismiss="modal">&times;</button>
        	                </div>
        	                <div class="modal-body">
        	                    <form action="action.php?action=blockArticle&page=home" method="POST">
        	                        <div class="form-group">
        	                            <textarea class="form-control" name="block_article_input" id="block_article_input" placeholder="Whats wrong with this article?" required></textarea>
        	                            <input type="text" name="blockArticleId" id="blockArticleId" value="" hidden/>
        	   	                    </div>
        	   	                    <button type="submit" class="btn btn-danger" id="block_article_btn">Block</button>
        	   	                </form>
        	    	        </div>
        	    	        <div class="modal-footer">
        	    	        </div>
        	    	    </div>
        	    	</div>
        	    </div>
	    
				<!-- article section -->
				<!-- article box -->
				<div id="article_section">
				<?php
				    if(isset($_SESSION['id'])){
				        $followers = Follow::getFollowerByID($id);
				        if(sizeof($followers)>0){
				            echo '<h2 style="display:block;">Followed Member</h2>';
    				        foreach($followers as $follower){
    				            $followedArticle = new Article(Article::getFollowedArticle($follower->follower));
    				            $followArticleUser = new User(User::getUserByID($follower->follower));
    				            $printDateDiff = getDateDiff($followedArticle->date_posted);
                                $content = $followedArticle->content;
                                $newLineCount = substr_count( $content, "\n" );
                                
                                if($newLineCount>7){
                                    $newLineCount = 7;
                                }
                                $wordWrapCount = 500 - (50 * $newLineCount);
                                if (strlen($followedArticle->content) > $wordWrapCount){
                                    $content = substr($followedArticle->content, 0, $wordWrapCount) . '...';
                                }
    				            echo '<div id="article_box">
                            			<div id="article_owner">
                            			    ';
                            			    echo '
                            				<table>
                                                <tr>
                                                   <td rowspan="2"> 
                                                       <a href="view_edit_profile.php?memberid='.$followArticleUser->id.'">
                                                       <img src="'.$followArticleUser->getProfilePic().'" style="width: 64px; height: 64px; border-radius: 32px;"></a>
                                                   </td> 
                                                    <td>
                                                        <a href="view_edit_profile.php?memberid='.$followArticleUser->id.'">
                                                        <h2 id="article_username">'.htmlspecialchars($followArticleUser->name) .'</h2></a> shared an article
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>
                                                        '.$printDateDiff.'
                                                    </td>
                                                </tr>
                                            </table>
                            			</div>
                            			<div id="article_content">
                            			<!-- show category -->
                            			<div id="categoryBox">Category : '. $followedArticle->type .'</div>
                            			<!-- end category -->';
                                            echo '
                            				<a href="view_article.php?aid='. $followedArticle->id.'"><h4>'. htmlspecialchars($followedArticle->title) .'</h4></a>
                            				<div id="article_content_text">
                            				<pre>'. htmlspecialchars($content);
                            				if (strlen($followedArticle->content) > 500){
                                                echo '<a href="view_article.php?aid='. $followedArticle->id.'">(more)</a>';
                                            }
                            				echo '</pre></div>
                            			</div>
                            			<br>
                            	    </div> 
                            	    <br>';
    				        }
				        }
				        
				    }
				    $list = Share::getAllShare(20);
				    $tempMonth = null;
                    for($x=0; $x<sizeof($list);$x++) {
                        $article = new Article(Article::getArticleByID($list[$x]->article));
                        $user = new User(User::getUserByID($list[$x]->user));
                        $articleUser = new User(User::getUserByID($article->post_by));
                        $monthlySort = date('F Y',strtotime($list[$x]->date_shared));
                        if($monthlySort!=$tempMonth){
                            echo '<h2 style="display:block;">'. $monthlySort .'</h2>';
                        }
                        $tempMonth = date('F Y',strtotime($list[$x]->date_shared));
                        //$article->content = str_replace("<br />", "<br/>", $article->content);
                        //$article->content = str_replace(" ", "&nbsp;", $article->content);
                        $printDateDiff = getDateDiff($list[$x]->date_shared);
                        $content = $article->content;
                        $newLineCount = substr_count( $content, "\n" );
                        if($newLineCount>7){
                            $newLineCount = 7;
                        }
                        $wordWrapCount = 500 - (50 * $newLineCount);
                        if (strlen($article->content) > $wordWrapCount){
                            $content = substr($article->content, 0, $wordWrapCount) . '...';
                        }
        				echo '<div id="article_box">
        					<div id="article_owner">
        					    ';
        					    if(isset($_SESSION['id'])){
            					    if($articleUser->id == $id) {
            					        echo '
            					        <button type="button" class="btn btn-danger" id="delete_article_button"
            					            onclick="deleteArticle('. $list[$x]->id .')">Delete</button>
            					        <button type="button" class="btn btn-info" id="edit_article_button" onclick="openEditModal('. $article->id .')">Edit</button>
            					        <button type="button" id="hiddenEditModalBtn" data-toggle="modal" data-target="#edit_article_box" style="display:none">Test</button>
            					        ';
        					        
            					    } else {
            					        if($user->id!=$id){
            					            echo '<button class="btn btn-success" id="follow_button" 
            					            onclick="followMember(\''. $articleUser->id .'\')">';
            					            if(Follow::getFollower($_SESSION['id'],$articleUser->id)){
            					                echo 'Followed';
            					            }else{
            					                echo 'Follow';
            					            }
            					            echo '</button>';
            					        }if($account->type == 0){
            					            echo '<button type="button" class="btn btn-danger" 
            					            id="delete_article_button" data-toggle="modal" data-target="#block_article_box" onclick="openBlockModal('. $article->id .')">Block</button>';
            					        }
            					    }
        					    }
        					    echo '
        						<table>
                                    <tr>
                                       <td rowspan="2"> 
                                           <a href="view_edit_profile.php?memberid='.$user->id.'">
                                           <img src="'.$user->getProfilePic().'" style="border-radius: 32px;" id="account_img"></a>
                                       </td> 
                                        <td>
                                            <a href="view_edit_profile.php?memberid='.$user->id.'">
                                            <h2 id="article_username">'.htmlspecialchars($user->name) .'</h2></a> shared an article
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>
                                            '.$printDateDiff.'
                                        </td>
                                    </tr>
                                </table>
        					</div>
        					<div id="article_content">
        					<!-- show category -->
        					<div id="categoryBox">Category : '. $article->type.'</div>
        					<!-- end category -->';
        					    if($list[$x]->reply!=null){
				                    echo '<h4 style="margin-bottom:20px">'.htmlspecialchars($list[$x]->reply).'</h4>';
				                }
				                echo '
        						<a href="view_article.php?aid='. $article->id.'"><h4>'. htmlspecialchars($article->title) .'</h4></a>
        						<div id="article_content_text">
        						<pre>'. htmlspecialchars($content);
        						if (strlen($article->content) > 500){
                                    echo '<a href="view_article.php?aid='. $article->id.'">(more)</a>';
                                }
        						echo '</pre></div>
        					    <p class="article_total_likes" id="likesTotal'.$article->id.'">'. $article->getTotalLikes() .' Likes</p>
        					    <p id="article_total_comments">'. $article->getTotalComments() .' Comment</p>
        					</div>
        					<hr>
        					<div id="article_option">
        						<div class="container-fluid">
        							<div class="row" style="width:100%">
        								<table style="width:100%">
        								    <tr>
    								        <td id="likeButtonCol" >
    								            <button type="button" id="likeBtn'.$article->id.'"  class="likeButton" onclick="likeArticle('. $article->id .')">
    									        <p id=likebutton'.$article->id.' style="display:inline;"><i class="fas fa-thumbs-up"></i> 
    									        ';
    									        if($article->getLikeById($member->id)){
    									            echo 'Liked';
    									        }else{
    									            echo 'Like';
    									        }
    									         
    									        echo '</p></button>
    								        </td>
        								        
    								        <td id="commentButtonCol" >
    								            <button type="button" class="likeButton"
    								            onclick=';
    								            if(isset($_SESSION['id'])){
    								                echo '"commentArticle('.$article->id.')" 
    								                data-toggle="modal" data-target="#comment_article_box"';
    								            }else{
    								                echo '"alert(\'You needed to sign up to comment this article.\')"';
    								            }
    								            echo '><i class="fas fa-comments"></i>
        									  Comment</button>
    								        </td>
    								        
    								        <td id="shareButtonCol">
    								            <button type="button" class="likeButton" 
    								            onclick=';
    								            if(isset($_SESSION['id'])){
    								                echo '"shareArticle('.$article->id.')" 
    								                data-toggle="modal" data-target="#share_article_box"';
    								            }else{
    								                echo '"shareAlert()"';
    								            }
    								            echo ' ><i class="fas fa-share"></i>
        									  Share</button>
    								        </td>
        								    </tr>
        								</table>
        								
        							</div>
        						</div>
        					</div>
    				    </div> 
    				    <br>';
                }
				?>
				<br>
				</div>
				<!-- end article box -->
				<!-- end article section -->
			</div>
			<div class="col-sm-3">
				<div id="event_container">
				    <a href="view_event_list.php">
    					<img src="../img/calendar.png" style="width: 64px; height: 64px;">
    					<h3 style="display: inline; vertical-align: middle; color: black;">Events</h3>
    				</a>
				</div>
				<div id="event_box_1">
				    <?php
				    $events = Event::getAllEvents(10);
				    for($x=0; $x<sizeof($events);$x++) {
    				        if(strtotime($events[$x]->end_date)>strtotime(date('m/d/Y', time()))){
        					echo '<a href="view_event.php?eid='.$events[$x]->id.'"> 
        					<h4>
        					<!-- event name -->
        					'. htmlspecialchars($events[$x]->title) .'
        					</h4>
        					<h6>
        					<!-- event date -->
        					'. $events[$x]->start_date.'
        					</h6> </a>';
				        }
				    }
					?>
					<hr>
					<a href="view_event_list.php">View All Event</a>
				</div>
			</div>
		</div>
	</div>
</body>
</html>