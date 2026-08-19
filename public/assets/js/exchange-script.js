$(document).ready(function() {
    addEventListner();
    requestTotalPage();
});

function requestPageInfo() {
    requestMoneyHistory();
}

//Function to Show Betting History
function showMoneyHistory(jsonBetData) {
    var elemBetDataTb = document.getElementById("bank-exchange-table-id");
    var strBuf = "";

    var curPage = getActivePage();
    var firstIdx = (curPage - 1) * CountPerPage;

    for (nRow in jsonBetData) {

        strBuf += "<tr><td>";
        strBuf += (parseInt(nRow) + firstIdx + 1);
        strBuf += "</td><td>";
        if(mObjUser && mObjUser.mb_level >= LEVEL_ADMIN){
            strBuf += "<a onclick='popupMemberUid(\"" + jsonBetData[nRow].money_mb_uid + "\")' class='link-member'>"+ jsonBetData[nRow].money_mb_uid+ "</a>";
        } else 
            strBuf += jsonBetData[nRow].money_mb_uid;

        strBuf += "</td><td>";
        strBuf += Math.floor(jsonBetData[nRow].money_amount).toLocaleString();
        strBuf += "</td><td>";
        strBuf += Math.floor(jsonBetData[nRow].money_before).toLocaleString();
        strBuf += "</td><td>";
        strBuf += Math.floor(jsonBetData[nRow].money_after).toLocaleString();
        strBuf += "</td><td>";
        strBuf += jsonBetData[nRow].money_update_time;
        strBuf += "</td><td>";
        switch (parseInt(jsonBetData[nRow].money_change_type)) {
            case 1 : strBuf += "충전"; break;
            case 2: 
                if(parseInt(jsonBetData[nRow].money_bet_mode) == 1)
                    strBuf += "환전"; 
                else strBuf += "환전요청"; 
                break;
            case 3: strBuf += "포인트 전환"; break;
            case 4: strBuf += "PBG 배팅"; break;
            case 5: strBuf += "PBG 배팅취소"; break;
            case 6: strBuf += "PBG 정산"; break;
            case 7: strBuf += "동행파워볼 배팅"; break;
            case 8: strBuf += "동행파워볼 배팅취소"; break;
            case 9: strBuf += "동행파워볼 정산"; break;
            case 10: strBuf += "스피드키노 배팅"; break;
            case 11: strBuf += "스피드키노 배팅취소"; break;
            case 12: strBuf += "스피드키노 정산"; break;
            case 13: strBuf += "보글볼 배팅"; break;
            case 14: strBuf += "보글볼 배팅취소"; break;
            case 15: strBuf += "보글볼 정산"; break;
            case 16: strBuf += "보사달 배팅"; break;
            case 17: strBuf += "보사달 배팅취소"; break;
            case 18: strBuf += "보사달 정산"; break;
            case 19: strBuf += "하부이동"; break;
            case 20: strBuf += "상부이동"; break;
            case 21: strBuf += "충전취소"; break;
            case 22: strBuf += "환전취소"; break;
            case 23: strBuf += "직충전"; break;
            case 24: strBuf += "머니회수"; break;
            case 25: strBuf += "포인트회수"; break;
            case 26: strBuf += "직환전"; break;
            case 27: strBuf += "하부환수"; break;
            case 28: strBuf += "상부환수"; break;
            case 29: strBuf += "머니지급"; break;
            case 30: strBuf += "머니전환"; break;
            case 31: strBuf += "EOS5분파워볼 배팅"; break;
            case 32: strBuf += "EOS5분파워볼 배팅취소"; break;
            case 33: strBuf += "EOS5분파워볼 정산"; break;
            case 34: strBuf += "EOS3분파워볼 배팅"; break;
            case 35: strBuf += "EOS3분파워볼 배팅취소"; break;
            case 36: strBuf += "EOS3분파워볼 정산"; break;
            case 37: strBuf += "코인5분파워볼 배팅"; break;
            case 38: strBuf += "코인5분파워볼 배팅취소"; break;
            case 39: strBuf += "코인5분파워볼 정산"; break;
            case 40: strBuf += "코인3분파워볼 배팅"; break;
            case 41: strBuf += "코인3분파워볼 배팅취소"; break;
            case 42: strBuf += "코인3분파워볼 정산"; break;
            case 43: strBuf += "에볼 배팅"; break;
            case 44: strBuf += "에볼 적특"; break;
            case 45: strBuf += "에볼 정산"; break;
            case 46: strBuf += "프라그 배팅"; break;
            case 47: strBuf += "프라그 적특"; break;
            case 48: strBuf += "프라그 정산"; break;
            default:break;
        } 
        strBuf += "</td><td>";
        if(jsonBetData[nRow].money_change_type == 43 || jsonBetData[nRow].money_change_type == 44 ||
            jsonBetData[nRow].money_change_type == 45 ){
            if(mObjUser && mObjUser.mb_level > LEVEL_ADMIN){
                let info = jsonBetData[nRow].money_bet_target.split(':');
                if(info.length > 0){
                    strBuf += info[0] ;
                }
            }
        } else if (jsonBetData[nRow].money_change_type == 19 || jsonBetData[nRow].money_change_type == 20 ||
            jsonBetData[nRow].money_change_type == 27 || jsonBetData[nRow].money_change_type == 28) {
            if(mObjUser && mObjUser.mb_level >= LEVEL_ADMIN){
                strBuf += "<a onclick='popupMemberUid(\"" + jsonBetData[nRow].money_bet_target + "\")' class='link-member'>"+ jsonBetData[nRow].money_bet_target+ "</a>";
            } else 
                strBuf += jsonBetData[nRow].money_bet_target;
        } 
        strBuf += "</td></tr>";

    }

    if (strBuf.length < 1) {
        strBuf = "<tr><td colspan='10'>자료가 없습니다.</td></tr>";
    }
    elemBetDataTb.innerHTML = strBuf;

}



function addEventListner() {
    $("#exchange-list-view-but-id").click(function() {
        requestTotalPage();
    });

    $("#exchange-number-select-id").change(function() {
        requestTotalPage();
    });
}

//Function to Request Betting History to WebServer
function requestMoneyHistory() {

    var dtStart = $("#exchange-datestart-input-id").val();
    var dtEnd = $("#exchange-dateend-input-id").val();
    var strUser = $("#exchange-userid-input-id").val();
    var nMode = $("#exchange-game-select-id").val();
    var nPage = getActivePage();

    var jsonData = { "count": CountPerPage, "page": nPage, "start": dtStart, "end": dtEnd, "user": strUser, "mode": nMode };
    jsonData = JSON.stringify(jsonData);
    $(".loading").show();
    $.ajax({
        url: FURL + '/api/moneyhistory',
        data: { json_: jsonData },
        type: 'post',
        dataType: "json",
        success: function(jResult) {
            $(".loading").hide();
            // console.log(jResult);
            if (jResult.status == "success") {
                showMoneyHistory(jResult.data);
            }
        },
        error: function(request, status, error) {
            $(".loading").hide();
            // console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
        }

    });
}


function requestTotalPage() {


    var dtStart = $("#exchange-datestart-input-id").val();
    var dtEnd = $("#exchange-dateend-input-id").val();
    CountPerPage = $("#exchange-number-select-id").val();
    var strUser = $("#exchange-userid-input-id").val();
    var nMode = $("#exchange-game-select-id").val();

    var jsonData = { "count": CountPerPage, "start": dtStart, "end": dtEnd, "user": strUser, "mode": nMode };
    jsonData = JSON.stringify(jsonData);


    $.ajax({
        url: FURL + '/api/moneyhistorycnt',
        data: { json_: jsonData },
        dataType: 'json',
        type: 'post',
        success: function(jResult) {
            // console.log(jResult);
            if (jResult.status == "success") {
                TotalCount = jResult.data.count;
                setFirstPage();
                requestMoneyHistory();
            }
        },
        error: function(request, status, error) {
            // console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
        }

    });
}


// function exchangeLoop() {

//     requestMoneyHistory();

//     setTimeout(function() {
//         exchangeLoop();
//     }, 300000);

// }