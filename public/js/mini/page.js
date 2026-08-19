var TotalCount = 1;
var CountPerPage = 10;
var ViewPage = 10;

function setFirstPage() {

    if (TotalCount <= CountPerPage) {
        $(".pagenation").hide();
        $("#pagenation-num").html("");
        return;
    }

    var tHtml = "";
    var pageCnt = TotalCount % CountPerPage == 0 ? TotalCount / CountPerPage : TotalCount / CountPerPage + 1;

    $(".pagenation .prev").hide();

    if (pageCnt > ViewPage) {
        pageCnt = ViewPage;
    }

    if (TotalCount > CountPerPage * pageCnt)
        $(".pagenation .next").show();
    else $(".pagenation .next").hide();


    for (var page = 1; page <= pageCnt; page++) {
        if (page == 1)
            tHtml += "<a href='javascript:void(0);' class='on'>";
        else tHtml += "<a href='javascript:void(0);'>";

        tHtml += page.toString();
        tHtml += "</a>";

    }
    $("#pagenation-num").html(tHtml);
    $(".pagenation").show();
    addPageEventListner();


}

function getFirstPage() {
    var pageBtns = $("#pagenation-num").find("a");
    if (pageBtns == null)
        return -1;

    if (pageBtns.length < 1)
        return -1;

    if (pageBtns[0].innerHTML.length > 0)
        return parseInt(pageBtns[0].innerHTML);
    return -1;

}

function getActivePage() {
    var pageBtns = $("#pagenation-num").find(".on");
    if (pageBtns == null)
        return 1;

    if (pageBtns.length < 1)
        return 1;

    if (pageBtns[0].innerHTML.length > 0)
        return parseInt(pageBtns[0].innerHTML);
    return 1;

}

function prevPage() {

    if (TotalCount <= CountPerPage) {
        $(".pagenation").hide();
        $("#pagenation-num").html("");
        return;
    }

    var firstPage = getFirstPage();
    if (firstPage < 0)
        return;

    var layountCnt = parseInt(firstPage / ViewPage) * ViewPage * CountPerPage;

    if (layountCnt > TotalCount)
        return;

    var tHtml = "";
    var pageCnt = layountCnt % CountPerPage == 0 ? layountCnt / CountPerPage : layountCnt / CountPerPage + 1;

    if (layountCnt > ViewPage * CountPerPage)
        $(".pagenation .prev").show();
    else $(".pagenation .prev").hide();

    if (pageCnt > ViewPage) {
        pageCnt = ViewPage;
    }
    $(".pagenation .next").show();

    firstPage -= ViewPage;
    for (var page = 1; page <= pageCnt; page++) {
        if (page == 1)
            tHtml += "<a class='on' href='javascript:void(0);'>";
        else tHtml += "<a href='javascript:void(0);'>";

        tHtml += (firstPage + page - 1).toString();
        tHtml += "</a>";

    }
    $("#pagenation-num").html(tHtml);
    $(".pagenation").show();
    addPageEventListner();
    requestPageInfo();
}

function nextPage() {

    if (TotalCount <= CountPerPage) {
        $(".pagenation").hide();
        $("#pagenation-num").html("");
        return;
    }

    var pageBtns = $("#pagenation-num").find("a");
    if (pageBtns == null)
        return;

    if (pageBtns.length < ViewPage)
        return;

    var firstPage = parseInt(pageBtns[0].innerHTML);

    var layountCnt = TotalCount - (parseInt(firstPage / ViewPage) + 1) * ViewPage * CountPerPage;

    var tHtml = "";
    var pageCnt = layountCnt % CountPerPage == 0 ? layountCnt / CountPerPage : layountCnt / CountPerPage + 1;

    $(".pagenation .prev").show();
    if (pageCnt > ViewPage) {
        pageCnt = ViewPage;
    }

    if (layountCnt > CountPerPage * pageCnt)
        $(".pagenation .next").show();
    else $(".pagenation .next").hide();

    firstPage += ViewPage;
    for (var page = 1; page <= pageCnt; page++) {
        if (page == 1)
            tHtml += "<a class='on' href='javascript:void(0);'>";
        else tHtml += "<a  href='javascript:void(0);'>";

        tHtml += (firstPage + page - 1).toString();
        tHtml += "</a>";

    }
    $("#pagenation-num").html(tHtml);
    $(".pagenation").show();
    addPageEventListner();
    requestPageInfo();

}

function addPageEventListner() {
    var pageBtns = $("#pagenation-num").find("a");
    if (pageBtns == null)
        return;

    for (var idx = 0; idx < pageBtns.length; idx++) {

        pageBtns[idx].addEventListener("click", function() {

            if (this.className != "on") {
                $("#pagenation-num").find(".on").removeClass("on");
                this.className = "on";
                requestPageInfo();
            }

        });

    }
}