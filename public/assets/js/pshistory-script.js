$(document).ready(function() {
    setNavBarElement();
    addEventListner();
    requestTotalPage();
    setTimeout(function() {
        pbhitoryLoop();
    }, 300000);
});

function requestPageInfo() {
    requestBetHistory();
}


//Function to Show Betting History
function ShowBetHistory(jsonBetData) {
    var elemBetDataTb = document.getElementById("pbbet-table-id");
    var strBuf = "";

    var strBetMode = "";
    var strBetTarget = "";
    var strResultTarget = "";
    var strWinMoney = "";
    var strResult = "";
    var curPage = getActivePage();
    var firstIdx = (curPage - 1) * CountPerPage;

    for (nRow in jsonBetData) {

        strBuf += "<tr><td>";
        strBuf += (parseInt(nRow) + firstIdx + 1);
        strBuf += "</td><td>";
        strBuf += jsonBetData[nRow].bet_time;
        strBuf += "</td><td>";
        strBuf += jsonBetData[nRow].bet_round_no;
        strBuf += "</td><td>";
        strBuf += jsonBetData[nRow].bet_mb_uid;
        strBuf += "</td>";

        jsonBetData[nRow].bet_mode = parseInt(jsonBetData[nRow].bet_mode);

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
            strResult = "<td  class = 'pb-home-table-betstate-loss'>미적중";
            strWinMoney = parseInt(jsonBetData[nRow].bet_win_money).toLocaleString() + "원";
        } else if (jsonBetData[nRow].bet_state == "3") {
            strResult = "<td  class = 'pb-home-table-betstate-earn'>적중";
            strWinMoney = parseInt(jsonBetData[nRow].bet_win_money).toLocaleString() + "원";
        } else if (jsonBetData[nRow].bet_state == "4") {
            strResult = "<td  class = 'pb-home-table-betstate-wait'>무효";
        }

        strBuf += strWinMoney;
        strBuf += "</td>";
        strBuf += strResult;
        strBuf += "</td><td>";
        if (jsonBetData[nRow].rw_point != null)
            strBuf += jsonBetData[nRow].rw_point;
        else strBuf += "0";
        strBuf += "</td></tr>";

    }

    if (strBuf.length < 1) {
        strBuf = "<tr><td colspan='15'>자료가 없습니다.</td></tr>";
    }
    elemBetDataTb.innerHTML = strBuf;

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


function ShowBetAccount(arrBetAccount) {

    $("#total-betmoney-id").text("0");
    $("#total-winmoney-id").text("0");
    $("#total-lossmoney-id").text("0");
    $("#total-benefit-id").text("0");

    if (arrBetAccount == null) {
        $(".pbresult-list-page-div p").css('display', 'none');
        return;
    }
    if (arrBetAccount.length != 4) return;
    $(".pbresult-list-page-div p").css('display', 'block');

    $("#total-betmoney-id").text(parseInt(arrBetAccount[0]).toLocaleString() + " 원");
    $("#total-winmoney-id").text(parseInt(arrBetAccount[1]).toLocaleString() + " 원");
    $("#total-lossmoney-id").text(parseInt(arrBetAccount[2]).toLocaleString() + " 원");
    $("#total-benefit-id").text(parseInt(arrBetAccount[3]).toLocaleString() + " 원");

}


function addEventListner() {
    $("#pbhistory-list-view-but-id").click(function() {

        requestTotalPage();
    });

    $("#pbhistory-number-select-id").change(function() {
        requestTotalPage();
    });
}
//Function to Request Betting History to WebServer
function requestBetHistory() {

    var dtStart = $("#pbhistory-datestart-input-id").val();
    var dtEnd = $("#pbhistory-dateend-input-id").val();
    var strRound = $("#pbhistory-roundid-input-id").val();
    var strUser = $("#pbhistory-userid-input-id").val();
    var nMode = $("#pbhistory-game-select-id").val();
    var nPage = getActivePage();
    var strEmp = "";
    if ($("#pbhistory-empid-input-id").length > 0) {
        strEmp = $("#pbhistory-empid-input-id").val();
    }

    var jsonData = {
        "count": CountPerPage,
        "page": nPage,
        "start": dtStart,
        "end": dtEnd,
        "emp": strEmp,
        "user": strUser,
        "round": strRound,
        "mode": nMode,
        "game": mGameId,
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
            // console.log(jResult);
            if (jResult.status == "success") {
                ShowBetHistory(jResult.data);
            }
        },
        error: function(request, status, error) {
            $(".loading").hide();
            // console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
        }

    });
}


function requestTotalPage() {


    var dtStart = $("#pbhistory-datestart-input-id").val();
    var dtEnd = $("#pbhistory-dateend-input-id").val();
    CountPerPage = $("#pbhistory-number-select-id").val();
    var strRound = $("#pbhistory-roundid-input-id").val();
    var strUser = $("#pbhistory-userid-input-id").val();
    var nMode = $("#pbhistory-game-select-id").val();
    var strEmp = "";
    if ($("#pbhistory-empid-input-id").length > 0) {
        strEmp = $("#pbhistory-empid-input-id").val();
    }
    var jsonData = {
        "count": CountPerPage,
        "start": dtStart,
        "end": dtEnd,
        "emp": strEmp,
        "user": strUser,
        "round": strRound,
        "mode": nMode,
        "game": mGameId,
    };
    jsonData = JSON.stringify(jsonData);

    $.ajax({
        url: FURL + '/pbapi/betlistcnt',
        data: { json_: jsonData },
        dataType: 'json',
        type: 'post',
        success: function(jResult) {
            // console.log(jResult);
            if (jResult.status == "success") {
                TotalCount = jResult.data.count;
                setFirstPage();
                requestBetHistory();
                ShowBetAccount(jResult.account);
            }
        },
        error: function(request, status, error) {
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        }

    });
}


function pshitoryLoop() {

    var currentTime = new Date();

    if (currentTime.getSeconds() == 10 && currentTime.getMinutes() % 5 == 0 ) {
        requestBetHistory();
    }

    // 1초뒤에 다시 실행
    setTimeout(function() {
        pshitoryLoop();
    }, 1000);

}