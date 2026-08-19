$(document).ready(function() {
    setNavBarElement();
    $("#pbhistory-userid-input-id").css("width", "115px");
    $("#pbhistory-userid-input-id").attr("placeholder", "아아디||등록번호");
    addEventListner();
    requestPageInfo();
    setTimeout(function() {
        requestTotalPage(false);
    }, 1000);
    setTimeout(function() {
        pbhitoryLoop();
    }, 5000);
});

function requestPageInfo() {
    requestBetHistory();
}


//Function to Show Betting History
function ShowBetHistory(jsonBetData, level) {
    var elemBetDataTb = document.getElementById("pbbet-table-id");
    var strBuf = "";
    var curPage = getActivePage();
    var firstIdx = (curPage - 1) * CountPerPage;

    for (nRow in jsonBetData) {

        strBuf += "<tr><td>";
        strBuf += (parseInt(nRow) + firstIdx + 1);
        strBuf += "</td><td>";
        strBuf += jsonBetData[nRow].bet_mb_uid;
        strBuf += "</td><td>";
        strBuf += jsonBetData[nRow].bet_mb_fid;
        strBuf += "</td><td>";
        strBuf += jsonBetData[nRow].bet_time;
        strBuf += "</td><td>";
        strBuf += parseInt(jsonBetData[nRow].bet_money).toLocaleString() + "원";
        strBuf += "</td><td>";
        strBuf += parseInt(jsonBetData[nRow].bet_win_money).toLocaleString() + "원";
        strBuf += "</td>";
        strResult = "<td>";
        if (parseInt(jsonBetData[nRow].bet_win_money) > parseInt(jsonBetData[nRow].bet_money)) {
            strResult = "<td  class = 'pb-home-table-betstate-earn'>승리";
        } else if (jsonBetData[nRow].bet_win_money == jsonBetData[nRow].bet_money) {
            strResult = "<td  class = 'pb-home-table-betstate-wait'>비김";
        } else {
            strResult = "<td  class = 'pb-home-table-betstate-loss'>패배"; //
        }
        strBuf += strResult;
        strBuf += "</td><td>";
        if (jsonBetData[nRow].rw_point != null)
            strBuf += Math.floor(jsonBetData[nRow].rw_point).toLocaleString();
        else strBuf += "0";
        if(level >= LEVEL_ADMIN+1){
            strBuf += "</td>";
            strBuf += toCardInfo(jsonBetData[nRow].bet_player_1, jsonBetData[nRow].bet_player_seat==1);
            strBuf += "</td>";
            strBuf += toCardInfo(jsonBetData[nRow].bet_player_2, jsonBetData[nRow].bet_player_seat==2);
            strBuf += "</td>";
            strBuf += toCardInfo(jsonBetData[nRow].bet_player_3, jsonBetData[nRow].bet_player_seat==3);
            strBuf += "</td>";
            strBuf += toCardInfo(jsonBetData[nRow].bet_player_4, jsonBetData[nRow].bet_player_seat==4);
            strBuf += "</td>";
            strBuf += toCardInfo(jsonBetData[nRow].bet_player_5, jsonBetData[nRow].bet_player_seat==5);
            strBuf += "</td>";
            strBuf += toCardInfo(jsonBetData[nRow].bet_player_6, jsonBetData[nRow].bet_player_seat==6);
            strBuf += "</td>";
            strBuf += toCardInfo(jsonBetData[nRow].bet_player_7, jsonBetData[nRow].bet_player_seat==7);
            strBuf += "</td>";
            strBuf += toCardInfo(jsonBetData[nRow].bet_player_8, jsonBetData[nRow].bet_player_seat==8);
            strBuf += "</td>";
            strBuf += toCardInfo(jsonBetData[nRow].bet_player_9, jsonBetData[nRow].bet_player_seat==9);
            strBuf += "</td>";
            strBuf += toCardInfo(jsonBetData[nRow].bet_community);
        }
       
        strBuf += "</td></tr>";

    }

    if (strBuf.length < 1) {
        strBuf = "<tr><td colspan='10'>자료가 없습니다.</td></tr>";
    }
    elemBetDataTb.innerHTML = strBuf;

}

function toCardInfo(strCards, mark=false){
    let cards = strCards.trim().split(',');

    let buf = "<td>";
    if(mark)
        buf = "<td style='border:1px solid #0000ff;'>";

    let sign = "";
    let cardNum = "";
    let num = 0

    for(let card of cards){
        card = card.trim();
        if(card.length < 1)
            continue;
        card = parseInt(card);
        sign = Math.ceil(card/13);
        num = card%13; 

        if(num == 0){
            cardNum = "K";    
        } else if(num == 12){
            cardNum = "Q";    
        } else if(num == 11){
            cardNum = "J";    
        } else if(num < 11){
            cardNum = num;    
        }
        
        if(sign == 1){
            buf += "<span style='color:black'>♠"; 
        } else if(sign == 2){
            buf += "<span style='color:black'>♣"; 
        } else if(sign == 3){
            buf += "<span style='color:red'>♦"; 
        } else if(sign == 4){
            buf += "<span style='color:red'>♥"; 
        } else
            buf += "<span style='color:black'>"; 

        buf += cardNum+"</span>" + " ";
    }
    return buf;
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
    CountPerPage = $("#pbhistory-number-select-id").val();
    var strUser = $("#pbhistory-userid-input-id").val();
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
        "game": mGameId
    };
    jsonData = JSON.stringify(jsonData);
    // $(".loading").show();
    $.ajax({
        url: FURL + '/api/hlbetlist',
        data: { json_: jsonData },
        type: 'post',
        dataType: "json",
        success: function(jResult) {
            // $(".loading").hide();
            // console.log(jResult);
            if (jResult.status == "success") {
                ShowBetHistory(jResult.data, jResult.level);
                ShowBetAccount(jResult.account);
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
        "game": mGameId
    };
    jsonData = JSON.stringify(jsonData);
    $(".loading").show();

    $.ajax({
        url: FURL + '/api/hlbetlistcnt',
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
    let tmCount = 60000; 
    if(mObjUser && mObjUser.mb_level >= LEVEL_ADMIN)
        tmCount = 20000;

    // 1초뒤에 다시 실행
    setTimeout(function() {
        pbhitoryLoop();
    }, tmCount);
}