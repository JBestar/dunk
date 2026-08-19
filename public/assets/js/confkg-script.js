var mGameRanges = null;
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

function showGame(list, gameId) {
    var html = "";

    if (list) {
        var curPage = getActivePage();
        var firstIdx = (curPage - 1) * CountPerPage;
        var nRow = 0;
        for(let game of list){
            html += "<tr><td>";
            html += (parseInt(nRow) + firstIdx + 1);
            html += "</td><td>";
            html += game.name;
            html += "</td><td>";
            if(mGameRanges !== undefined ){
                html += getRangeHtml(game.fid, game.key, game.range);
                html += "</td><td>";
            }
            html += "<select class='' onchange='onChangeHidden(this)' data-fid='" + game.fid + "' ";
            if (game.hidden == 0) {
                html += " style=''> <option selected='' value='0'>보이기</option><option value='1'>감추기</option></select>";
            } else {
                html += " style='color:red'> <option value='0'>보이기</option><option selected='' value='1'>감추기</option></select>";
            }
            html += "</td><td>";
            html += "<select class='' onchange='onChangeMaintain(this)' data-fid='" + game.fid + "' ";
            if (game.maintain == 0) {
                html += " style=''> <option selected='' value='0'>운영</option><option value='1'>점검</option></select>";
            } else {
                html += " style='color:red'> <option value='0'>운영</option><option selected='' value='1'>점검</option></select>";
            }
            html += "</td><tr>";
            nRow++;
        }
        if (html.length < 1) {
            html = "<tr><td colspan='5'>자료가 없습니다.</td></tr>";
        }
    }
    $("#confsite-table-data").html(html);

}

function getRangeHtml(fid, prdKey, gameKey){
    if(prdKey.indexOf("bota_casino") >= 0){
        gameKey = prdKey;
        prdKey = "bota_casino";
    }

     html = "";
    if(prdKey in mGameRanges){
        html = "<select style='width:150px;' onchange='onChangeRange(this)' data-fid='" + fid + "' >";
        for(let range of mGameRanges[prdKey]){
            html+=`<option value='${range.key}' ${range.key==gameKey?'selected':''}>${range.value}</option>`;
        }
        html += "</select>";
    }
    return html;

}

function onChangeRange(objSelect) {

    let range = $(objSelect).val();
    
    let jsonData = { "range": range,
        "fid": $(objSelect).data("fid"),
    }

    requestGameSet(jsonData);
}

function onChangeHidden(objSelect) {

    let iType = $(objSelect).val();
    if (iType == 1)
        $(objSelect).css("color", "red");
    else
        $(objSelect).css("color", "black");

    var jsonData = {
        "hidden": iType == 1 ? 1 : 0,
        "fid": $(objSelect).data("fid"),
    };

    requestGameSet(jsonData);

}


function onChangeMaintain(objSelect) {

    let iType = $(objSelect).val();
    if (iType == 1)
        $(objSelect).css("color", "red");
    else
        $(objSelect).css("color", "black");

    var jsonData = {
        "maintain": iType == 1 ? 1 : 0,
        "fid": $(objSelect).data("fid"),
    };
    requestGameSet(jsonData);
}

function requestGame() {

    var nPage = getActivePage();
    var sGame = $("#confsite-game-input-id").val();
    let gameId = $(".confsite-game-panel").attr('id');

    var jsonData = {
        "count": CountPerPage,
        "page": nPage,
        "name": sGame,
        "cat":gameId
    };

    jsonData = JSON.stringify(jsonData);
    $(".loading").show();
    $.ajax({
        type: "POST",
        dataType: "json",
        url: FURL + "/api/kgonlist",
        data: { json_: jsonData },
        success: function(jResult) {
            // console.log(jResult);
            $(".loading").hide();
            if (jResult.status == "success") {
                showGame(jResult.data, gameId);
            } else if (jResult.status == "fail") {

            }
        },
        error: function(request, status, error) {
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
            $(".loading").hide();
        }

    });

}



//Function to Request Game Result History to WebServer
function requestTotalPage() {
    CountPerPage = $("#confsite-number-select-id").val();
    var sGame = $("#confsite-game-input-id").val();
    let gameId = $(".confsite-game-panel").attr('id');

    var jsonData = {
        "count": CountPerPage,
        "name": sGame,
        "cat":gameId
    };

    jsonData = JSON.stringify(jsonData);

    $.ajax({
        url: FURL + '/api/kgoncnt',
        data: { json_: jsonData },
        dataType: 'json',
        type: 'post',
        success: function(jResult) {
            // console.log(jResult);
            if (jResult.status == "success") {
                TotalCount = jResult.data.count;
                mGameRanges = jResult.ranges;
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
        url: FURL + "/api/kgonset",
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