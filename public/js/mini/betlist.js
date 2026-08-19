$(document).ready(function() {
    requestTotalPage();
});

$(function() {

    $(".search_tab a").click(function(e) {
        e.preventDefault();

        $(".search_tab a").removeClass("on");
        $(this).addClass("on");
        requestTotalPage();
    });
});

function getDate() {
    if ($(".search_tab .on").length > 0) {
        $date = $(".search_tab .on").attr("id");
        return $date.replace(/[/]/g, "-");
    }
    return "";
}

function getGameId() {
    return $(".result_list").attr("id");
}

function requestPageInfo() {
    var nPage = getActivePage();
    var jsonData = { "game": getGameId(), "date": getDate(), "count": CountPerPage, "page": nPage };

    jsonData = JSON.stringify(jsonData);

    $.ajax({
        type: "POST",
        dataType: "json",
        url: FURL + "/api/page_bet",
        data: { json_: jsonData },
        success: function(jResult) {
            // console.log(jResult);
            if (jResult.status == "success") {
                showPage(jResult.game, jResult.data);
            } else if (jResult.status == "fail") {

            }
        },
        error: function(request, status, error) {
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        }

    });
}

function requestTotalPage() {
    var jsonData = { "count": CountPerPage, "date": getDate(), "game": getGameId() };
    jsonData = JSON.stringify(jsonData);

    $.ajax({
        url: FURL + '/api/count_bet',
        data: { json_: jsonData },
        dataType: 'json',
        type: 'post',
        success: function(jResult) {
            // console.log(jResult);
            if (jResult.status == "success") {
                TotalCount = jResult.data;
                setFirstPage();
                requestPageInfo();
            }
        },
        error: function(request, status, error) {
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        }

    });
}

function showPage(game, data) {

    if (game == "undefined" || game == null)
        return;

    var html = getBetResultHtml(game, data);
    $(".result_list").html(html);

}