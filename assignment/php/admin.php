<?php
    include("../config.php");
    session_start();
    $id = null;
    if(isset($_SESSION['id'])){
        $id = $_SESSION['id'];
        $member = new User(User::getUserByID($id));
        $account= Account::getAccByID($_SESSION['id']);
        
        if($account->type != 0) {
            header('Location: '. SITEURL .'php/home.php');
            exit();
        }
    } else {
        header('Location: '. SITEURL .'php/home.php');
        exit();
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
    <script src="https://kit.fontawesome.com/c1620fa448.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="../css/theme1.css">
    <link rel="stylesheet" href="../css/admin_theme.css">
    
    
    <script type="text/javascript" src="../include/Template/ajax.js"></script>
    
	<title>Software Engineering</title>
</head>
<body>
    
    <?php
    include(TEMPLATE_PATH."header.php");
    ?>
    
	
    <div class="mt-5 mt-5"></div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-2">
                
            </div>
            <div class="col-sm-8">
                <h1>Admin page</h1>
                <div id="main_container">
                    <h3>Announcement</h3>
                    <form action="action.php?action=updateAnnouncement" class="form" method="POST">
                        <table id="annTable">
                            <tr>
                                <td>
                                    <input type="text" class="form-control" id="annInput" name="annInput">
                                    <small id="textUnder" class="form-text text-muted"><i class="fas fa-exclamation-triangle"></i> Everyone can see this.</small>
                                </td>
                                <td id="annAlign">
                                    <button type="submit" class="btn btn-success" id="annBtn"><i class="fas fa-check"></i></button>
                                </td>
                            </tr>
                            <tr>
                                <!-- probably a button for history -->
                            </tr>
                        </table>
                    </form>
                    <hr>
                    <h3>View pending</h3>
                    <div class="dropdown" id="dropdownPendingContainer">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownPending" data-toggle="dropdown">
                            Please select
                        </button>
                        <div class="dropdown-menu">
                            <button class="dropdown-item" type="button" id="pendingUser" 
                            onclick="pendingDropDown('User')">User</button>
                            <button class="dropdown-item" type="button" id="pendingArticle" 
                            onclick="pendingDropDown('Article')">Article</button>
                            <button class="dropdown-item" type="button" id="pendingEvent" 
                            onclick="pendingDropDown('Event')">Event</button>
                        </div>
                    </div>
                    <div class="mt-3">
                    <div id="pendingUserList" style="display:none">
                        <table class="table">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Contact</th>
                                    <th id="actCol">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th>#</th>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Contact</th>
                                    <th>
                                        <button class="btn btn-success" id="actionBtn"><i class="fas fa-check"></i></button>
                                        <button class="btn btn-danger" id="actionBtn"><i class="fas fa-times"></i></button>
                                    </th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div id="pendingArticleList" style="display:none">
                        <table class="table">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th>Content</th>
                                    <th>By</th>
                                    <th id="actCol">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th>#</th>
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th>Content</th>
                                    <th>By</th>
                                    <th>
                                        <button class="btn btn-success" id="actionBtn"><i class="fas fa-check"></i></button>
                                        <button class="btn btn-danger" id="actionBtn"><i class="fas fa-times"></i></button>
                                    </th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div id="pendingEventList" style="display:none">
                        <table class="table">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th>Content</th>
                                    <th>By</th>
                                    <th id="actCol">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th>#</th>
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th>Content</th>
                                    <th>By</th>
                                    <th>
                                        <button class="btn btn-success" id="actionBtn"><i class="fas fa-check"></i></button>
                                        <button class="btn btn-danger" id="actionBtn"><i class="fas fa-times"></i></button>
                                    </th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <hr>
                    <h3>View reported</h3>
                    <div class="dropdown" id="dropdownReportedContainer">
                        <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownReported" data-toggle="dropdown">
                            Please select
                        </button>
                        <div class="dropdown-menu">
                            <button class="dropdown-item" type="button" id="reportedUser" 
                            onclick="reportDropDown('User')">User</button>
                            <button class="dropdown-item" type="button" id="reportedArticle" 
                            onclick="reportDropDown('Article')">Article</button>
                            <button class="dropdown-item" type="button" id="reportedEvent" 
                            onclick="reportDropDown('Event')">Event</button>
                        </div>
                    </div>
                    <div class="mt-3">
                    <div id="reportedUserList" style="display:none">
                        <table class="table">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Contact</th>
                                    <th>Reason</th>
                                    <th id="actCol">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th>#</th>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Contact</th>
                                    <th>Reason</th>
                                    <th>
                                        <button class="btn btn-success" id="actionBtn"><i class="fas fa-check"></i></button>
                                        <button class="btn btn-danger" id="actionBtn"><i class="fas fa-times"></i></button>
                                    </th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div id="reportedArticleList" style="display:none">
                        <table class="table">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th>Content</th>
                                    <th>By</th>
                                    <th>Reason</th>
                                    <th id="actCol">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th>#</th>
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th>Content</th>
                                    <th>By</th>
                                    <th>Reason</th>
                                    <th>
                                        <button class="btn btn-success" id="actionBtn"><i class="fas fa-check"></i></button>
                                        <button class="btn btn-danger" id="actionBtn"><i class="fas fa-times"></i></button>
                                    </th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    <div id="reportedEventList" style="display:none">
                        <table class="table">
                            <thead class="thead-light">
                                <tr>
                                    <th>#</th>
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th>Content</th>
                                    <th>By</th>
                                    <th>Reason</th>
                                    <th id="actCol">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <th>#</th>
                                    <th>ID</th>
                                    <th>Title</th>
                                    <th>Content</th>
                                    <th>By</th>
                                    <th>Reason</th>
                                    <th>
                                        <button class="btn btn-success" id="actionBtn"><i class="fas fa-check"></i></button>
                                        <button class="btn btn-danger" id="actionBtn"><i class="fas fa-times"></i></button>
                                    </th>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="col-sm-2">
                    
                </div>
            </div>
        </div>
    </div>
</body>
</html>