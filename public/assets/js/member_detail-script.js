$(document).ready(function() {
    addBtnEvent();
    reqUserDetail();
});

function reqUserDetail(){
    reqUserStatist();
    setTimeout(function() { reqChargeLog(); }, 500);
    setTimeout(function() { reqDischargeLog(); }, 1000);
    setTimeout(function() { reqIpLog(); }, 1500);
    setTimeout(function() { reqBetLog(); }, 2000);

}

function getToday(){
    let today = new Date();
    let year = today.getFullYear();     // 년도
    let month = today.getMonth() + 1;   // 월
    let date = today.getDate();         // 날짜
    return year + '-' + month + '-' + date;
}

function addBtnEvent() {

    $("#info-panel .glyphicon-chevron-up").click(function() {
        if($('#info-panel .panel-body').css('display') === 'none')
            $('#info-panel .panel-body').slideDown(200);
        else 
            $('#info-panel .panel-body').slideUp(100);
    });

    $("#info-panel .glyphicon-remove").click(function() {
        $('#info-panel').slideUp(100);
    });

    $("#bet-panel .glyphicon-chevron-up").click(function() {
        if($('#bet-panel .panel-body').css('display') === 'none')
            $('#bet-panel .panel-body').slideDown(200);
        else 
            $('#bet-panel .panel-body').slideUp(100);
    });

    $("#bet-panel .glyphicon-remove").click(function() {
        $('#bet-panel').slideUp(100);
    });

    
    $("#charge-panel .glyphicon-chevron-up").click(function() {
        if($('#charge-panel .panel-body').css('display') === 'none')
            $('#charge-panel .panel-body').slideDown(200);
        else 
            $('#charge-panel .panel-body').slideUp(100);
    });

    $("#charge-panel .glyphicon-remove").click(function() {
        $('#charge-panel').slideUp(100);
    });

    $("#discharge-panel .glyphicon-chevron-up").click(function() {
        if($('#discharge-panel .panel-body').css('display') === 'none')
            $('#discharge-panel .panel-body').slideDown(200);
        else 
            $('#discharge-panel .panel-body').slideUp(100);
    });

    $("#discharge-panel .glyphicon-remove").click(function() {
        $('#discharge-panel').slideUp(100);
    });
    
    $("#iplog-panel .glyphicon-chevron-up").click(function() {
        if($('#iplog-panel .panel-body').css('display') === 'none')
            $('#iplog-panel .panel-body').slideDown(200);
        else 
            $('#iplog-panel .panel-body').slideUp(100);
    });

    $("#iplog-panel .glyphicon-remove").click(function() {
        $('#iplog-panel').slideUp(100);
    });
}

function reqUserStatist(){
    var strUid = $("#user_uid").text();
    var jsonData = { "mb_uid": strUid };
    jsonData = JSON.stringify(jsonData);

    $.ajax({
        type: "POST",
        data: { json_: jsonData },
        dataType: "json",
        url: FURL + "/userapi/userinfo",
        success: function(jResult) {
            // console.log(jResult);
            if (jResult.status == "success") {
                showStatist(jResult.data);
            } else if (jResult.status == "fail") {

            }
        },
        error: function(request, status, error) {
            // console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
        }

    });
}

function reqChargeLog(){
    var strUid = $("#user_uid").text();
    var jsonData = { "count": 100, "page": 1, "mb_uid": strUid, "start": getToday(), "end": getToday() };
    jsonData = JSON.stringify(jsonData);

    $.ajax({
        type: "POST",
        data: { json_: jsonData },
        dataType: "json",
        url: FURL + "/api/depositlist",
        success: function(jResult) {
            //console.log(jResult);
            if (jResult.status == "success") {
                showChargeLog(jResult.data);

            } else if (jResult.status == "fail") {

            }
        },
        error: function(request, status, error) {
            // console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
        }

    });

}

function reqDischargeLog(){
    var strUid = $("#user_uid").text();
    
    var jsonData = { "count": 100, "page": 1, "mb_uid": strUid, "start": getToday(), "end": getToday() };
    jsonData = JSON.stringify(jsonData);

    $.ajax({
        type: "POST",
        data: { json_: jsonData },
        dataType: "json",
        url: FURL + "/api/withdrawlist",
        success: function(jResult) {
            // console.log(jResult);
            if (jResult.status == "success") {
                showDischargeLog(jResult.data);
            } else if (jResult.status == "fail") {

            }
        },
        error: function(request, status, error) {
            //console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
        }

    });
}

function reqIpLog(){
    var strUid = $("#user_uid").text();
    var jsonData = {
        "count": 100,
        "page": 1,
        "type": 0,
        "search": strUid,
        "start": getToday(),
        "end": getToday()
    };

    jsonData = JSON.stringify(jsonData);

    $.ajax({
        type: "POST",
        dataType: "json",
        url: FURL + "/userapi/loglist",
        data: { json_: jsonData },
        success: function(jResult) {
            // console.log(jResult);
            if (jResult.status == "success") {
                showIpLog(jResult.data);
            } else if (jResult.status == "fail") {

            }
        },
        error: function(request, status, error) {
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        }

    });
}

function reqBetLog(){
    var strUid = $("#user_uid").text();
    var jsonData = {
        "mb_uid": strUid,
    };

    jsonData = JSON.stringify(jsonData);

    $.ajax({
        type: "POST",
        dataType: "json",
        url: FURL + "/userapi/userbet",
        data: { json_: jsonData },
        success: function(jResult) {
            // console.log(jResult);
            if (jResult.status == "success") {
                showBetLog(jResult.data, jResult.date);
            } else if (jResult.status == "fail") {

            }
        },
        error: function(request, status, error) {
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        }

    });
}

function showStatist(data){
    $("#total_charge_money").text(parseInt(data.charge_total).toLocaleString());
    $("#total_discharge_money").text(parseInt(data.discharge_total).toLocaleString());
    $("#today_charge_money").text(parseInt(data.charge_today).toLocaleString());
    $("#today_discharge_money").text(parseInt(data.discharge_today).toLocaleString());
    $("#total_bet_money").text(parseInt(data.bet_total).toLocaleString());
    $("#total_result_money").text(parseInt(data.win_total).toLocaleString());
    $("#today_bet_money").text(parseInt(data.bet_today).toLocaleString());
    $("#today_result_money").text(parseInt(data.win_today).toLocaleString());

}

function showChargeLog(arrData){
    let tHtml = "";
    let idx = 0;
    for (nRow in arrData) {
        if(arrData[nRow].charge_action_state != 2 && arrData[nRow].charge_action_state != 5)
            continue;
        idx ++;
        tHtml += "<tr><td>";
        tHtml += idx;
        tHtml += "</td><td>";
        tHtml += parseInt(arrData[nRow].charge_money).toLocaleString();
        tHtml += "</td><td>";
        if (arrData[nRow].charge_action_state == 2)
            tHtml += "신청충전";
        else if (arrData[nRow].charge_action_state == 5)
            tHtml += "직충전";
        tHtml += "</td><td>";
        tHtml += arrData[nRow].charge_time_require;
        tHtml += "</td></tr>";
    }
    if(tHtml.length < 1){
        tHtml = '<tr><td colspan="50" class="empty_table"> 내역이 없습니다</td></tr>';
    }

    $("#charge_table").html(tHtml);
}

function showDischargeLog(arrData){
    let tHtml = "";
    let idx = 0;
    for (nRow in arrData) {
        if(arrData[nRow].exchange_action_state != 2 && arrData[nRow].exchange_action_state != 5)
            continue;
        idx ++;
        tHtml += "<tr><td>";
        tHtml += idx;
        tHtml += "</td><td>";
        tHtml += parseInt(arrData[nRow].exchange_money).toLocaleString();
        tHtml += "</td><td>";
        if (arrData[nRow].exchange_action_state == 2)
            tHtml += "신청환전";
        else if (arrData[nRow].exchange_action_state == 5)
            tHtml += "직환전";
        tHtml += "</td><td>";
        tHtml += arrData[nRow].exchange_time_require;
        tHtml += "</td></tr>";
    }
    if(tHtml.length < 1){
        tHtml = '<tr><td colspan="50" class="empty_table"> 내역이 없습니다</td></tr>';
    }

    $("#discharge_table").html(tHtml);
}

function showIpLog(arrData){
    let tHtml = "";
    for (nRow in arrData) {
        tHtml += "<tr><td>";
        tHtml += (parseInt(nRow) + 1);
        tHtml += "</td><td>";
        tHtml += arrData[nRow].log_time;
        tHtml += "</td><td>";
        tHtml += arrData[nRow].log_ip;
        tHtml += "</td></tr>";
    }
    if(tHtml.length < 1){
        tHtml = '<tr><td colspan="50" class="empty_table"> 내역이 없습니다</td></tr>';
    }

    $("#login_history_table").html(tHtml);
}


function showBetLog(arrData, date){
    let tHtml = "";
    let num = 1;
    for (nRow in arrData) {
        if(arrData[nRow].bet_count < 1)
            continue;
        tHtml += "<tr><td>";
        tHtml += (num++);
        tHtml += "</td><td>";
        tHtml += date;
        tHtml += "</td><td>";
        tHtml += arrData[nRow].bet_name;
        if(arrData[nRow].bet_kind == 4)
            tHtml += " (카지노)";
        else if(arrData[nRow].bet_kind == 7)
            tHtml += " (슬롯)";
        tHtml += "</td><td>";
        tHtml += arrData[nRow].bet_count;
        tHtml += "</td><td>";
        tHtml += parseInt(arrData[nRow].bet_money).toLocaleString();
        tHtml += "</td><td>";
        tHtml += parseInt(arrData[nRow].bet_win_money).toLocaleString();
        tHtml += "</td></tr>";
    }
    if(tHtml.length < 1){
        tHtml = '<tr><td colspan="50" class="empty_table"> 내역이 없습니다</td></tr>';
    }

    $("#game_hist_table").html(tHtml);
}