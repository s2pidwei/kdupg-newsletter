<?php
    include("../config.php");
    session_start();
    $id = null;
    $member = new User();
    if(isset($_SESSION['id'])){
        $id = $_SESSION['id'];
        $member = new User(User::getUserByID($id));
        $account = Account::getAccByID($id);
    }
    if(isset($_GET['aid'])){
        $articleId = $_GET['aid'];
        $article = new Article(Article::getArticleByID($articleId));
    }
    function getDateDiff($datePosted){
        $datenow = date('Y-m-d H:i:s');
        $dateDiff = abs(strtotime($datenow) - strtotime($datePosted));
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
    	<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
        <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
        <script src="https://kit.fontawesome.com/c1620fa448.js" crossorigin="anonymous"></script>
    	<link rel="stylesheet" href="../css/theme1.css">
    	<script type="text/javascript" src="../include/Template/javascript.js"></script>
    	<script type="text/javascript" src="../include/Template/ajax.js"></script>
    	<title>Software Engineering</title>
    </head>
    <body>
        
        <?php 
        include(TEMPLATE_PATH."header.php");
        $user = new User(User::getUserByID($article->post_by));
        ?>
        <!-- edit article (pop up) -->
	    <div class="modal fade bd-example-modal-lg" role="dialog" id="edit_article_box">
	        <div class="modal-dialog modal-lg" role="document" id="modal_content_box"  > <!---->
	            <div class="modal-content" >
	                <div class="modal-header" id="edit_article_header">
                        <h1 class="modal-title">Edit article</h1>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
	                </div>
	                <br>
	                <div class="modal-body" id="article_edit_contents">
	                    
	                    <form action="action.php?action=editArticle2&articleId=<?php echo $article->id ?>" method="POST">
	                        <h5 class="modal-title" style="margin-top:20px; margin-bottom:20px ">Edit Article Title</h5>
	                        <select class="btn btn-secondary dropdown-toggle-menu" name="categoryOption" required>
                                <option value="" disabled selected>Select Category</option>
                                <?php
                                $categories = Category::getAllCategory();
                                foreach ($categories as $category) {
                                 echo '<option value="'.$category["type"].'">'.$category["type"].'</option>';
                                }
                                ?>
                            echo '
                            </select>
	                        <div class="form-group" >
	                            <input type="text" class="form-control" name="edit_article_title_input" id="edit_article_title_input" placeholder="Article title" value="<?php echo htmlspecialchars($article->title); ?>" required>
	                            
	                            <h5 class="modal-title" style="margin-top:20px">Edit Article Contents</h5>
	                            <textarea class="form-control" name="edit_article_input" id="edit_article_input" placeholder="Write something..." required><?php echo htmlspecialchars($article->content); ?></textarea>
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
	                            <input type="text" name="shareArticleId" id="shareArticleId" value="
	                            <?php echo $article->id?>" hidden/>
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
	    <!-- report article box-->
        <div class="modal fade" role="dialog" id="report_article_box">
	        <div class="modal-dialog" role="document">
	            <div class="modal-content">
	                <div class="modal-header">
                        <h5 class="modal-title">Report article</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
	                </div>
	                <div class="modal-body">
	                    <form action="action.php?action=reportArticle" method="POST">
	                        <div class="form-group">
	                            <textarea class="form-control" name="report_article_input" id="report_article_input" placeholder="Whats wrong with this article?" required></textarea>
	                            <input type="text" name="reportArticleId" id="reportArticleId" value="" hidden/>
	   	                    </div>
	   	                    <button type="submit" class="btn btn-danger" id="report_article_btn">Report</button>
	   	                </form>
	    	        </div>
	    	        <div class="modal-footer">
	    	        </div>
	    	    </div>
	    	</div>
	    </div>
	    <!-- Admin block article -->
	    <div class="modal fade" role="dialog" id="block_article_box">
	        <div class="modal-dialog" role="document">
	            <div class="modal-content">
	                <div class="modal-header">
                        <h5 class="modal-title">Block article</h5>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
	                </div>
	                <div class="modal-body">
	                    <form action="action.php?action=blockArticle&page=view" method="POST">
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
        <div class="container" id="view_article_div">
            <div class="row" id="view_article_div2">
            <div class="col-sm-4">
        		<table>
                    <tr>
                        <td rowspan="2" width="70px">
                            <a href="view_edit_profile.php?memberid=<?php echo $user->id; ?>">
                                <img src="<?php echo $user->getProfilePic() ?>" 
                            style="width: 64px; height: 64px; border-radius: 32px;"></a>
                        </td> 
                        <td>
                            <h2 id="article_username"><?php echo htmlspecialchars($user->name) ?></h2>
                        </td>
                            </tr>
                            <tr>
                        <td>
                            
                            <?php echo getDateDiff($article->date_posted); ?>
                        </td>
                    </tr>
                </table>
        	</div>
        	<div class="col-sm-8">
        	    <?php 
        	    if(isset($_SESSION["id"])){
        	        if($member->id != $user->id){
        	            echo '<button class="btn btn-success" id="follow_button">Follow</button>';
        	            if($account->type != 0){
        	            echo '<button class="btn btn-danger" id="follow_button" data-toggle="modal" data-target="#report_article_box" onclick="reportArticle(\''. $article->id.'\')">Report this article</button>';
        	            }else if($account->type == 0){
        	                echo '<button class="btn btn-danger" id="follow_button" data-toggle="modal" data-target="#block_article_box" onclick="openBlockModal('. $article->id .')">Block this article</button>';
            	        }
        	        }else if($member->id == $user->id){
        	            echo '<button class="btn btn-success" id="follow_button"
        	            data-toggle="modal" data-target="#edit_article_box">Edit Article</button>';
        	            echo '<button class="btn btn-danger" id="follow_button" 
        	            onclick="deleteArticle(\''.$article->id.'\')">Delete Article</button>';
        	        }
        	    }else{
        	        echo '<button class="btn btn-danger" id="follow_button" data-toggle="modal" data-target="#report_article_box" onclick="reportArticle(\''. $article->id.'\')">Report this article</button>';
        	    }
        	    ?>
        	</div>
            </div>
                
                <div id="view_article_box">
                    <h1><?php echo htmlspecialchars($article->title) ?></h1>
                    <div id="view_article_contents">
                    <p><?php echo htmlspecialchars($article->content) ?></p>
                </div>
                
                <?php
                if($article->status == 1){
                echo '
                <div id="article_option">
        			<div class="container-fluid">
        			    <div class="row">
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
								        echo '</button>
				                </td>
							        
						        <td id="commentButtonCol" >
							            <button type="button" class="likeButton" onclick="setFocus()">
									<i class="fas fa-comments"></i>
									  Comment</button>
							    </td>
						        
						        <td id="shareButtonCol">
						            <button type="button" class="likeButton" data-toggle="modal" data-target="#share_article_box"';
						        if(!isset($_SESSION['id'])){
                                    echo 'disabled';
                                }
						        echo '>
								<i class="fas fa-share"></i>
								  Share</button>
						        </td>
							    </tr>
							</table>
        			    </div>
        		    </div>
        		</div>';
                }
                ?>
                </div><!--Article Contents-->
            
                <!--comment box-->
                
                <!-- write comment -->
                <?php 
                if($article->status == 1){
                echo '
                <div id="view_commment_box" style="margin-bottom:10px;">
                    <div>
                        <textarea id="write_comment_box" style="width:100%;" placeholder="Enter your comment here"  onKeyPress="writeComment('. $article->id .')" ';
                if(!isset($_SESSION['id'])){
                    echo 'disabled';
                }
                echo '></textarea>
                    </div>
                    <!-- end write comment -->
                    <div style="margin: 10px;"></div> <!-- 20px space -->
                    <div id="all_comments">';
                    
                    $comments = array();
                    $comments = Comment::getAllComment(10,$article->id);
                    if (empty($comments)){
                        echo 'No comments.';
                    }
                    for($x=0; $x<sizeof($comments);$x++) {
                        $commentUser = new User(User::getUserByID($comments[$x]->user));
                        echo '
                        <!-- comments, likes and shares -->
                        <div id="comment_box">
                            <table>
                                <tr>
                                    <td rowspan="2" ><!-- prof pic -->
                                        <img src="'. $commentUser->getProfilePic().'" id="header_profile_pic">
                                    </td>
                                    <td colspan="2" >
                                        <p style="font-weight:bold; display:inline-block;">'.htmlspecialchars($commentUser->name).' </p> <!-- name -->
                                        <p style="display:inline-block;">'.getDateDiff($comments[$x]->date_posted) .' </p><!-- time posted -->
                                    </td>
                                </tr>
                                
                                <tr>
                                    <td></td>
                                    <td>
                                        '. htmlspecialchars($comments[$x]->comment) .'
                                        <!-- comment content -->
                                    </td>
                                    <td></td>
                                </tr>    
                            </table>
                            <br>
                        </div> <!-- end of one person"s comment  -->
                    <br>';
                    }
                
                echo    '<!-- end comment box -->
                    </div>';
                }
                    
                ?>
                </div>
            </div>
        </div>
    </body>
</html>