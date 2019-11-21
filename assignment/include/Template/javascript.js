function show_edit_profile(){
    document.getElementById("edit_article_div").style.display = "block";
    document.getElementById("view_article_div2").style.display = "none";
    document.getElementById("edit_profile_btn").style.display = "none";
}

function hide_edit_profile(){
    document.getElementById("edit_article_div").style.display = "none";
    document.getElementById("view_article_div2").style.display = "block";
    document.getElementById("edit_profile_btn").style.display = "block";
}

function show_update_pw2() {
    document.getElementById("update_pw_div").style.display = "block";
    document.getElementById("update_pw_btn").style.display = "none";
    document.getElementById("view_article_div2").style.display = "none";
    document.getElementById("edit_profile_btn").style.display = "none";
}

function hide_update_pw() {
    document.getElementById("update_pw_div").style.display = "none";
    document.getElementById("update_pw_btn").style.display = "block";
    document.getElementById("view_article_div2").style.display = "block";
    document.getElementById("edit_profile_btn").style.display = "block";
}

function shareAlert(){
    alert('You need to sign in to share this article.');
}

var _validFileExtensions = [".jpg", ".jpeg", ".png"];    
function ValidateSingleInput(oInput) {
    if (oInput.type == "file") {
        var sFileName = oInput.value;
         if (sFileName.length > 0) {
            var blnValid = false;
            for (var j = 0; j < _validFileExtensions.length; j++) {
                var sCurExtension = _validFileExtensions[j];
                if (sFileName.substr(sFileName.length - sCurExtension.length, sCurExtension.length).toLowerCase() == sCurExtension.toLowerCase()) {
                    blnValid = true;
                    break;
                }
            }
             
            if (!blnValid) {
                alert("Sorry, this file is invalid, allowed extensions are: " + _validFileExtensions.join(", "));
                oInput.value = "";
                return false;
            }
        }
    }
    var reader = new FileReader();
    reader.onload = function(){
      var output = document.getElementById('editProfilePic');
      output.src = reader.result;
    };
    reader.readAsDataURL(event.target.files[0]);
    return true;
}

function setFocus() {
    document.getElementById("write_comment_box").focus();
}

function auto_grow(element) {
    element.style.height = "5px";
    element.style.height = (element.scrollHeight)+"px";
}

function deleteArticle(articleId){
     if (confirm("Are you sure you want to delete this post and article?") === true) {
         window.location.href = "action.php?action=deleteArticle&shareId=" + articleId;
     }
}

function commentArticle(articleId){
    document.getElementById("commentArticleId").value = articleId;
}

function shareArticle(articleId){
    document.getElementById("shareArticleId").value = articleId;
}

function reportUser(userId){
    document.getElementById("reportUserId").value = userId;
}

function reportArticle(articleId){
    document.getElementById("reportArticleId").value = articleId;
}

function reportEvent(eventId){
    document.getElementById("reportEventId").value = eventId;
}

function openBlockModal(articleId,shareId){
    document.getElementById("blockArticleId").value = articleId;
    document.getElementById("blockShareId").value = shareId;
} 

function deleteEvent(eventId){
    if(confirm('Are you sure you want to close this event?')){
        window.location.href = "action.php?action=closeEvent&eventId=" + eventId;
    }
}

function getRejectReason(reason){
    document.getElementById("rejectRaeason").innerHTML = reason;
    document.getElementById("showReasonModal").click();
}