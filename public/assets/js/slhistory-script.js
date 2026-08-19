$(document).ready(function() {
    setNavBarElement();
    addEventListner();
    requestPageInfo();
    setTimeout(function() {
        requestTotalPage(false);
    }, 1000);
    setTimeout(function() {
        pbhitoryLoop();
    }, 300000);
});

function requestPageInfo() {
    requestBetHistory();
}


//Function to Show Betting History
function ShowBetHistory(jsonBetData) {
    let elemBetDataTb = document.getElementById("pbbet-table-id");
    let strBuf = "";
    let curPage = getActivePage();
    let firstIdx = (curPage - 1) * CountPerPage;

    for (nRow in jsonBetData) {

        strBuf += "<tr><td>";
        strBuf += (parseInt(nRow) + firstIdx + 1);
        strBuf += "</td><td>";
        strBuf += "<a onclick='popupMemberUid(\"" + jsonBetData[nRow].bet_mb_uid + "\")' class='link-member'>"+ jsonBetData[nRow].bet_mb_uid + "</a>";
        strBuf += "</td><td>";
        strBuf += jsonBetData[nRow].bet_time;
        strBuf += "</td><td>";
        if (jsonBetData[nRow].prd_name != null){
            strBuf += jsonBetData[nRow].prd_name;
            if(parseInt(jsonBetData[nRow].bet_game_type) == 215 && mGameId == 8)
                strBuf += "(NEW)"; 
        }
        else strBuf += jsonBetData[nRow].bet_game_type;
        strBuf += "</td><td>";
        if (jsonBetData[nRow].game_name != null)
            strBuf += jsonBetData[nRow].game_name;
        else
            strBuf += jsonBetData[nRow].bet_table_code;
        strBuf += "</td><td>";
        strBuf += parseInt(jsonBetData[nRow].bet_money).toLocaleString() + "원";
        strBuf += "</td><td>";
        strBuf += parseInt(jsonBetData[nRow].bet_win_money).toLocaleString() + "원";
        strBuf += "</td>";
        strResult = "<td>";
        if (parseInt(jsonBetData[nRow].bet_win_money) > parseInt(jsonBetData[nRow].bet_money)) {
            strResult = "<td  class = 'pb-home-table-betstate-earn'>적중";
        } else if (jsonBetData[nRow].bet_win_money == jsonBetData[nRow].bet_money) {
            strResult = "<td  class = 'pb-home-table-betstate-wait'>비김";
        } else {
            strResult = "<td  class = 'pb-home-table-betstate-loss'>미적중"; //
        }

        strBuf += strResult;
        strBuf += "</td><td>";
        if (jsonBetData[nRow].rw_point != null)
            strBuf += Math.floor(jsonBetData[nRow].rw_point).toLocaleString();
        else strBuf += "0";
        strBuf += "</td></tr>";

    }

    if (strBuf.length < 1) {
        strBuf = "<tr><td colspan='10'>자료가 없습니다.</td></tr>";
    }
    elemBetDataTb.innerHTML = strBuf;

}


function ShowBetAccount(arrBetAccount) {

    $("#total-betmoney-id").text("0");
    $("#total-winmoney-id").text("0");
    $("#total-lossmoney-id").text("0");
    $("#total-benefit-id").text("0");
    $("#total-blank-id").text("0");

    if (arrBetAccount == null) {
        $(".pbresult-list-page-div p").css('display', 'none');
        return;
    }
    if (arrBetAccount.length != 5) return;
    $(".pbresult-list-page-div p").css('display', 'block');

    $("#total-betmoney-id").text(parseInt(arrBetAccount[0]).toLocaleString() + " 원");
    $("#total-winmoney-id").text(parseInt(arrBetAccount[1]).toLocaleString() + " 원");
    $("#total-lossmoney-id").text(parseInt(arrBetAccount[2]).toLocaleString() + " 원");
    $("#total-benefit-id").text(parseInt(arrBetAccount[3]).toLocaleString() + " 원");
    $("#total-blank-id").text(parseInt(arrBetAccount[4]).toLocaleString() + " P");

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
    CountPerPage = $("#pbhistory-number-select-id").val();
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
        "mode": nMode,
        "game": mGameId
    };
    jsonData = JSON.stringify(jsonData);
    $(".loading").show();
    $.ajax({
        url: FURL + '/api/slbetlist',
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
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        }

    });
}


function requestTotalPage(bReqPage = true) {

    var dtStart = $("#pbhistory-datestart-input-id").val();
    var dtEnd = $("#pbhistory-dateend-input-id").val();
    CountPerPage = $("#pbhistory-number-select-id").val();
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
        "mode": nMode,
        "game": mGameId
    };
    jsonData = JSON.stringify(jsonData);
    $(".loading").show();

    $.ajax({
        url: FURL + '/api/slbetlistcnt',
        data: { json_: jsonData },
        dataType: 'json',
        type: 'post',
        success: function(jResult) {
            $(".loading").hide();
            // console.log(jResult);
            if (jResult.status == "success") {
                TotalCount = jResult.data.count;
                setFirstPage();
                if(bReqPage)
                    requestBetHistory();
                ShowBetAccount(jResult.account);
            }
        },
        error: function(request, status, error) {
            $(".loading").hide();
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        }

    });
}


function pbhitoryLoop() {

    requestBetHistory();

    // 1초뒤에 다시 실행
    setTimeout(function() {
        pbhitoryLoop();
    }, 300000);
}