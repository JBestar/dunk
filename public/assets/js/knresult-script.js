$(document).ready(function() {
    setNavBarElement();
    addEventListner();
    requestTotalPage();
    pbresultLoop();
});

function requestPageInfo() {
    requestGameResult();
}

//Function to Show Betting History
function ShowGameResult(arrResult) {
    var elemResultTb = document.getElementById("pbresult-list-table-id");

    var strBuf = "";
    let arrNum = null;
    for (nRow in arrResult) {

        strBuf += "<tr><td>";
        strBuf += arrResult[nRow].round_date;
        strBuf += "</td><td>";
        strBuf += arrResult[nRow].round_num;
        strBuf += "</td><td>";
        strBuf += arrResult[nRow].round_power;
        strBuf += "</td><td>";
        if (arrResult[nRow].round_result_1 == 'P')
            strBuf += "<span class='pbresult-rule-odd-span'>홀</span>";
        else if (arrResult[nRow].round_result_1 == 'B')
            strBuf += "<span class='pbresult-rule-even-span'>짝</span>";
        strBuf += "</td><td>";
        if (arrResult[nRow].round_result_2 == 'P')
            strBuf += "<span class='pbresult-rule-odd-span'><i class='glyphicon glyphicon-arrow-down'></i></span>";
        else if (arrResult[nRow].round_result_2 == 'B')
            strBuf += "<span class='pbresult-rule-even-span'><i class='glyphicon glyphicon-arrow-up'></i></span>";
        strBuf += "</td><td>";

        arrNum = getRoundNum(arrResult[nRow]);
        strBuf += arrNum[2];
        strBuf += "</td><td>";
        if (arrResult[nRow].round_result_3 == 'P')
            strBuf += "<span class='pbresult-rule-odd-span'>홀</span>";
        else if (arrResult[nRow].round_result_3 == 'B')
            strBuf += "<span class='pbresult-rule-even-span'>짝</span>";
        strBuf += "</td><td>";
        if (arrResult[nRow].round_result_4 == 'P')
            strBuf += "<span class='pbresult-rule-odd-span'><i class='glyphicon glyphicon-arrow-down'></i></span>";
        else if (arrResult[nRow].round_result_4 == 'B')
            strBuf += "<span class='pbresult-rule-even-span'><i class='glyphicon glyphicon-arrow-up'></i></span>";
        strBuf += "</td><td>";
        strBuf += arrNum[0];
        strBuf += "</td><td>";
        if (arrResult[nRow].round_result_5 == 'P')
            strBuf += "<span class='pbresult-rule-odd-span'>홀</span>";
        else if (arrResult[nRow].round_result_5 == 'B')
            strBuf += "<span class='pbresult-rule-even-span'>짝</span>";
        strBuf += "</td><td>";
        if (arrResult[nRow].round_result_6 == 'P')
            strBuf += "<span class='pbresult-rule-odd-span'><i class='glyphicon glyphicon-arrow-down'></i></span>";
        else if (arrResult[nRow].round_result_6 == 'B')
            strBuf += "<span class='pbresult-rule-even-span'><i class='glyphicon glyphicon-arrow-up'></i></span>";
        strBuf += "</td><td>";
        if(mGameId == 1){
            strBuf += "<a href='"+FURL+"/result/pbresult_edit/" + arrResult[nRow].round_fid + "' >수정</a>";
            strBuf += "<a href='"+FURL+"/result/pbbetchange/" + arrResult[nRow].round_date + "/" + arrResult[nRow].round_num + "' >적특</a>";
        } else if(mGameId == 14){
            strBuf += "<a href='"+FURL+"/result/skresult_edit/" + arrResult[nRow].round_fid + "' >수정</a>";
            strBuf += "<a href='"+FURL+"/result/skbetchange/" + arrResult[nRow].round_date + "/" + arrResult[nRow].round_num + "' >적특</a>";
        } else if(mGameId == 2){
            strBuf += "<a href='"+FURL+"/result/dpresult_edit/" + arrResult[nRow].round_fid + "' >수정</a>";
            strBuf += "<a href='"+FURL+"/result/dpbetchange/" + arrResult[nRow].round_date + "/" + arrResult[nRow].round_num + "' >적특</a>";
        } else if(mGameId == 5){
            strBuf += "<a href='"+FURL+"/result/bbresult_edit/" + arrResult[nRow].round_fid + "' >수정</a>";
            strBuf += "<a href='"+FURL+"/result/bbbetchange/" + arrResult[nRow].round_date + "/" + arrResult[nRow].round_num + "' >적특</a>";
        } else if(mGameId == 9){
            strBuf += "<a href='"+FURL+"/result/e5result_edit/" + arrResult[nRow].round_fid + "' >수정</a>";
            strBuf += "<a href='"+FURL+"/result/e5betchange/" + arrResult[nRow].round_date + "/" + arrResult[nRow].round_num + "' >적특</a>";
        } else if(mGameId == 10){
            strBuf += "<a href='"+FURL+"/result/e3result_edit/" + arrResult[nRow].round_fid + "' >수정</a>";
            strBuf += "<a href='"+FURL+"/result/e3betchange/" + arrResult[nRow].round_date + "/" + arrResult[nRow].round_num + "' >적특</a>";
        } else if(mGameId == 11){
            strBuf += "<a href='"+FURL+"/result/r5result_edit/" + arrResult[nRow].round_fid + "' >수정</a>";
            strBuf += "<a href='"+FURL+"/result/r5betchange/" + arrResult[nRow].round_date + "/" + arrResult[nRow].round_num + "' >적특</a>";
        } else if(mGameId == 12){
            strBuf += "<a href='"+FURL+"/result/r3result_edit/" + arrResult[nRow].round_fid + "' >수정</a>";
            strBuf += "<a href='"+FURL+"/result/r3betchange/" + arrResult[nRow].round_date + "/" + arrResult[nRow].round_num + "' >적특</a>";
        }
        strBuf += "</td></tr>";

    }

    if (strBuf.length < 1) {
        strBuf = "<tr><td colspan='10'>자료가 없습니다.</td></tr>";
    }

    elemResultTb.innerHTML = strBuf;
}

function getRoundNum(round) {
    var sNum = "";
    var sSum = 0;
    var sSup = -1;
    arrNormal = round.round_normal.split(",");
    if (arrNormal.length >= 5) {

        arrNormal.forEach((num) => {
            sNum += parseInt(num) + ",";
            sSum += parseInt(num);
            if(sSup < 0)
             sSup = parseInt(num);
        });
        sNum += round.round_power;
    } else {
        sNum = "-";
        sSum = "-";
        sSup = "-";
    }
    return [sNum, sSum, sSup];
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
    var dtStart = document.getElementById("pbresult-datestart-input-id").value;
    var dtEnd = document.getElementById("pbresult-dateend-input-id").value;
    var nRound = document.getElementById("pbresult-round-input-id").value;
    var nPage = getActivePage();

    var jsonData = {  "game":mGameId, "count": CountPerPage, "page": nPage, "start": dtStart, "end": dtEnd, "round": nRound };
    jsonData = JSON.stringify(jsonData);
    $(".loading").show();
    $.ajax({
        url: FURL + '/pbapi/result',
        data: { json_: jsonData },
        type: 'post',
        dataType: 'json',
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

    var dtStart = document.getElementById("pbresult-datestart-input-id").value;
    var dtEnd = document.getElementById("pbresult-dateend-input-id").value;
    CountPerPage = document.getElementById("pbresult-number-select-id").value;
    var nRound = document.getElementById("pbresult-round-input-id").value;
    var jsonData = {  "game":mGameId, "count": CountPerPage, "start": dtStart, "end": dtEnd, "round": nRound };
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



function pbresultLoop() {

    var currentTime = new Date();

    if (currentTime.getSeconds() == 10 && (currentTime.getMinutes() % 10 == 3 || currentTime.getMinutes() % 10 == 8)) {
        requestGameResult();
    }


    // 1초뒤에 다시 실행
    setTimeout(function() {
        pbresultLoop();
    }, 1000);

}