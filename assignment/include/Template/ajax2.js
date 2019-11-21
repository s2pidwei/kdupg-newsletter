function articleCategory(category){
    var showCategory = category;
    if(category === null){
        showCategory = "All Category"; 
    }
    document.getElementById("categoryDropdown").innerHTML = showCategory;
    searchArticles();
}

function writeArticleCategory(category){
    var showCategory = category;
    document.getElementById("newArticleCategory").value = showCategory;
    document.getElementById("writesCategoryDropdown").innerHTML = showCategory;
}

function editArticleCategory(category){
    var showCategory = category;
    document.getElementById("changeArticleCategory").value = showCategory;
    document.getElementById("editCategoryDropdown").innerHTML = showCategory;
}

function enterToSearch(){
    if (event.keyCode === 13) {
        event.preventDefault();
        searchArticles();
    }
}

function searchArticles(){
    var xmlhttp;
    var content = document.getElementById("searchArticleBox").value;
    var category = document.getElementById("categoryDropdown").innerHTML;
    if (category == "All Category" || category == "Select Category"){
        category = '';
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
            document.getElementById("article_section").innerHTML = this.responseText;
        }
    };
    xmlhttp.open("GET", "ajaxAction.php?action=searchArticle&type=" + category + "&content=" + content, true);
    xmlhttp.send();
}

function followMember(followerId){
    var xmlhttp;
    var follow;
    var followText = document.getElementById("follow_button").innerHTML;
    if(followText == "Follow"){
        follow = "false";
    }else if(followText == "Followed"){
        follow = "true";
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
            if(response == "Follow" || response == "Followed"){
                document.getElementById("follow_button").innerHTML = this.responseText;
            }else{
                alert("Error following this member. Please try again later." + response);
            }
        }
    };
    xmlhttp.open("GET", "ajaxAction.php?action=followMember&follow=" + follow + "&follower=" + followerId, true);
    xmlhttp.send();
}
