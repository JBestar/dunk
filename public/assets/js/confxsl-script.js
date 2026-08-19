$(document).ready(function() {

    addEventListner();
    requestTotalPage();
});


function requestPageInfo() {
    requestGame();
}

function addEventListner() {
    $("#confsite-list-view-but-id").click(function() {
        requestTotalPage();
    });

    $("#confsite-number-select-id").change(function() {
        requestTotalPage();
    });
}

function showGame(list) {
    var html = "";

    if (list) {
        var curPage = getActivePage();
        var firstIdx = (curPage - 1) * CountPerPage;
        var nRow = 0;
        list.forEach((game) => {
            html += "<tr><td>";
            html += (parseInt(nRow) + firstIdx + 1);
            html += "</td><td>";
            html += game.name_kr;
            html += "</td><td>";
            html += game.name;
            html += "</td><td>";
            html += "<select class='' onchange='onChangeHidden(this)' data-name='" + game.name + "' ";
            if (game.hidden == 0) {
                html += " style='color:black'> <option selected='' value='0'>보이기</option><option value='1'>감추기</option></select>";
            } else {
                html += " style='color:red'> <option value='0'>보이기</option><option selected='' value='1'>감추기</option></select>";
            }
            html += "</td><tr>";
            nRow++;
        });
        if (html.length < 1) {
            html = "<tr><td colspan='5'>자료가 없습니다.</td></tr>";
        }
    }
    $("#confsite-table-data").html(html);

}

function onChangeHidden(objSelect) {

    let iType = $(objSelect).val();
    if (iType == 1)
        $(objSelect).css("color", "red");
    else
        $(objSelect).css("color", "black");

    var jsonData = {
        "hidden": iType == 1 ? 1 : 0,
        "name": $(objSelect).data("name"),
    };

    requestGameSet(jsonData);
}

function requestGame() {

    var nPage = getActivePage();
    var sGame = $("#confsite-game-input-id").val();

    var jsonData = {
        "count": CountPerPage,
        "page": nPage,
        "name": sGame,
        "game": $(".confsite-game-panel").attr('id')
    };

    jsonData = JSON.stringify(jsonData);
    $(".loading").show();
    $.ajax({
        type: "POST",
        dataType: "json",
        url: FURL + "/api/xslotlist",
        data: { json_: jsonData },
        success: function(jResult) {
            // console.log(jResult);
            $(".loading").hide();
            if (jResult.status == "success") {
                showGame(jResult.data);
            } else if (jResult.status == "fail") {

            }
        },
        error: function(request, status, error) {
            console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
            $(".loading").hide();
        }

    });

}



//Function to Request Game Result History to WebServer
function requestTotalPage() {
    CountPerPage = $("#confsite-number-select-id").val();
    var sGame = $("#confsite-game-input-id").val();

    var jsonData = {
        "count": CountPerPage,
        "name": sGame,
        "game": $(".confsite-game-panel").attr('id')
    };

    jsonData = JSON.stringify(jsonData);

    $.ajax({
        url: FURL + '/api/xslotcnt',
        data: { json_: jsonData },
        dataType: 'json',
        type: 'post',
        success: function(jResult) {
            // console.log(jResult);
            if (jResult.status == "success") {
                TotalCount = jResult.data.count;
                setFirstPage();
                requestGame();
            }
        },
        error: function(request, status, error) {
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        }

    });
}


function requestGameSet(data){
    let jsonData = JSON.stringify(data);
    $.ajax({
        type: "POST",
        dataType: "json",
        url: FURL + "/api/xslotset",
        data: { json_: jsonData },
        success: function(jResult) {
            // console.log(jResult);
            if (jResult.status == "success") {}
        },
        error: function(request, status, error) {
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        }
    });
}

