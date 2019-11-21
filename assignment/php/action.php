<?php
require('../config.php');
session_start();
$view = isset($_GET['action']) ? $_GET['action'] : "";

switch($view){
    //Account/Profile part
    case 'logout': //Logout function
        logout();
        break;
    case 'signup': // Sign up function
        signup();
        break;
    case 'editProfile': //Edit user Profile
        editProfile();
        break;
    case 'newArticle': //Create new Article
        newArticle();
        break;
    case 'forget_pw':
        forget_pw();
        break;
    case 'update_pw':
        update_pw();
        break;
    
    //Article Part
    case 'openEditArticle':
        openEditArticle();
        break;
    case 'editArticle': //Edit existing Article
        editArticle();
        break;
    case 'editArticle2':
        editArticle2();
        break;
    case 'deleteArticle': //Delete Article
        deleteArticle();
        break;
    case 'likeArticle':// Like Article
        likeArticle();
        break;
    case 'updateLikes': //Update total Likes in Article
        updateLikes();
        break;
    case 'commentArticle':
        commentArticle();
        break;
    case 'shareArticle': //Share Article
        shareArticle();
        break;
        
    //Event part
    case 'createEvent':
        createEvent();
        break;
    case 'closeEvent':
        closeEvent();
        break;
    case 'joinEvent':
        joinEvent();
        break;
    case 'leaveEvent':
        leaveEvent();
        break;
    case 'inviteToEvent':
        inviteToEvent();
        break;

    //Admin part
    case 'updateAnnouncement':
        updateAnnouncement();
        break;
    case 'approveUser':
        approveUser();
        break;
    case 'approveArticle':
        approveArticle();
        break;
    case 'approveEvent':
        approveEvent();
        break;
    case 'rejectUser':
        rejectUser();
        break;
    case 'rejectArticle':
        rejectArticle();
        break;
    case 'rejectEvent':
        rejectEvent();
        break;
    case 'blockArticle':
        blockArticle();
        break;
        
    //Report
    case 'reportUser':
        reportUser();
        break;
    case 'reportArticle':
        reportArticle();
        break;
    case 'reportEvent':
        reportEvent();
        break;
    case 'deleteReport':
        deleteReport();
        break;
}

function logout(){
    session_destroy();
    header("Location: ". SITEURL . "php/home.php");
}

function signup(){
    $existedUserID = null;
    $existedUserEmail = null;
    if(isset($_POST['usr_id']) && isset($_POST['usr_name']) && isset($_POST['usr_email']) && isset($_POST['usr_pass']) && isset($_POST['usr_passc']) && isset($_POST['usr_contact'])){
        
        $existedUserID = User::getUserByID($_POST['usr_id']);
        $existedUserEmail = User::getUserByEmail($_POST['usr_email']);
        $password = md5($_POST['usr_pass']);
        $valid = true;
        
        if(empty($existedUserID) == false){
            $valid=false;
            echo '<script>alert("ID already used.");window.location = "'.SITEURL.'php/sign_up.php";</script>';
        }
        
        else if(empty($existedUserEmail) == false){
            $valid=false;
            echo '<script>alert("Email already used.");window.location = "'.SITEURL.'php/sign_up.php";</script>';
        }
        
        else if($_POST['usr_pass'] != $_POST['usr_passc']){
            $valid=false;
            echo '<script>alert("Password does not match.");window.location = "'.SITEURL.'php/sign_up.php";</script>';
        }
        
        else {
        $account = new Account();
        $account->id = $_POST['usr_id'];
        $account->pass = $password;
        $account->type = 1;
        $account->insert();
        
        
        $user = new User();
        $user->id = $_POST['usr_id'];
        $user->name = $_POST['usr_name'];
        $user->email = $_POST['usr_email'];
        $user->contact = $_POST['usr_contact'];
        $user->date_joined = date('Y-m-d H:i:s');
        $user->insert();
        
        echo '<script>alert("Account created. Please wait for the administrator to verify your account. Thank you.");window.location = "'.SITEURL.'php/home.php";</script>';
        }
    }
}

function editProfile(){
    $valid = true;
    $error="";
    if(file_exists($_FILES['usr_edit_picture']['tmp_name'])) {
        $valid = uploadUserPic();
    }
    if(isset($_POST['usr_edit_name']) && isset($_POST['usr_edit_email']) 
    && isset($_POST['usr_edit_contact']) && isset($_POST['usr_edit_desc'])){
        $user = new User(User::getUserByID($_SESSION['id']));
        $user->name = trim($_POST['usr_edit_name']);
        $user->email = trim($_POST['usr_edit_email']);
        $user->contact = trim($_POST['usr_edit_contact']);
        $user->description = trim($_POST['usr_edit_desc']);
        $user->update();
        echo "<script>alert('Profile Edited Successfully.');
        window.location.href = 'view_edit_profile.php?memberid=" . $_SESSION['id'] ."';</script>";
    }
}

function uploadUserPic(){
    $target_dir = "../upload/profile/";
    $userID = $_SESSION['id'];
    $error_msg = '';
    $uploadOk = 1;
    $filename = $_FILES['usr_edit_picture']['name'];
    $imageFileType = strtolower(pathinfo($filename,PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    if(isset($_POST["submit"])) {
        $check = getimagesize($filename);
        if($check !== false) {
            $uploadOk = true;
        } else {
            $error_msg = 'File is not an image.;';
            $uploadOk = false;
        }
    }
    // Check file size
    if ($_FILES["usr_edit_picture"]["size"] > 204800) {
        $error_msg = 'Sorry, your image is too large. Please lower the resolution of the image';
        $uploadOk = false;
    }
    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
        $error_msg = 'Sorry, only JPG, JPEG, PNG  files are allowed.;';
        $uploadOk = false;
    }
    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == false) {
        echo "<script>alert('$error_msg')";
        return false;
        
    // if everything is ok, try to upload file
    } else {
        $target_folder = $target_dir . $userID . '.png';
        if (imagepng(imagecreatefromstring(file_get_contents($_FILES["usr_edit_picture"]["tmp_name"])), $target_folder)) {
            return true;
        } else {
           echo "<script>alert('Sorry, there was an error uploading your file.');</script>";
           return false;
        }
    }
}

function newArticle(){
    $account = Account::getAccByID($_SESSION['id']);
    if(isset($_POST['new_title']) && isset($_POST['new_content'])){
        //Add article to Article table
        $article = new Article();
        $article->title = $_POST['new_title'];
        $article->content = $_POST['new_content'];
        $article->type = $_POST['categoryOption'];
        $article->post_by = $_SESSION['id'];
        if($account->type == 0){
            $article->status = 1;
        }else{
            $article->status = 0;
        }
        if($article->insert()){
            if($account->type ==0){
                $share = new Share();
                $share->user = $article->post_by;
                $share->article = $article->id;
                $share->date_shared = date('Y-m-d H:i:s');
                if($share->insert()){
                    echo '<script>alert("Article added successfully.");
                    window.location.href = "'.SITEURL.'php/home.php";</script>';
                }else{
                    echo '<script>alert("Error adding article. Please try again later.");
                    window.location.href = "'.SITEURL.'php/home.php";</script>';
                }
            }else{
                echo '<script>alert("Article added successfully. Please wait for administrator to review your article.");
                window.location.href = "'.SITEURL.'php/home.php";</script>';
            }
        }else{
            echo '<script>alert("Error adding article. Please try again later.");
            window.location.href = "'.SITEURL.'php/home.php";</script>';
        }
    }
}

function forget_pw() {
    $email = $_POST['forget_pw_email_input'];
    $user = User::getUserByEmail($email);
    $account = Account::getAccByID($user->id);
    
    if(!$user) {
        echo '<script>alert("Email does not exist in database!");window.history.back();</script>';
    } else {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $newPass = '';
        for ($i = 0; $i < 10; $i++) {
            $newPass .= $characters[rand(0, $charactersLength - 1)];
        }
        
        $account->pass = md5($newPass);
        $account->update();
        
        $msg ="A request was made to reset password, here is your new password:\n\n\t
        ".$newPass."\n\nPlease update your password as soon as possible.";
        
        mail($email, "Software Engineering", $msg);
        
        echo '<script>alert("Email sent.");window.location.href="'.SITEURL.'php/home.php";</script>';    
    }
}

function update_pw() {
    $account = Account::getAccByID($_SESSION['id']);
    $user = User::getUserByID($_SESSION['id']);
    $email = $user['email'];
    $currentPw = $account->pass;
    $validPw = $_POST['current_pw_input'];
    $newPw = $_POST['new_pw_input'];
    $confirmPw = $_POST['confirm_pw_input'];
    
    if(md5($validPw) == $currentPw) {
        if($newPw == $confirmPw) {
            if($newPw == $validPw){
                echo '<script>New password cannot be the same as current password!</script>';
            } else {
                $account->pass = md5($newPw);
                $account->update();
                $msg = "A request was made for update password, your password is now:\n\n\t
                ".$newPw;
                
                mail($email, "Software Engineering", $msg);
                echo'<script>alert("Password updated.");window.location.href="'.SITEURL.'php/home.php"</script>';
            }
        } else {
            echo '<script>alert("New password does not match!");window.history.back()</script>';
        }
    } else {
        echo '<script>alert("Current password does not match!");window.history.back()</script>';
    }
}

function openEditArticle(){
    $articleId = $_GET['articleId'];
    $article = new Article(Article::getArticleById($articleId));
    
    echo '<form action="action.php?action=editArticle&articleId='. $articleId .'" method="POST">
            <div class="form-group">
            <table style="width:100%">
                <tbody>
                <tr>
                 <td style="width:25%">       
                <h5 class="modal-title" style="margin-top:20px; margin-bottom:20px ">Edit Article Title</h5>
                </td>
                <!-- category -->
            <td style="width:75%">
            <select class="btn btn-secondary dropdown-toggle-menu" name="categoryOption" required>
            <option value="" disabled selected>Select Category</option>
            ';
            $categories = Category::getAllCategory();
            foreach ($categories as $category) {
             echo '<option value="'.$category["type"].'">'.$category["type"].'</option>';
            }
        echo '
        </select>
        </td>
        
        </tr>
        </tbody>
        </table>
        <!-- end category -->
        
                <input type="text" class="form-control" id="write_article_title_input" 
                    name="new_title" placeholder="Article title" value="'. htmlspecialchars($article->title) .'">
                    
                <h5 class="modal-title" style="margin-top:20px">Write Article Contents</h5>       
                <textarea class="form-control" id="edit_article_input" name="new_content" placeholder="Write something..." style="white-space: pre-line;">'. htmlspecialchars($article->content) .'</textarea>
               </div>
        <div class="modal-footer">
        <div class="col-md-12 text-center">
        <button type="submit" class="btn btn-primary" id="write_article_btn">Save</button>
        </div>
        </div>
           </form>';
}

function editArticle(){
    if(isset($_GET['articleId'])){
        $account = Account::getAccByID($_SESSION['id']);
        $articleId = $_GET['articleId'];
        $article = new Article(Article::getArticleByID($articleId));
        if(isset($_POST['edit_article_title_input']) && isset($_POST['edit_article_input'])){
            $article->title = $_POST['edit_article_title_input'];
            $article->content = $_POST['edit_article_input'];
            $article->type = $_POST['categoryOption'];
            if($account->type==0){
                $article->status = 1;
                $reason = array();
                $reason = $article->getRejectReason();
                
                if(sizeof($reason)>0){
                    $article->removeReject();
                }
                if($article->update()){
                    echo '<script>alert("Article updated successfully.");
                    window.location = "'.SITEURL.'php/home.php";</script>';
                }else{
                    echo '<script>alert("Error updating article. Please try again later.");
                    window.location = "'.SITEURL.'php/home.php";</script>';
                }
            }else{
                $article->status = 0;
                $reason = array();
                $reason = $article->getRejectReason();
                if(sizeof($reason)>0){
                    $article->removeReject();
                }
                if($article->update() && Share::deleteShareByArticle($articleId)){
                    echo '<script>alert("Article updated successfully. Please wait for administrator approval.");
                    window.location = "'.SITEURL.'php/home.php";</script>';
                }else{
                    echo '<script>alert("Error updating article. Please try again later.");
                    window.location = "'.SITEURL.'php/home.php";</script>';
                }
            }
        }
    }
}

function editArticle2(){
    if(isset($_GET['articleId'])){
         $account = Account::getAccByID($_SESSION['id']);
        $articleId = $_GET['articleId'];
        $article = new Article(Article::getArticleByID($articleId));
        if(isset($_POST['edit_article_title_input']) && isset($_POST['edit_article_input'])){
            $article->title = $_POST['edit_article_title_input'];
            $article->content = $_POST['edit_article_input'];
            $article->type = $_POST['categoryOption'];
            if($account->type==0){
                $article->status = 1;
                $reason = array();
                $reason = $article->getRejectReason();
                if(sizeof($reason)>0){
                    $article->removeReject();
                }
                if($article->update()){
                    echo '<script>alert("Article updated successfully.");
                    window.location = "view_article.php?aid='. $_GET['articleId'] .'";</script>';
                }else{
                    echo '<script>alert("Error updating article. Please try again later.");
                    window.location = "view_article.php?aid='. $_GET['articleId'] .'";</script>';
                }
                
            }else{
                $article->status = 0;
                $reason = array();
                $reason = $article->getRejectReason();
                if(sizeof($reason)>0){
                    $article->removeReject();
                }
                if($article->update() && Share::deleteShareByArticle($articleId)){
                    echo '<script>alert("Article updated successfully. Please wait for administrator approval.");
                    window.location = "view_article.php?aid='. $_GET['articleId'] .'";</script>';
                }else{
                    echo '<script>alert("Error updating article. Please try again later.");
                    window.location = "view_article.php?aid='. $_GET['articleId'] .'";</script>';
                }
            }
        }
    }
}

function deleteArticle(){
    if(isset($_GET['shareId'])){
        $shareId = $_GET['shareId'];
        $share = new Share(Share::getShareByID($shareId));
        $article = new Article(Article::getArticleByID($share->article));
        if($share->delete() && $article->delete()){
            echo '<script>alert("Post successfully deleted.");
            window.location = "'.SITEURL.'php/home.php";</script>';
        }else{
            echo '<script>alert("Unable to delete article. Please try again soon.");
            window.location = "'.SITEURL.'php/home.php";</script>';
        }
            
    }
}

function likeArticle(){
    $articleId = $_GET['articleId'];
    $article = new Article(Article::getArticleById($articleId));
    if(isset($_SESSION['id'])){
        $user = $_SESSION['id'];
        if(!$article->getLikeById($user)){
            $article->insertLike($user);
            echo '<i class="fas fa-thumbs-up"></i> Liked';
        }else if($article->getLikeById($user)){
            $article->deleteLike($user);
            echo '<i class="fas fa-thumbs-up"></i> Like';
        }

    }else{
        echo 'No';
    }
}

function updateLikes(){
    $articleId = $_GET['articleId'];
    $article = new Article(Article::getArticleById($articleId));
    echo $article->getTotalLikes(). ' Likes';
}

function commentArticle(){
    $articleId = $_POST['commentArticleId'];
    $comment = $_POST['comment_article_input'];
    $userId = $_SESSION['id'];
    $newComment = new Comment();
    $newComment->article = $articleId;
    $newComment->user = $userId;
    $newComment->comment = $comment;
    if($newComment->insert()){
        echo '<script>alert("Article commented.");
        window.location = "'.SITEURL.'php/home.php";
        </script>';
    }else{
        echo '<script>alert("Error when commenting article, please try again later.");
        window.location = "'.SITEURL.'php/home.php";
        </script>';
    }
}

function shareArticle(){
    $articleId = $_POST['shareArticleId'];
    $shareReply = $_POST['share_article_input'];
    $userID = $_SESSION['id'];
    
    $newShare = new Share();
    $newShare->user = $userID;
    $newShare->article = $articleId;
    $newShare->reply = $shareReply;
    $newShare->date_shared = date('Y-m-d H:i:s');
    if ($newShare->insert()){
        echo '<script>alert("Article Shared");
        window.location = "'.SITEURL.'php/home.php";
        </script>';
    }else{
        echo '<script>alert("Error when sharing article, please try again later.");
        window.location = "'.SITEURL.'php/home.php";
        </script>';
    }
}

function createEvent(){
    $newEvent = new Event();
    $newEvent->title = $_POST['event_name_input'];
    $newEvent->content = $_POST['event_desc_input'];
    $newEvent->start_date = $_POST['event_start_date'];
    if(isset($_POST['event_end_date'])){
        $newEvent->end_date = $_POST['event_end_date'];
    }
    if(isset($_POST['event_start_time'])){
        $newEvent->start_time = date("H:i", strtotime($_POST['event_start_time']));
    }
    if(isset($_POST['event_end_time'])){
        $newEvent->end_time = date("H:i", strtotime($_POST['event_end_time']));
    }
    $newEvent->date_posted = date('Y-m-d H:i:s');
    $newEvent->post_by = $_SESSION['id'];
    $newEvent->status = 0;
    
    if($newEvent->insert()){
        if(file_exists($_FILES['event_picture_input']['tmp_name'])) {
            $valid = uploadEventPic($newEvent->id);
        }
        echo "<script>alert('Event added successfully. Please wait for administrator approval.');
        window.location.href = 'view_event_list.php';</script>";
    }else{
        echo "<script>alert('Error creating event. Please try again later.');
        window.location.href = 'create_event.php';</script>";
    }
}


function uploadEventPic($eventId){
    $target_dir = "../upload/event/";
    $error_msg = '';
    $uploadOk = 1;
    $filename = $_FILES['event_picture_input']['name'];
    $imageFileType = strtolower(pathinfo($filename,PATHINFO_EXTENSION));

    // Check if image file is a actual image or fake image
    if(isset($_POST["submit"])) {
        $check = getimagesize($_FILES['event_picture_input']['tmp_name']);
        if($check !== false) {
            $uploadOk = true;
        } else {
            $error_msg = 'File is not an image.;';
            $uploadOk = false;
        }
    }
    // Allow certain file formats
    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
        $error_msg = 'Sorry, only JPG, JPEG, PNG  files are allowed.;';
        $uploadOk = false;
    }
    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == false) {
        echo "<script>alert('$error_msg')";
        return false;
        
    // if everything is ok, try to upload file
    } else {
        $target_folder = $target_dir . $eventId . '.jpeg';
        if (imagepng(imagecreatefromstring(file_get_contents($_FILES["event_picture_input"]["tmp_name"])), $target_folder)) {
            $compressLocation = "../upload/event_compress/" . $eventId . ".jpeg";
            compressImage($_FILES['event_picture_input']['tmp_name'],$compressLocation,50);
            return true;
        } else {
           echo "<script>alert('Sorry, there was an error uploading your file.');</script>";
           return false;
        }
    }
}

function compressImage($source, $destination, $quality) {
  $info = getimagesize($source);
  if ($info['mime'] == 'image/jpeg') 
    $image = imagecreatefromjpeg($source);

  elseif ($info['mime'] == 'image/gif') 
    $image = imagecreatefromgif($source);

  elseif ($info['mime'] == 'image/png') 
    $image = imagecreatefrompng($source);

  imagejpeg($image, $destination, $quality);
}

function closeEvent(){
    $eventId = $_GET['eventId'];
    $eventPart = new Event_Participant();
    $eventPart->event = $eventId;
    
    $event = new Event();
    $event->id =  $eventId;
    if($eventPart->deleteAll() && $event->delete()){
         echo '<script>alert("Event closed successfully.");
         window.location = "'.SITEURL.'php/view_event_list.php";</script>';
    }else{
         echo '<script>alert("Error deleting event. Please try again later.");
         window.location = "'.SITEURL.'php/view_event.php?eid='.$eventId.'" ;</script>';
    }
}

function joinEvent(){
    $participant = new Event_Participant();
    $participant->user = $_GET['userId'];
    $participant->event = $_GET['eventId'];
    $participant->status = 1;
    if($participant->insert()){
        echo "ok";
    }else{
        echo "error";
    }
    
}

function leaveEvent(){
    $participant = new Event_Participant();
    $participant->user = $_GET['userId'];
    $participant->event = $_GET['eventId'];
    if($participant->delete()){
        echo "ok";
    }else{
        echo "error";
    }
}

function inviteToEvent() {
    $participant = new Event_Participant();
    $participant->emails = $_POST['invite_to_event_input'];
    $participant->msg = $_POST['invite_to_event_msg'];
    $participant->event = $_POST['inviteEventId'];
    
    $removedSpace = str_replace(' ', '', $participant->emails);
    $emails = explode(',', $removedSpace);
    
    
    foreach ($emails as $email) {
        $msg = "
        You have been invited to join this event with the following message:\n
        ".$participant->msg."\n
        event link: ".SITEURL."php/view_event.php?eid=".$participant->event."\n
        This is an automated message, please do not reply.";
        mail($email,"Software Engineering",$msg);
    }
    
    echo '<script>alert("Invitation sent.");window.location = "'.SITEURL.'php/view_event.php?eid='.$participant->event.'";</script>';
    
}

//Admin
function updateAnnouncement() {
    $ann = new Announcement();
    $ann->content = $_POST['annInput'];
    $ann->date_posted = date('Y-m-d H:i:s');
    
    $ann->insert();
    echo '<script>alert("Announcement updated.");window.location = "'.SITEURL.'php/admin.php";</script>';
}

function approveUser(){
    $userId = $_GET['userId'];
    $account = Account::getAccByID($userId);
    $account->type = 2;
    if($account->update()){
        echo 'ok';
    }else{
        echo 'error';
    }
}

function approveArticle(){
    $articleId = $_GET['articleId'];
    $article = new Article(Article::getArticleByID($articleId));
    $article->status = 1;
    //Add to share table
    $share = new Share();
    $share->user = $article->post_by;
    $share->article = $article->id;
    $share->date_shared = date('Y-m-d H:i:s');
    if($article->update() && $share->insert()){
        echo 'ok';
    }else{
        echo 'error';
    }
}

function approveEvent(){
    $eventId = $_GET['eventId'];
    $event = new Event(Event::getEventByID2($eventId));
    $event->status = 1;
    if($event->updateStatus()){
        echo 'ok';
    }else{
        echo 'error';
    }
}

function rejectUser(){
    $userId = $_GET['userId'];
    $account = Account::getAccByID($userId);
    $user = new User(User::getUserByID($userId));
    if($user->delete() && $account->delete()){
        echo 'ok';
    }else{
        echo 'error';
    }
}

function rejectArticle(){
    $articleId = $_GET['articleId'];
    $reason = $_GET['reason'];
    $article = new Article(Article::getArticleByID($articleId));
    $article->status = 2;
    if($article->update() && $article->rejectArticle($reason)){
        echo 'ok';
    }else{
        echo 'error';
    }
}

function rejectEvent(){
    $eventId = $_GET['eventId'];
    $event = new Event(Event::getEventByID2($eventId));
    //Add send email here
    if($event->delete()){
        echo 'ok';
    }else{
        echo 'error';
    }
}

function blockArticle(){
    $articleId = $_POST['blockArticleId'];
    $getPage = $_GET['page'];
    $reason = $_POST['block_article_input'];
    if($getPage == "home"){
        $page = "home.php";
    }else if($getPage == "view"){
        $page = "view_article.php?aid=" . $articleId;
    }
    $article = new Article(Article::getArticleByID($articleId));
    $article->status = 3;
    if($article->update() && $article->rejectArticle($reason) && Share::deleteShareByArticle($articleId)){
        echo '<script>alert("Article Blocked.");
        window.location = "home.php";</script>';
    }else{
        echo '<script>alert("Error blocking article. Please try again later.");
        window.location = "'. $page .'";</script>';
    }
}

function reportUser(){
    $id = $_POST['reportUserId'];
    $type = "User";
    $reason = $_POST['report_user_input'];
    if(reported($type, $id, $reason)){
        echo '<script>alert("Report submitted. Thank you for your feedback.");
        window.location = "view_edit_profile.php?memberid='.$id.'";</script>';
    }else{
        echo '<script>alert("Error reporting user. Please try again later.");
        window.location = "view_edit_profile.php?memberid='.$id.'" ;</script>';
    }
}

function reportArticle(){
    $id = $_POST['reportArticleId'];
    $type = "Article";
    $reason = $_POST['report_article_input'];
    if(reported($type, $id, $reason)){
        echo '<script>alert("Report submitted. Thank you for your feedback.");
        window.location = "view_article.php?aid= '. $id .'";</script>';
    }else{
        echo '<script>alert("Error reporting article. Please try again later.");
        window.location = "view_article.php?aid= '. $id .'" ;</script>';
    }
}

function reportEvent(){
    $id = $_POST['reportEventId'];
    $type = "Event";
    $reason = $_POST['report_event_input'];
    if(reported($type, $id, $reason)){
        echo '<script>alert("Report submitted. Thank you for your feedback.");
        window.location = "view_event.php?eid='. $id .'";</script>';
    }else{
        echo '<script>alert("Error reporting event. Please try again later.");
        window.location = "view_event.php?eid='. $id .'" ;</script>';
    }
}

function reported($type, $item_id, $reason){
    $con = new PDO(DBhost,DBuser,DBpass);
    $query = "INSERT INTO report(type, item_id, reason, date)
    VALUES(:type, :item_id, :reason, :date)";
    $st = $con->prepare($query);
    $st->bindValue(":type", $type, PDO::PARAM_STR);
    $st->bindValue(":item_id", $item_id, PDO::PARAM_STR);
    $st->bindValue(":reason", $reason, PDO::PARAM_STR);
    $st->bindValue(":date", date('Y-m-d H:i:s'), PDO::PARAM_STR);
    if($st->execute()){
        return true;
    }else{
        return false;
    }
}

function deleteReport(){
    $id = $_GET['rid'];
    $con = new PDO(DBhost,DBuser,DBpass);
    $query = "DELETE FROM report WHERE id=:id";
    $st = $con->prepare($query);
    $st->bindValue(":id", $id, PDO::PARAM_INT);
    if($st->execute()){
        echo 'ok';
    }else{
        echo 'error';
    }
}
?>