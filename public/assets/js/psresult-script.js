$(document).ready(function() {
    setNavBarElement();
    addEventListner();
    requestTotalPage();
    psresultLoop();
});

function requestPageInfo() {
    requestGameResult();
}

//Function to Show Betting History
function ShowGameResult(jsonRoundResults) {
    var elemResultTb = document.getElementById("pbresult-list-table-id");

    var strBuf = "";

    for (nRow in jsonRoundResults) {

        strBuf += "<tr><td>";
        strBuf += jsonRoundResults[nRow].round_date;
        strBuf += "</td><td>";
        strBuf += jsonRoundResults[nRow].round_num;
        strBuf += "</td><td>";
        if (jsonRoundResults[nRow].round_result_1 == 'P')
            strBuf += "<span class='pbresult-rule-odd-span'>좌</span>";
        else if (jsonRoundResults[nRow].round_result_1 == 'B')
            strBuf += "<span class='pbresult-rule-even-span'>우</span>";
        strBuf += "</td><td>";
        if (jsonRoundResults[nRow].round_result_2 == 'P')
            strBuf += "<span class='pbresult-rule-odd-span'> 3줄 </span>";
        else if (jsonRoundResults[nRow].round_result_2 == 'B')
            strBuf += "<span class='pbresult-rule-even-span'>4줄</span>";
        strBuf += "</td><td>";
        if (jsonRoundResults[nRow].round_result_3 == 'P')
            strBuf += "<span class='pbresult-rule-odd-span'>홀</span>";
        else if (jsonRoundResults[nRow].round_result_3 == 'B')
            strBuf += "<span class='pbresult-rule-even-span'>짝</span>";

        strBuf += "</td><td>";
        if(mGameId == 2){
            strBuf += "<a href='"+FURL+"/result/psresult_edit/" + jsonRoundResults[nRow].round_fid + "' >수정</a>";
            strBuf += "<a href='"+FURL+"/result/psbetchange/" + jsonRoundResults[nRow].round_date + "/" + jsonRoundResults[nRow].round_num + "' >적특</a>";
        } else if(mGameId == 6){
            strBuf += "<a href='"+FURL+"/result/bsresult_edit/" + jsonRoundResults[nRow].round_fid + "' >수정</a>";
            strBuf += "<a href='"+FURL+"/result/bsbetchange/" + jsonRoundResults[nRow].round_date + "/" + jsonRoundResults[nRow].round_num + "' >적특</a>";
        }
        strBuf += "</td></tr>";

    }

    if (strBuf.length < 1) {
        strBuf = "<tr><td colspan='6'>자료가 없습니다.</td></tr>";
    }

    elemResultTb.innerHTML = strBuf;
}

function addEventListner() {

    $("#pbresult-list-view-but-id").click(function() {
        requestTotalPage();
    });

    $("#pbresult-number-select-id").change(function() {
        requestTotalPage();
    });
}

//Function to Request Game Result History to WebServer
function requestGameResult() {

    var dtStart = $("#pbresult-datestart-input-id").val();
    var dtEnd = $("#pbresult-dateend-input-id").val();
    var nRound = $("#pbresult-round-input-id").val();
    var nPage = getActivePage();

    var jsonData = { "game":mGameId, "count": CountPerPage, "page": nPage, "start": dtStart, "end": dtEnd, "round": nRound };
    jsonData = JSON.stringify(jsonData);
    $(".loading").show();
    $.ajax({
        url: FURL + '/pbapi/result',
        data: { json_: jsonData },
        dataType: 'json',
        type: 'post',
        success: function(jResult) {
            $(".loading").hide();
            //console.log(jResult);
            if (jResult.status == "success") {
                ShowGameResult(jResult.data);
            }
        },
        error: function(request, status, error) {
            $(".loading").hide();
            //console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
        }

    });
}


//Function to Request Game Result History to WebServer
function requestTotalPage() {

    var dtStart = $("#pbresult-datestart-input-id").val();
    var dtEnd = $("#pbresult-dateend-input-id").val();
    CountPerPage = $("#pbresult-number-select-id").val();
    var nRound = $("#pbresult-round-input-id").val();

    var jsonData = { "game":mGameId, "count": CountPerPage, "start": dtStart, "end": dtEnd, "round": nRound };
    jsonData = JSON.stringify(jsonData);

    $.ajax({
        url: FURL + '/pbapi/resultcnt',
        data: { json_: jsonData },
        dataType: 'json',
        type: 'post',
        success: function(jResult) {
            //console.log(jResult);
            if (jResult.status == "success") {
                TotalCount = jResult.data.count;
                setFirstPage();
                requestGameResult();
            }
        },
        error: function(request, status, error) {
            //console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
        }

    });
}


function psresultLoop() {

    var currentTime = new Date();

    if (currentTime.getSeconds() == 10 && (currentTime.getMinutes() % 10 == 3 || currentTime.getMinutes() % 10 == 8)) {
        requestGameResult();
    }

    // 1초뒤에 다시 실행
    setTimeout(function() {
        psresultLoop();
    }, 1000);

}