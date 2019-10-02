
// <!-- Pure Js Script -->
$(function() { 
    // Sidebar Listing Scroll Bar
    new PerfectScrollbar(".list-scrollbar");
    var nanobar = new Nanobar();
    nanobar.go(100);

    // Hyperlink Actions
    var user = getCookieValue("user"),
        url = window.location.pathname,
        filename = window.location.pathname.substring(window.location.pathname.lastIndexOf('/') + 1);
    var pathURL = window.location.href.split('?')[0].replace(filename, '').replace("#", '');
    var homeURL;
    var sessionURL = "pages/menu_session.php";

    if ("admin" == user) {
        homeURL = pathURL;
    } else {
        homeURL = pathURL + user + ".php";
    }
    
	redirection("#home", homeURL, null, null);
    redirection("#home404", homeURL, null, null);
    redirection("#module_list", pathURL, sessionURL, "module_list");
	redirection("#menu_list", pathURL, sessionURL, "menu_list");
    redirection("#clearlog", pathURL, sessionURL, "clearlog");
    redirection("#account_list", pathURL, sessionURL, "account_list");

    $("#system_doctor_st, #system_doctor_pr").on("click", function(event) {
        $("#webContent").addClass("d-none");
        $("#iframeContent").removeClass("d-none");
    });

    // Get current page and set current in nav
    var menuClick = getCookieValue("page"),
        parentDivId;

    if (menuClick) {  
        $("#" + menuClick).addClass("point").append('<i class="fa fa-trash-o" aria-hidden="true"></i>').css('background-color', "#f4f4f4");
        $("#" + menuClick).parent().parent().css('display', "block");
        // $("#" + menuClick).parent().parent().parent().find("a").addClass("transition rotate");
    }

    $("a").click(function() {
        menuClick = $(this).attr("id");
        menuParentClick = $(this).closest('ul').parent().find('a').attr("id");
        listId = $(this).closest('ul').attr("id");
        parentId = $(this).closest('ul').attr("id");
        grandParentId = $(this).closest('ul').parent().closest('ul').attr("id");
        // setCookieValue("page", menuClick, 60);
        // alert(listId);
        // alert(parentId);
        // alert(menuParentClick);
        
        // $("ul.list-hidden:not(#" + listId + ", #populateMenu, #" + parentId + ", #" + grandParentId + ")").css("display", "none");
        // $("a:not(#" + menuClick + ", #" + menuParentClick + ")").removeClass("active, rotate");

        $("#" + menuClick).addClass("point").append('<i class="fa fa-trash-o" aria-hidden="true"></i>').css('background-color', "#f4f4f4");
        $("#" + menuClick).parent().parent().css('display', "block");

        $("a:not(#" + menuClick + ")").removeClass("point");
        $("a.link-arrow").removeClass("point");
    });

    // Notification
    var total = getCookieValue("total"),
        result = getCookieValue("result"),
        action = getCookieValue("action").replace("_", " "),
        resMsg;
    if ("success" == result) {
        resMsg = total + " record(s) has been " + action + " successfully";
    } else if ("error" == result) {
        resMsg = "Failed to " + action + " " + total + " record(s)";
    }
    if (result) {
        $.miniNoty(resMsg, result);
        return false;
    }
});

function redirection(ids, url, sessurl, page)
{
	$(ids).click(function() {
		var onClickId = $(this).attr("id");
	  	event.preventDefault();
	  	if (page) {
	  		var redirectURL = url + sessurl + "?url=" + url + "&page=" + page;
        	$(location).attr("href", redirectURL);
	  	} else {
	  		if ("home" == onClickId) $(location).attr("href", url);
        }
	});
}

function getCookieValue(cname) 
{
    var value = document.cookie.match('(^|[^;]+)\\s*' + cname + '\\s*=\\s*([^;]+)');
    return value ? value.pop() : '';
}

// function setCookieValue(cname, cvalue, exseconds) 
// {
//     var expires = "";
//     if (exseconds) {
//         var date = new Date();
//         date.setTime(date.getTime() + (exseconds));
//         expires = "; expires=" + date;
//     }
//     document.cookie = cname + "=" + (cvalue || "")  + expires + "; path=/";
// }
	