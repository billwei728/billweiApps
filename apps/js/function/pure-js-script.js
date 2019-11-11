
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
    
    $("a.list-link").on("click", function(event) {
        var webId = $(this).attr("id");
        var webHref = $(this).attr("href");
        var urlLength = webHref.length;
        $("#webContent").empty();
        if (1 < urlLength) {
            $("#webContent").addClass("d-none");
            $("#iframeContent").removeClass("d-none");
        } else {
            $("#webContent").removeClass("d-none");
            $("#iframeContent").addClass("d-none");

            var link_arrow = $(this).hasClass("link-arrow");
            if (! link_arrow) {
                if ("#" == webHref || "" == webHref) {
                    redirection("#"+webId, pathURL, sessionURL, webId);
                }
            }
        }
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
        resMsg = '<i class="fas fa-info-circle fa-lg mr-2"></i>' + total + " record(s) has been " + action + " successfully";
    } else if ("error" == result) {
        resMsg = '<i class="fas fa-info-circle fa-lg mr-2"></i>' + " Failed to " + action + " " + total + " record(s)";
    }
    if (result) {
        $.miniNoty(resMsg, result);
        return false;
    }
});

function redirection(ids, url, sessurl, page)
{
    if ("home" == ids || "home404" == ids) {
        $(location).attr("href", url);
    } else {
        var redirectURL = url + sessurl + "?url=" + url + "&page=" + page;
        $(location).attr("href", redirectURL);
    }
}

function getCookieValue(cname) 
{
    var value = document.cookie.match('(^|[^;]+)\\s*' + cname + '\\s*=\\s*([^;]+)');
    return value ? value.pop() : '';
}
