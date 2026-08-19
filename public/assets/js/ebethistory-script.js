$(document).ready(function() {
    setNavBarElement();
    addEventListner();
    requestPageInfo();
    setTimeout(function() {
        requestTotalPage(false);
    }, 1000);
    setTimeout(function() {
        pbhitoryLoop();
    }, 60000);
});

function requestPageInfo() {
    requestBetHistory();
}


//Function to Show Betting History
function ShowBetHistory(jsonBetData) {

    var state = $("#pbhistory-state-select-id").val();

    var strHead = "<th>ID</th><th>아이디</th><th>배팅시간</th><th>게임종류</th><th>게임방</th>";
    strHead += "<th>요청금</th><th>배팅금</th><th>넘기기금</th><th>배팅타입</th><th>배팅선택</th><th>경기결과</th><th>적중금</th>";
    if(state > 0){
        strHead+= "<th>처리결과</th><th>처리</th>";
    } else {
        strHead+= "<th>배팅결과</th><th>포인트</th>";
    }
    $("#pbbet-table-head-id").html(strHead);

    var type = -1;
    if ($("#pbhistory-type-select-id").length > 0) {
        type = $("#pbhistory-type-select-id").val();
    }

    var elemBetDataTb = document.getElementById("pbbet-table-id");
    var strBuf = "";
    var strWinMoney = "";
    var curPage = getActivePage();
    var firstIdx = (curPage - 1) * CountPerPage;

    if(jsonBetData){

        for (nRow in jsonBetData) {

            strBuf += "<tr><td>";
            strBuf += (parseInt(nRow) + firstIdx + 1);
            strBuf += "</td><td>";
            strBuf += "<a onclick='popupMemberUid(\"" + jsonBetData[nRow].bet_mb_uid + "\")' class='link-member'>"+ jsonBetData[nRow].bet_mb_uid + "</a>";
            strBuf += "</td><td>";
            strBuf += jsonBetData[nRow].bet_time;
            strBuf += "</td><td>";
            strBuf += getGameName(jsonBetData[nRow].bet_game_type);
            strBuf += "</td><td>";
            if (jsonBetData[nRow].game_name != null)
                strBuf += jsonBetData[nRow].game_name;
            else if(jsonBetData[nRow].bet_game_id > 0){
                strBuf += jsonBetData[nRow].bet_round_no;
            } else
                strBuf += jsonBetData[nRow].bet_table_code;
            strBuf += "</td><td>";
            if(jsonBetData[nRow].obj_id > 0)
                strBuf += parseInt(jsonBetData[nRow].obj_id).toLocaleString() + "원";
            strBuf += "</td><td>";
            if(type == 0){              //누르기
                strBuf += parseInt(jsonBetData[nRow].bet_money-jsonBetData[nRow].bet_balance).toLocaleString() + "원";
            } else if(type == 1){       //넘기기
                strBuf += parseInt(jsonBetData[nRow].bet_balance).toLocaleString() + "원";
            } else {
                strBuf += parseInt(jsonBetData[nRow].bet_money).toLocaleString() + "원";
            }
            strBuf += "</td><td>";
            if(type == 1){
                if(jsonBetData[nRow].bet_balance > 0){
                    strBuf += parseInt(jsonBetData[nRow].bet_balance).toLocaleString() + " ";
                }
                if(jsonBetData[nRow].bet_balance_rate > 0 && jsonBetData[nRow].bet_balance_rate < 1)
                        strBuf += "(" + (parseFloat(jsonBetData[nRow].bet_balance_rate)*100) + "%)" ;
            } else {
                if(parseInt(jsonBetData[nRow].bet_type) == 1) {  //if over bet
                    if(jsonBetData[nRow].bet_balance > 0){
                        strBuf += parseInt(jsonBetData[nRow].bet_balance).toLocaleString() + " "; 
                    }
                    if(jsonBetData[nRow].bet_balance_rate > 0 && jsonBetData[nRow].bet_balance_rate < 1)
                        strBuf += "(" + (parseFloat(jsonBetData[nRow].bet_balance_rate)*100) + "%)" ;
                }
            }
            strBuf += "</td>";
            if(type == 0){
                strBuf += "<td  class = 'pb-home-table-betstate-wait'>누르기";
                if(jsonBetData[nRow].bet_balance_no > 0 && jsonBetData[nRow].bet_auto_press > 0){
                    strBuf += " ("+ jsonBetData[nRow].bet_balance_no + ")";
                }
            } else if(type == 1){
                strBuf += "<td  class = 'pb-home-table-betstate-loss'>넘기기";
                if(jsonBetData[nRow].bet_balance_no > 0){
                    strBuf += " ("+ jsonBetData[nRow].bet_balance_no; 
                    strBuf += ")";
                }
            } else {
                if(parseInt(jsonBetData[nRow].bet_type) == 0) {
                    strBuf += "<td  class = 'pb-home-table-betstate-wait'>누르기";
                    if(jsonBetData[nRow].bet_balance_no > 0 && jsonBetData[nRow].bet_auto_press > 0){
                        strBuf += " ("+ jsonBetData[nRow].bet_balance_no + ")";
                    }
                } else {
                    strBuf += "<td  class = 'pb-home-table-betstate-loss'>넘기기";
                    if(jsonBetData[nRow].bet_balance_no > 0){
                        strBuf += " ("+ jsonBetData[nRow].bet_balance_no; 
                        strBuf += ")";
                    }
                }
            }
            strBuf += "</td><td>";
            strBuf += getEvolSide(jsonBetData[nRow].bet_choice);
            strBuf += "</td><td>";
            strBuf += getEvolSide(jsonBetData[nRow].bet_result);
            strBuf += "</td><td>";
            if(type == 0){              //누르기
                strBuf += parseInt(jsonBetData[nRow].bet_win_money-jsonBetData[nRow].bet_win_balance).toLocaleString() + "원";
            } else if(type == 1){       //넘기기
                strBuf += parseInt(jsonBetData[nRow].bet_win_balance).toLocaleString() + "원";
            } else 
                strBuf += parseInt(jsonBetData[nRow].bet_win_money).toLocaleString() + "원";
            
            strBuf += "</td>";
            strResult = "<td>";
            if(state > 0) {
                if (parseInt(jsonBetData[nRow].point_amount) == 3) {
                    strResult = "<td  class = 'pb-home-table-betstate-earn'>적중";
                } else if (parseInt(jsonBetData[nRow].point_amount) == 4) {
                    strResult = "<td  class = 'pb-home-table-betstate-wait'>타이";
                } else  if (parseInt(jsonBetData[nRow].point_amount) == 2){
                    strResult = "<td  class = 'pb-home-table-betstate-loss'>미적중"; //
                } else {
                    if(parseInt(jsonBetData[nRow].company_amount) == 20)
                        strResult = "<td  class = 'pb-home-table-betstate-wait'>취소"; //
                    else {
                        strResult = "<td  class = 'pb-home-table-betstate-loss'>미처리"; //
                        if(mObjUser && mObjUser.mb_level >= LEVEL_ADMIN+2){
                            strResult += " (상태:" + jsonBetData[nRow].company_amount;
                            strResult += ")";
                        }
                    }
                }
            } else{
                if (parseInt(jsonBetData[nRow].bet_win_money) > parseInt(jsonBetData[nRow].bet_money)) {
                    strResult = "<td  class = 'pb-home-table-betstate-earn'>적중";
                } else if (jsonBetData[nRow].bet_win_money == jsonBetData[nRow].bet_money) {
                    strResult = "<td  class = 'pb-home-table-betstate-wait'>타이";
                } else {
                    strResult = "<td  class = 'pb-home-table-betstate-loss'>미적중"; //
                }
            }
            strBuf += strResult;
            strBuf += "</td><td>";
            if(state > 0) {
                strBuf += "<button data-fid='" + jsonBetData[nRow].bet_fid + "'>적중</button> ";
                strBuf += "<button data-fid='" + jsonBetData[nRow].bet_fid + "'>미적중</button> ";
                strBuf += "<button data-fid='" + jsonBetData[nRow].bet_fid + "'>타이</button> ";
            } else {
                if (jsonBetData[nRow].rw_point != null)
                    strBuf += Math.floor(jsonBetData[nRow].rw_point).toLocaleString();
                else strBuf += "0";
            }
            strBuf += "</td></tr>";

        }
    }
    if (strBuf.length < 1) {
        strBuf = "<tr><td colspan='15'>자료가 없습니다.</td></tr>";
    }
    elemBetDataTb.innerHTML = strBuf;

    if(state > 0)
        addBtnEvent();

}


function addBtnEvent() {
    /* Loop through all dropdown buttons to toggle between hiding and showing its dropdown content - This allows the user to have multiple dropdowns without any conflict */

    var elemTable = document.getElementById("pbbet-table-id");
    var elemTableBtns = elemTable.getElementsByTagName("button");
    if (elemTableBtns == null)
        return;

    var i;
    for (i = 0; i < elemTableBtns.length; i++) {
        addButtonElementListener(elemTableBtns[i]);
    }
}



function addButtonElementListener(buttonElement) {
    buttonElement.addEventListener("click", function() {
        let fid  = $(this).data('fid');

        let tHtml = this.innerHTML; 
        if (tHtml.search("미적중") >= 0) {
            var jsonData = {"game":mGameId, "data":[fid], "state":2};
            requestBetProcess(jsonData);
        } else  if (tHtml.search("적중") >= 0) {
            var jsonData = {"game":mGameId, "data":[fid], "state":3};
            requestBetProcess(jsonData);
        } else  if (tHtml.search("타이") >= 0) {
            var jsonData = {"game":mGameId, "data":[fid], "state":4};
            requestBetProcess(jsonData);
        }
    });
}

function getGameName(strGameType) {
    var strGameName = "";

    if (strGameType == "1") {
        strGameName = "바카라";
    } else if (strGameType == "2") {
        strGameName = "룰렛";
    } else if (strGameType == "3") {
        strGameName = "식보";
    } else if (strGameType == "4") {
        strGameName = "바카라보";
    } else if (strGameType == "5") {
        strGameName = "용호";
    } else if (strGameType == "6") {
        strGameName = "판탄";
    } else {
        strGameName = strGameType;
    }

    return strGameName;

}


function ShowBetAccount(arrBetAccount) {

    $("#total-betmoney-id").text("0");
    $("#total-winmoney-id").text("0");
    $("#total-lossmoney-id").text("0");
    $("#total-benefit-id").text("0");

    $(".pbresult-list-page-div p").css('display', 'none');

    if (arrBetAccount == null)
        return;
        
    if(arrBetAccount.length > 0){
        $(".pbresult-list-page-div p:first-child").css('display', 'block');
        $("#total-betmoney-id").text(parseInt(arrBetAccount[0]).toLocaleString() + " 원");
    }

    if(arrBetAccount.length > 1){
        $(".pbresult-list-page-div p:nth-child(2)").css('display', 'block');
        $("#total-winmoney-id").text(parseInt(arrBetAccount[1]).toLocaleString() + " 원");
    }

    if(arrBetAccount.length > 2){
        $(".pbresult-list-page-div p:nth-child(3)").css('display', 'block');
        $("#total-lossmoney-id").text(parseInt(arrBetAccount[2]).toLocaleString() + " 원");
    }

    if(arrBetAccount.length > 3){
        $(".pbresult-list-page-div p:nth-child(4)").css('display', 'block');
        $("#total-benefit-id").text(parseInt(arrBetAccount[3]).toLocaleString() + " 원");
    }
}


function addEventListner() {

    $("#pbhistory-list-view-but-id").click(function() {
        requestTotalPage();
    });

    $("#pbhistory-number-select-id").change(function() {
        requestTotalPage();
    });
    
    $("#pbhistory-state-select-id").change(function() {
        requestTotalPage();
    });
    
    $("#pbhistory-type-select-id").change(function() {
        requestTotalPage();
    });
}

//Function to Request Betting History to WebServer
function requestBetHistory(auto = false) {

    var dtStart = $("#pbhistory-datestart-input-id").val();
    var dtEnd = $("#pbhistory-dateend-input-id").val();
    CountPerPage = $("#pbhistory-number-select-id").val();
    var strUser = $("#pbhistory-userid-input-id").val();
    var strRoom = $("#pbhistory-room-input-id").val();
    var nPage = getActivePage();
    var strEmp = "";
    if ($("#pbhistory-empid-input-id").length > 0) {
        strEmp = $("#pbhistory-empid-input-id").val();
    }
    var state = 0;
    if ($("#pbhistory-state-select-id").length > 0) {
        state = $("#pbhistory-state-select-id").val();
    }
    var type = -1;
    if ($("#pbhistory-type-select-id").length > 0) {
        type = $("#pbhistory-type-select-id").val();
    }
    var jsonData = {
        "count": CountPerPage,
        "page": nPage,
        "start": dtStart,
        "end": dtEnd,
        "emp": strEmp,
        "user": strUser,
        "room": strRoom,
        "mode": -10,
        "state":state,
        "type":type,
        "game":mGameId,
        "auto":auto,
    };
    jsonData = JSON.stringify(jsonData);
    $(".loading").show();
    $.ajax({
        url: FURL + '/api/csbetlist',
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
    var strRoom = $("#pbhistory-room-input-id").val();
    var strEmp = "";
    if ($("#pbhistory-empid-input-id").length > 0) {
        strEmp = $("#pbhistory-empid-input-id").val();
    }
    var state = 0;
    if ($("#pbhistory-state-select-id").length > 0) {
        state = $("#pbhistory-state-select-id").val();
    }
    var type = -1;
    if ($("#pbhistory-type-select-id").length > 0) {
        type = $("#pbhistory-type-select-id").val();
    }
    var jsonData = {
        "count": CountPerPage,
        "start": dtStart,
        "end": dtEnd,
        "emp": strEmp,
        "user": strUser,
        "room": strRoom,
        "mode": -10,
        "state":state,
        "type":type,
        "game":mGameId,
    };
    jsonData = JSON.stringify(jsonData);

    $.ajax({
        url: FURL + '/api/csbetlistcnt',
        data: { json_: jsonData },
        dataType: 'json',
        type: 'post',
        success: function(jResult) {
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
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        }

    });
}

function requestBetProcess(jsData) {
    var jsonData = JSON.stringify(jsData);
    console.log(jsonData);
    $.ajax({
        type: "POST",
        dataType: "json",
        url: FURL + "/pbapi/csbetprocess",
        data: { json_: jsonData },
        success: function(jResult) {
            // console.log(jResult);
            if (jResult.status == "success") {
                requestTotalPage();
            } else if (jResult.status == "logout") {
                window.location.replace( FURL +'/');
            }
        },
        error: function(request, status, error) {
            // console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
        }

    });

}

function pbhitoryLoop() {

    requestBetHistory(true);

    // 1초뒤에 다시 실행
    setTimeout(function() {
        pbhitoryLoop();
    }, 120000);
}