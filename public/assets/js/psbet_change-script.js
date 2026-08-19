var mBetData = null;
$(document).ready(function() {
    setNavBarElement();
    requestBetHistory();
    addEventListner();
});


//Function to Show Betting History
function ShowBetHistory(jsonBetData) {
    var elemBetDataTb = document.getElementById("pbbetchange-table-id");
    var strBuf = "";

    var nCurBetCnt = 0;
    var nCurBetMoney = 0;
    var strBetMode = "";
    var strBetTarget = "";
    var strResultTarget = "";
    var strWinMoney = "";
    var strResult = "";

    for (nRow in jsonBetData) {

        strBuf += "<tr><td>";
        strBuf += (parseInt(nRow) + 1);
        strBuf += "</td><td>";
        strBuf += jsonBetData[nRow].bet_time;
        strBuf += "</td><td>";
        strBuf += jsonBetData[nRow].bet_round_no;
        strBuf += "</td><td>";
        strBuf += jsonBetData[nRow].bet_mb_uid;
        strBuf += "</td>";

        strBetMode = "<td>";
        if (jsonBetData[nRow].bet_mode == 1) {
            strBetMode = "<td class = 'pb-home-table-betmode-power'> 좌우";
        } else if (jsonBetData[nRow].bet_mode == 2) {
            strBetMode = "<td class = 'pb-home-table-betmode-line'> 줄수";
        } else if (jsonBetData[nRow].bet_mode == 3) {
            strBetMode = "<td class = 'pb-home-table-betmode-normal'> 홀짝";
        } else {
            strBetMode = "<td class = 'pb-home-table-betmode-line'> 조합";
        }

        strBetTarget = "";
        strResultTarget = "";
        if (jsonBetData[nRow].bet_mode >= 1 && jsonBetData[nRow].bet_mode <= 3) {
            strBetTarget = getHtmlByBet(jsonBetData[nRow].bet_mode, jsonBetData[nRow].bet_target);
            strResultTarget = getHtmlByBet(jsonBetData[nRow].bet_mode, jsonBetData[nRow].bet_result);
        } else if (jsonBetData[nRow].bet_mode >= 4 && jsonBetData[nRow].bet_mode <= 7) {
            if (jsonBetData[nRow].bet_result.length > 1) {
                strResultTarget = getHtmlByBet("1", jsonBetData[nRow].bet_result.charAt(0)) + getHtmlByBet("2", jsonBetData[nRow].bet_result.charAt(1));
            }
            if (jsonBetData[nRow].bet_mode == 4) {
                strBetTarget = getHtmlByBet("1", "P") + getHtmlByBet("2", "P");
            } else if (jsonBetData[nRow].bet_mode == 5) {
                strBetTarget = getHtmlByBet("1", "P") + getHtmlByBet("2", "B");
            } else if (jsonBetData[nRow].bet_mode == 6) {
                strBetTarget = getHtmlByBet("1", "B") + getHtmlByBet("2", "P");
            } else if (jsonBetData[nRow].bet_mode == 7) {
                strBetTarget = getHtmlByBet("1", "B") + getHtmlByBet("2", "B");
            }

        }

        strBuf += strBetMode;
        strBuf += "</td><td>";
        strBuf += parseInt(jsonBetData[nRow].bet_money).toLocaleString();
        strBuf += "</td><td>";
        strBuf += jsonBetData[nRow].bet_ratio;
        strBuf += "</td><td>";
        strBuf += strBetTarget;
        strBuf += "</td><td>";
        strBuf += strResultTarget;
        strBuf += "</td><td>";

        strWinMoney = "";
        strResult = "<td>";
        if (jsonBetData[nRow].bet_state == "1") {
            strResult = "<td  class = 'pb-home-table-betstate-wait'>대기중";
        } else if (jsonBetData[nRow].bet_state == "2") {
            strResult = "<td  class = 'pb-home-table-betstate-loss'>미적중"; //
        } else if (jsonBetData[nRow].bet_state == "3") {
            strResult = "<td  class = 'pb-home-table-betstate-earn'>적중";
            strWinMoney = parseInt(jsonBetData[nRow].bet_win_money - jsonBetData[nRow].bet_money).toLocaleString() + "원";
        } else if (jsonBetData[nRow].bet_state == "4") {
            strResult = "<td  class = 'pb-home-table-betstate-wait'>무효";
        }

        strBuf += strWinMoney;
        strBuf += "</td>";
        strBuf += strResult;
        strBuf += "</td><td>";
        if (jsonBetData[nRow].bet_state == "4") {
            strBuf += "<button name='" + jsonBetData[nRow].bet_fid + "'>복구처리</button>   ";
        } else {
            strBuf += "<button name='" + jsonBetData[nRow].bet_fid + "'>결과처리</button>   ";
            strBuf += "<button name='" + jsonBetData[nRow].bet_fid + "'>무효처리</button>   ";
        }
        strBuf += "</td></tr>";

    }
    if (strBuf.length < 1) {
        strBuf = "<tr><td colspan='13'>자료가 없습니다.</td></tr>";
    }

    elemBetDataTb.innerHTML = strBuf;


    addTableBtnEvent();
}

function addTableBtnEvent() {
    var elemTable = document.getElementById("pbbetchange-table-id");
    var elemTableBtns = elemTable.getElementsByTagName("button");
    if (elemTableBtns == null)
        return;

    var i;
    for (i = 0; i < elemTableBtns.length; i++) {
        elemTableBtns[i].addEventListener("click", function() {

            if (this.innerHTML.search("결과처리") >= 0 || this.innerHTML.search("복구처리") >= 0) {
                var jsData = {"data":[this.name], "game":mGameId};
                requestBetProcess(jsData);
            } else if (this.innerHTML.search("무효처리") >= 0) {
                var jsData = {"data":[this.name], "game":mGameId};
                requestBetIgnore(jsData);
            }
        });
    }
}


function getHtmlByBet(strMode, strTarget) {
    var strResult = "";
    iMode = parseInt(strMode);
    if (iMode == 1) {
        if (strTarget == "P")
            strResult = "<div  class = 'pb-home-odd-span'>좌</div> "
        else if (strTarget == "B")
            strResult = "<div  class = 'pb-home-even-span'>우</div> ";
    } else if (iMode == 2) {
        if (strTarget == "P")
            strResult = "<div  class = 'pb-home-odd-span'>3</div> "
        else if (strTarget == "B")
            strResult = "<div  class = 'pb-home-even-span'>4</div> ";
    } else if (iMode == 3) {
        if (strTarget == "P")
            strResult = "<div  class = 'pb-home-odd-span'>홀</div> "
        else if (strTarget == "B")
            strResult = "<div  class = 'pb-home-even-span'>짝</div> ";
    }
    return strResult;
}

function addEventListner() {
    $("#pbbetchange-view-but-id").click(function() {

        requestBetHistory();
    });

    $("#pbbetchange-ignore-but-id").click(function() {

        if (mBetData == null) return;
        if (mBetData.length < 1) return;

        var arrData = new Array();
        for (nRow in mBetData)
            arrData[nRow] = mBetData[nRow].bet_fid;
            
        var jsData = {"data":arrData, "game":mGameId};
        requestBetIgnore(jsData);
    });

    $("#pbbetchange-process-but-id").click(function() {

        if (mBetData == null) return;
        if (mBetData.length < 1) return;

        var arrData = new Array();
        for (nRow in mBetData)
            arrData[nRow] = mBetData[nRow].bet_fid;
        
        var jsData = {"data":arrData, "game":mGameId};
        requestBetProcess(jsData);
    });
}


//Function to Request Betting History to WebServer
function requestBetHistory() {

    var dtStart = $("#pbbetchange-date-input-id").val();
    var dtEnd = $("#pbbetchange-date-input-id").val();
    var strRound = $("#pbbetchange-round-input-id").val();

    if (strRound < 1) return;

    var jsonData = {
        "count": 100,
        "page": 1,
        "start": dtStart,
        "end": dtEnd,
        "user": "",
        "round": strRound,
        "mode": 0,
        "emp": "",
        "game":mGameId
    };
    jsonData = JSON.stringify(jsonData);
    $(".loading").show();
    $.ajax({
        url: FURL + '/pbapi/betlist',
        data: { json_: jsonData },
        type: 'post',
        dataType: "json",
        success: function(jResult) {
            $(".loading").hide();
            //console.log(jResult);
            if (jResult.status == "success") {
                mBetData = jResult.data;
                ShowBetHistory(jResult.data);
            }
        },
        error: function(request, status, error) {
            $(".loading").hide();
            //console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
        }

    });
}


function requestBetIgnore(jsData) {
    var jsonData = JSON.stringify(jsData);

    $.ajax({
        url: FURL + '/pbapi/betignore',
        data: { json_: jsonData },
        type: 'post',
        dataType: "json",
        success: function(jResult) {
            if (jResult.status == "success") {
                requestBetHistory();
            }
        },
        error: function(request, status, error) {
            //  console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
        }

    });
}


function requestBetProcess(jsData) {

    var jsonData = JSON.stringify(jsData);

    $.ajax({
        url: FURL + '/pbapi/betprocess',
        data: { json_: jsonData },
        type: 'post',
        dataType: "json",
        success: function(jResult) {
            //console.log(jResult);
            if (jResult.status == "success") {
                requestBetHistory();
            }
        },
        error: function(request, status, error) {
            //  console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
        }

    });
}