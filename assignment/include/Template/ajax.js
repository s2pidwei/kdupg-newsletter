function likeArticle(articleId){
    var xmlhttp;
     document.getElementById("likeBtn"+articleId).disabled = true;
    if (window.XMLHttpRequest) {
       // code for modern browsers
       xmlhttp = new XMLHttpRequest();
     } else {
       // code for old IE browsers
       xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var response = this.responseText;
            document.getElementById("likeBtn"+articleId).disabled = false;
            if(response == 'No'){
                alert("You need to sign in to like this article.");
            }else{
                document.getElementById("likebutton"+articleId).innerHTML = this.responseText;
            }
        }
    };
    xmlhttp.open("GET", "action.php?action=likeArticle&articleId=" + articleId, true);
    xmlhttp.send();
    reloadLikes(articleId);
}

function reloadLikes(articleId){
    var xmlhttp;
    if (window.XMLHttpRequest) {
       // code for modern browsers
       xmlhttp = new XMLHttpRequest();
     } else {
       // code for old IE browsers
       xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
        xmlhttp.onreadystatechange = function() {
      if(xmlhttp.readyState === 4) {
        if(xmlhttp.status === 200) {
              alert(xmlhttp.responseText);
        } else {
              alert('Error Code: ' +  xmlhttp.status);
              alert('Error Message: ' + xmlhttp.statusText);
        }
      }
    };
    xmlhttp.onreadystatechange = function() {
      if (this.readyState == 4 && this.status == 200) {
        document.getElementById("likesTotal"+articleId).innerHTML = this.responseText;
      }
    };
    xmlhttp.open("GET", "action.php?action=updateLikes&articleId=" + articleId, true);
    xmlhttp.send();
}

function writeComment(articleId){
    var xmlhttp;
    var comment = document.getElementById("write_comment_box").value;
    if (event.keyCode === 13 && comment !== "") {
        event.preventDefault();
        document.getElementById("write_comment_box").disabled = true;
        if (window.XMLHttpRequest) {
           // code for modern browsers
           xmlhttp = new XMLHttpRequest();
        } else {
           // code for old IE browsers
           xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function() {
            if (this.readyState == 4 && this.status == 200) {
                document.getElementById("write_comment_box").value = null;
                document.getElementById("all_comments").innerHTML = this.responseText;
                document.getElementById("write_comment_box").disabled = false;
            }
        };
        xmlhttp.open("POST", "ajaxAction.php?action=commentArticle&articleId=" + articleId, true);
        xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
        xmlhttp.send("comment=" + comment);
    }else if(event.keyCode === 13){
        event.preventDefault();
    }
    
}

function openEditModal(articleId){
    var xmlhttp;
    if (window.XMLHttpRequest) {
       // code for modern browsers
       xmlhttp = new XMLHttpRequest();
     } else {
       // code for old IE browsers
       xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("article_edit_contents2").innerHTML = this.responseText;
            document.getElementById("hiddenEditModalBtn").click();
        }
    };
    xmlhttp.open("GET", "action.php?action=openEditArticle&articleId=" + articleId, true);
    xmlhttp.send();
}

function joinEvent(userId,eventId){
    var xmlhttp;
    document.getElementById("joinEventBtn" + eventId).disabled = true;
    if (window.XMLHttpRequest) {
       // code for modern browsers
       xmlhttp = new XMLHttpRequest();
     } else {
       // code for old IE browsers
       xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var response = this.responseText;
            if(response == 'ok'){
                refreshParticipant(eventId);
                alert("Event Joined.");
                document.getElementById("joinEventBtn" + eventId).disabled = false;
                document.getElementById("joinEventBtn"+ eventId).style.display = "none";
                document.getElementById("leaveEventBtn"+ eventId).style.display = "block";
            }else if(response == "error"){
                alert("Error joining event. Please try again later.");
            }else{
                alert("ERROR");
            }
        }
    };
    xmlhttp.open("GET", "action.php?action=joinEvent&userId="+ userId + "&eventId=" + eventId, true);
    xmlhttp.send();
}

function leaveEvent(userId,eventId){
    var xmlhttp;
    document.getElementById("leaveEventBtn" + eventId).disabled = true;
    if (window.XMLHttpRequest) {
       // code for modern browsers
       xmlhttp = new XMLHttpRequest();
     } else {
       // code for old IE browsers
       xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var response = this.responseText;
            if(response == 'ok'){
                refreshParticipant(eventId);
                alert("Event Left.");
                document.getElementById("leaveEventBtn" + eventId).disabled = false;
                document.getElementById("joinEventBtn"+ eventId).style.display = "block";
                document.getElementById("leaveEventBtn"+ eventId).style.display = "none";
            }else if(response == "error"){
                alert("Error leaving event. Please try again later.");
            }else{
                alert("ERROR");
            }
        }
    };
    xmlhttp.open("GET", "action.php?action=leaveEvent&userId="+ userId + "&eventId=" + eventId, true);
    xmlhttp.send();
}

function refreshParticipant(eventid){
    var xmlhttp;
    if (window.XMLHttpRequest) {
       // code for modern browsers
       xmlhttp = new XMLHttpRequest();
     } else {
       // code for old IE browsers
       xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("event_side_box2").innerHTML = this.responseText;
        }
    };
    xmlhttp.open("GET", "ajaxAction.php?action=showParticipant&eventid=" + eventid , true);
    xmlhttp.send();
}

function pendingDropDown(type){
    document.getElementById("dropdownPending").innerText = type;
    if(type == "User"){
        document.getElementById("pendingArticleList").style.display = "none";
        document.getElementById("pendingEventList").style.display = "none";
    }else if(type == "Article"){
        document.getElementById("pendingUserList").style.display = "none";
        document.getElementById("pendingEventList").style.display = "none";
    }else if(type == "Event"){
        document.getElementById("pendingUserList").style.display = "none";
        document.getElementById("pendingArticleList").style.display = "none";
    }
    showPendingList(type);
}

function showPendingList(type){
    var xmlhttp;
    var url;
    if(type == "User"){
        url = "ajaxAction.php?action=pendingUser";
    }else if(type == "Article"){
        url = "ajaxAction.php?action=pendingArticle";
    }else if(type == "Event"){
        url = "ajaxAction.php?action=pendingEvent";
    }
    if (window.XMLHttpRequest) {
       // code for modern browsers
       xmlhttp = new XMLHttpRequest();
     } else {
       // code for old IE browsers
       xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            if(type == "User"){
                document.getElementById("pendingUserList").innerHTML = this.responseText;
                document.getElementById("pendingUserList").style.display = "block";
        
            }else if(type == "Article"){
                document.getElementById("pendingArticleList").innerHTML = this.responseText;
                document.getElementById("pendingArticleList").style.display = "block";
        
            }else if(type == "Event"){
                document.getElementById("pendingEventList").innerHTML = this.responseText;
                document.getElementById("pendingEventList").style.display = "block";
            }
        }
    };
    xmlhttp.open("GET", url, true);
    xmlhttp.send();
}

function approvePending(type,id){
    var xmlhttp;
    var url;
    if(type == "User"){
        url = "action.php?action=approveUser&userId=" + id;
    }else if(type == "Article"){
        url = "action.php?action=approveArticle&articleId=" + id;
    }else if(type == "Event"){
        url = "action.php?action=approveEvent&eventId=" + id;
    }
    if (window.XMLHttpRequest) {
       // code for modern browsers
       xmlhttp = new XMLHttpRequest();
     } else {
       // code for old IE browsers
       xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var response = this.responseText;
            if(response == 'ok'){
                alert(type + ' approved');
                showPendingList(type);
            }else{
                alert("An error has occured. Please try again later.");
            }
        }
    };
    xmlhttp.open("GET", url, true);
    xmlhttp.send();
}

function rejectPending(type,id){
    var xmlhttp;
    var url;
    if(type == "User"){
        url = "action.php?action=rejectUser&userId=" + id;
    }else if(type == "Article"){
        var reason = prompt("Please write the reason of rejecting this article.");
        if(reason != null){
            url = "action.php?action=rejectArticle&articleId=" + id + "&reason=" + reason;
        }
    }else if(type == "Event"){
        var reason = prompt("Please write the reason of rejecting this event.");
        if(reason != null){
            url = "action.php?action=rejectEvent&eventId=" + id;
        }
    }
    if (window.XMLHttpRequest) {
       // code for modern browsers
       xmlhttp = new XMLHttpRequest();
     } else {
       // code for old IE browsers
       xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var response = this.responseText;
            if(response == 'ok'){
                alert( type + ' rejected');
                showPendingList(type);
            }
            else{
                alert("An error has occured. Please try again later.");
            }
        }
    };
    xmlhttp.open("GET", url, true);
    xmlhttp.send();
}

function reportDropDown(type){
    document.getElementById("dropdownReported").innerText = type;
    if(type == "User"){
        document.getElementById("reportedArticleList").style.display = "none";
        document.getElementById("reportedEventList").style.display = "none";
    }else if(type == "Article"){
        document.getElementById("reportedUserList").style.display = "none";
        document.getElementById("reportedEventList").style.display = "none";
    }else if(type == "Event"){
        document.getElementById("reportedUserList").style.display = "none";
        document.getElementById("reportedArticleList").style.display = "none";
    }
    showReportList(type);
}

function showReportList(type){
    var xmlhttp;
    var url;
    if(type == "User"){
        url = "ajaxAction.php?action=reportedUser";
    }else if(type == "Article"){
        url = "ajaxAction.php?action=reportedArticle";
    }else if(type == "Event"){
        url = "ajaxAction.php?action=reportedEvent";
    }
    if (window.XMLHttpRequest) {
       // code for modern browsers
       xmlhttp = new XMLHttpRequest();
     } else {
       // code for old IE browsers
       xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            if(type == "User"){
                document.getElementById("reportedUserList").innerHTML = this.responseText;
                document.getElementById("reportedUserList").style.display = "block";
        
            }else if(type == "Article"){
                document.getElementById("reportedEventList").innerHTML = this.responseText;
                document.getElementById("reportedEventList").style.display = "block";
        
            }else if(type == "Event"){
                document.getElementById("reportedEventList").innerHTML = this.responseText;
                document.getElementById("reportedEventList").style.display = "block";
            }
        }
    };
    xmlhttp.open("GET", url, true);
    xmlhttp.send();
}

function deleteReport(id,type){
    var xmlhttp;
    if (window.XMLHttpRequest) {
       // code for modern browsers
       xmlhttp = new XMLHttpRequest();
     } else {
       // code for old IE browsers
       xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
    }
    xmlhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
            var response = this.responseText;
            if(response == 'ok'){
                alert('Report deleted');
                showReportList(type);
            }
            else{
                alert("An error has occured. Please try again later.");
            }
        }
    };
    xmlhttp.open("GET", "action.php?action=deleteReport&rid=" + id, true);
    xmlhttp.send();
}
