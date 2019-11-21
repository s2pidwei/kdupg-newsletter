<?php
require('../config.php');
session_start();
$view = isset($_GET['action']) ? $_GET['action'] : "";

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

switch($view){
    case 'commentArticle':
        commentArticle();
        break;
    case 'pendingUser':
        pendingUser();
        break;
    case 'pendingArticle':
        pendingArticle();
        break;
    case 'pendingEvent':
        pendingEvent();
        break;
    case 'reportedUser':
        reportedUser();
        break;
    case 'reportedArticle':
        reportedArticle();
        break;
    case 'reportedEvent':
        reportedEvent();
        break;
    case 'searchArticle':
        searchArticle();
        break;
    case 'followMember':
        followMember();
        break;
    case 'showParticipant':
        showParticipant();
        break;
}

function commentArticle(){
    $articleId = $_GET['articleId'];
    $comment = $_POST['comment'];
    $userId = $_SESSION['id'];
    $newComment = new Comment();
    $newComment->article = $articleId;
    $newComment->user = $userId;
    $newComment->comment = $comment;
    $newComment->insert();
    
    $comments = Comment::getAllComment(10,$articleId);
    for($x=0; $x<sizeof($comments);$x++) {
        $commentUser = new User(User::getUserByID($comments[$x]->user));
        echo '<div id="comment_box">
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
        <a href="" style="float:right;">
        <img src="../img/thumbs_up.png" style="width: 20px; height: 20px;">
        Like</a>
        <br>';
    }
}

function pendingUser(){
    $pendingUserIds = Account::getPendingUsers();
    if(sizeof($pendingUserIds) > 0){
        echo '<table class="table">
            <thead class="thead-light">
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Contact</th>
                    <th id="actCol">Action</th>
                </tr>
            </thead>
            <tbody>';
        for($x=0; $x<sizeof($pendingUserIds);$x++) {
            $pendingUser = new User(User::getUserByID($pendingUserIds[$x]['id']));
            echo '
                <tr>
                    <td>'. $pendingUser->id .'</td>
                    <td>'. htmlspecialchars($pendingUser->name) .'</td>
                    <td>'. htmlspecialchars($pendingUser->email) .'</td>
                    <td>'. htmlspecialchars($pendingUser->contact) .'</td>
                    <td>
                        <button class="btn btn-success" id="actionBtn" 
                        onclick="approvePending(\'User\',\''.$pendingUserIds[$x]['id'].'\')"
                        ><i class="fas fa-check"></i></button>
                        <button class="btn btn-danger" id="actionBtn"
                        onclick="rejectPending(\'User\',\''.$pendingUserIds[$x]['id'].'\')"
                        ><i class="fas fa-times"></i></button>
                    </td>
                </tr>';
        }
        echo '</tbody>
        </table>';
    }else{
        echo '<p>No new users.</p>';
    }
}

function pendingArticle(){
    $pendingArticles = Article::getPendingArticles();
    if(sizeof($pendingArticles)>0){
    echo '<table class="table" id="tableWordWrap">
        <thead class="thead-light">
            <tr>
                <th id="cellIdWidth">ID</th>
                <th id="cellTitleWidth">Title</th>
                <th id="cellWidthGuide">Content</th>
                <th>By</th>
                <th id="actCol">Action</th>
            </tr>
        </thead>
        <tbody>';
    for($x=0; $x<sizeof($pendingArticles);$x++) {
        $authorUser = new User(User::getUserByID($pendingArticles[$x]->post_by));
        $content = $pendingArticles[$x]->content;
        if (strlen($pendingArticles[$x]->content) > 200){
            $content = substr($pendingArticles[$x]->content, 0, 200) . '...';
        }
        echo '<tr>
            <td>'.$pendingArticles[$x]->id.'</td>
            <td id=cellWordWrap><a href="view_article.php?aid='. $pendingArticles[$x]->id .'"' 
            .htmlspecialchars($pendingArticles[$x]->title).'</a></td>
            <td id="cellWordWrap">'.htmlspecialchars($content);
            if (strlen($pendingArticles[$x]->content) > 200){
                echo '<a href="">(more)</a>';
            }
            echo '</td>
            <td><a href="view_edit_profile.php?memberid='. $authorUser->id .'">'.htmlspecialchars($authorUser->name).'</a></td>
            <td>
                <button class="btn btn-success" id="actionBtn"
                onclick="approvePending(\'Article\','.$pendingArticles[$x]->id.')"
                ><i class="fas fa-check"></i></button>
                <button class="btn btn-danger" id="actionBtn"
                onclick="rejectPending(\'Article\','.$pendingArticles[$x]->id.')"
                ><i class="fas fa-times"></i></button>
            </td>
        </tr>';
    }
    echo '</tbody>
    </table>';
    }else{
        echo '<p>No new article</p>';
    }
}

function pendingEvent(){
    $pendingEvent = Event::getPendingEvents();
    if(sizeof($pendingEvent)>0){
    echo '<table class="table" id="tableWordWrap">
        <thead class="thead-light">
            <tr>
                <th id="cellIdWidth">ID</th>
                <th id="cellTitleWidth">Title</th>
                <th id="cellWidthGuide">Content</th>
                <th>By</th>
                <th id="actCol">Action</th>
            </tr>
        </thead>
        <tbody>';
    foreach($pendingEvent as $event){
        $eventUser = new User(User::getUserByID($event->post_by));
        $content = $event->content;
        if (strlen($event->content) > 200){
            $content = substr($event->content, 0, 200) . '...';
        }
        echo '<tr>
            <td>'.htmlspecialchars($event->id) .'</td>
            <td><a href="view_event.php?eid='. $event->id .'">'.htmlspecialchars($event->title) .'</a></td>
            <td id="cellWordWrap">'. htmlspecialchars($content) .'</td>
            <td><a href="view_edit_profile.php?memberid='. $eventUser->id .'">'.
            htmlspecialchars($eventUser->name) .'</a></td>
            <td>
                <button class="btn btn-success" id="actionBtn"
                onclick="approvePending(\'Event\','.$event->id.')"
                ><i class="fas fa-check"></i></button>
                <button class="btn btn-danger" id="actionBtn"
                onclick="rejectPending(\'Event\','.$event->id.')"
                ><i class="fas fa-times"></i></button>
            </td>
        </tr>';
    }
    echo '</tbody>
    </table>';
    }else{
        echo 'No new events';
    }
}

function reportedUser(){
    $list = viewReported("User");
    if(sizeof($list)>0){
    echo '<table class="table">
            <thead class="thead-light">
                <tr>
                    <th id="cellIdWidth">ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Contact</th>
                    <th>Reason</th>
                    <th id="actCol">Action(Block)</th>
                </tr>
            </thead>
            <tbody>';
    foreach($list as $user){
        $reportedUser = new User(User::getUserByID($user['item_id']));
        echo '
            <tr>
                <td>'.$user['item_id'].'</td>
                <td><a href="view_edit_profile.php?memberid='. $reportUser->id .'">'.htmlspecialchars($reportedUser->name).'</a></td>
                <td>'.htmlspecialchars($reportedUser->email).'</td>
                <td>'.htmlspecialchars($reportedUser->contact).'</td>
                <td>'.htmlspecialchars($user['reason']).'</td>
                <td>
                    <button class="btn btn-success" id="actionBtn"><i class="fas fa-check"></i></button>
                    <button class="btn btn-danger" id="actionBtn" onclick="deleteReport('.$user['id'].',\'User\')"><i class="fas fa-times"></i></button>
                </td>
            </tr>';
    }
    echo '</tbody></table>';
    }else{
        echo 'No user report.';
    }
}

function reportedArticle(){
    $list = viewReported("Article");
    if(sizeof($list)>0){
    echo '<table class="table" id="tableWordWrap">
        <thead class="thead-light">
            <tr>
                <th>ID</th>
                <th>Title</th>
                <th id="cellWidthGuide">Content</th>
                <th>By</th>
                <th>Reason</th>
                <th id="actCol">Action</th>
            </tr>
        </thead>
        <tbody>';
    foreach($list as $article){
        $reportedArticle = new Article(Article::getArticleByID($article['item_id']));
        $articleUser = new User(User::getUserByID($reportedArticle->post_by));
        echo '
            <tr>
                <td>'.$article['item_id'].'</td>
                <td><a href="view_article.php?aid='. $reportedArticle->id .'">'.htmlspecialchars($reportedArticle->title).'</a></td>
                <td id="cellWordWrap">'.htmlspecialchars($reportedArticle->content).'</td>
                <td><a href="view_edit_profile.php?memberid='.$articleUser->id.'">'.htmlspecialchars($articleUser->name).'</a></td>
                <td>'.htmlspecialchars($article['reason']).'</td>
                <td>
                    <button class="btn btn-success" id="actionBtn"><i class="fas fa-check"></i></button>
                    <button class="btn btn-danger" id="actionBtn" onclick="deleteReport('.$article['id'].',\'Article\')"><i class="fas fa-times"></i></button>
                </td>
            </tr>';
    }
    echo '</tbody>
    </table>';
    }else{
        echo 'No article report.';
    }
}

function reportedEvent(){
    $list = viewReported("Event");
    if(sizeof($list)>0){
    echo '<table class="table" id="tableWordWrap">
            <thead class="thead-light">
                <tr>
                    <th>ID</th>
                    <th>Title</th>
                    <th id="cellWidthGuide">Content</th>
                    <th>By</th>
                    <th>Reason</th>
                    <th id="actCol">Action</th>
                </tr>
            </thead>
            <tbody>';
    foreach($list as $event){
        $reportedEvent = new Event(Event::getEventByID2($event['item_id']));
        $articleUser = new User(User::getUserByID($reportedEvent->post_by));
        echo '
                <tr>
                    <td>'.$event['item_id'].'</td>
                    <td><a href="view_event.php?eid='. $reportedEvent->id .'">'.htmlspecialchars($reportedEvent->title).'</a></td>
                    <td id="cellWordWrap">'.htmlspecialchars($reportedEvent->content).'</td>
                    <td><a href="view_edit_profile.php?memberid='. $articleUser->id .'">'.htmlspecialchars($articleUser->name).'</a></td>
                    <td>'.htmlspecialchars($event['reason']).'</td>
                    <td>
                        <button class="btn btn-danger" id="actionBtn" onclick="deleteReport('.$event['id'].',\'Event\')"><i class="fas fa-times"></i></button>
                    </td>
                </tr>';
    }
    echo '</tbody></table>';
    }else{
        echo 'No event report.';
    }
}

function viewReported($type){
    $rows = array();
    $con = new PDO(DBhost,DBuser,DBpass);
    $query = "SELECT * FROM report WHERE type LIKE :type ORDER BY date DESC";
    $st = $con->prepare($query);
    $st->bindValue(":type", $type, PDO::PARAM_STR);
    $st->execute();
    $rows = $st->fetchAll();
    return $rows;
}

function searchArticle(){
    $content = $_GET['content'];
    $type = $_GET['type'];
    $id = null;
    if(isset($_SESSION['id'])){
        $id = $_SESSION['id'];
    }
    $list = array();
    $list = Article::searchArticles($content, $type);
    if(!empty($list)){
    foreach ($list as $article) {
        $user = new User(User::getUserByID($article->post_by));
        $printDateDiff = getDateDiff($article->date_posted);
        $content = $article->content;
        if (strlen($article->content) > 500){
            $content = substr($article->content, 0, 500) . '...';
        }
		echo '<div id="article_box">
			<div id="article_owner">
			    ';
			    if($user->id == $id) {
			        echo '
			        <button type="button" class="btn btn-danger" id="delete_article_button"
			            onclick="deleteArticle('. $article->id .')">Delete</button>
			        <button type="button" class="btn btn-info" id="edit_article_button" onclick="openEditModal('. $article->id .')">Edit</button>
			        <button type="button" id="hiddenEditModalBtn" data-toggle="modal" data-target="#edit_article_box" style="display:none">Test</button>
			        ';
			        
			    }
			    if($user->id != $id && isset($_SESSION['id'])){
			        echo '<button class="btn btn-success" id="follow_button">Follow</button>';
			    }
			    echo '
				<table>
                    <tr>
                       <td rowspan="2"> 
                           <a href="view_edit_profile.php?memberid='.$user->id.'">
                           <img src="'.$user->getProfilePic().'" style="width: 64px; height: 64px; border-radius: 32px;"></a>
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
			<div id="categoryBox">Category : '. $article->type .'</div>
			<!-- end category -->';
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
			<br>
	    </div> 
	    <br>';
    }
    }else{
        echo 'No article found.';
    }
}

function followMember(){
    $followed = $_GET['follow'];
    $id = $_SESSION['id'];
    $userId = $_GET['follower'];
    $follow = new Follow();
    $follow->user = $id;
    $follow->follower = $userId;
    if($followed == "true"){
        if($follow->delete()){
            echo "Follow";
        }
    }else if($followed == "false"){
        if($follow->insert()){
            echo "Followed";
        }
    }
}

function showParticipant(){
    $eventid = $_GET['eventid'];
    $participants = new Event_Participant();
    $participants->event = $eventid;
    $participants_id = $participants->getAllParticipant(20);
    echo '<h4>Participants</h4>';
    foreach ($participants_id as $user_id) {
        $participant_name = new User(User::getUserByID($user_id->user));
        echo '<a href="view_edit_profile.php?memberid='. $participant_name->id .'">'.
        htmlspecialchars($participant_name->name).'</a><br>';
    }
}
?>