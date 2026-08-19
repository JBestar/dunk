$(document).ready(function() {
    setNavBarElement();
    requestConfPowerball();
    addBtnEvent();
});


function showConfPowerball(objConfig) {

    if (objConfig.game_bet_permit == 1)
        $("#confpb-bet-check-id").prop('checked', true);
    else $("#confpb-bet-check-id").prop('checked', false);

    $("#confpb-endsec-input-id").val(objConfig.game_time_countdown);
    $("#confpb-minmoney-input-id").val(objConfig.game_min_bet_money);
    $("#confpb-maxmoney-input-id").val(objConfig.game_max_bet_money);
    $("#confpb-winmoney-input-id").val(objConfig.game_max_win_money);
    $("#confpb-percent-input-id").val(objConfig.game_percent_1);
    $("#confpb-min2money-input-id").val(objConfig.game_min2_bet_money);
    $("#confpb-max2money-input-id").val(objConfig.game_max2_bet_money);
    $("#confpb-win2money-input-id").val(objConfig.game_max2_win_money);
    $("#confpb-percent2-input-id").val(objConfig.game_percent_2);
    $("#confpb-min3money-input-id").val(objConfig.game_min3_bet_money);
    $("#confpb-max3money-input-id").val(objConfig.game_max3_bet_money);
    $("#confpb-win3money-input-id").val(objConfig.game_max3_win_money);
    $("#confpb-percent3-input-id").val(objConfig.game_percent_3);
    $("#confpb-min4money-input-id").val(objConfig.game_min4_bet_money);
    $("#confpb-max4money-input-id").val(objConfig.game_max4_bet_money);
    $("#confpb-win4money-input-id").val(objConfig.game_max4_win_money);
    $("#confpb-percent4-input-id").val(objConfig.game_percent_4);
    $("#confpb-min5money-input-id").val(objConfig.game_min5_bet_money);
    $("#confpb-max5money-input-id").val(objConfig.game_max5_bet_money);
    $("#confpb-win5money-input-id").val(objConfig.game_max5_win_money);
    $("#confpb-percent5-input-id").val(objConfig.game_percent_5);
    $("#confpb-min6money-input-id").val(objConfig.game_min6_bet_money);
    $("#confpb-max6money-input-id").val(objConfig.game_max6_bet_money);
    $("#confpb-win6money-input-id").val(objConfig.game_max6_win_money);
    $("#confpb-percent6-input-id").val(objConfig.game_percent_6);
    $("#confpb-min7money-input-id").val(objConfig.game_min7_bet_money);
    $("#confpb-max7money-input-id").val(objConfig.game_max7_bet_money);
    $("#confpb-win7money-input-id").val(objConfig.game_max7_win_money);
    $("#confpb-percent7-input-id").val(objConfig.game_percent_7);
    $("#confpb-ratio1-input-id").val(objConfig.game_ratio_1);
    $("#confpb-ratio2-input-id").val(objConfig.game_ratio_2);
    $("#confpb-ratio3-input-id").val(objConfig.game_ratio_3);
    $("#confpb-ratio4-input-id").val(objConfig.game_ratio_4);
    $("#confpb-ratio5-input-id").val(objConfig.game_ratio_5);
    $("#confpb-ratio6-input-id").val(objConfig.game_ratio_6);
    $("#confpb-ratio7-input-id").val(objConfig.game_ratio_7);
    $("#confpb-ratio8-input-id").val(objConfig.game_ratio_8);
    $("#confpb-ratio9-input-id").val(objConfig.game_ratio_9);
    $("#confpb-ratio10-input-id").val(objConfig.game_ratio_10);
    $("#confpb-ratio11-input-id").val(objConfig.game_ratio_11);
    $("#confpb-ratio12-input-id").val(objConfig.game_ratio_12);
    $("#confpb-ratio13-input-id").val(objConfig.game_ratio_13);
    $("#confpb-ratio14-input-id").val(objConfig.game_ratio_14);
    $("#confpb-ratio15-input-id").val(objConfig.game_ratio_15);
    $("#confpb-ratio16-input-id").val(objConfig.game_ratio_16);
    $("#confpb-ratio17-input-id").val(objConfig.game_ratio_17);
    $("#confpb-ratio18-input-id").val(objConfig.game_ratio_18);
    $("#confpb-ratio19-input-id").val(objConfig.game_ratio_19);
    $("#confpb-ratio20-input-id").val(objConfig.game_ratio_20);
    $("#confpb-ratio21-input-id").val(objConfig.game_ratio_21);
    $("#confpb-ratio22-input-id").val(objConfig.game_ratio_22);
    $("#confpb-ratio23-input-id").val(objConfig.game_ratio_23);
    $("#confpb-ratio24-input-id").val(objConfig.game_ratio_24);
    $("#confpb-ratio25-input-id").val(objConfig.game_ratio_25);
    $("#confpb-ratio26-input-id").val(objConfig.game_ratio_26);
    $("#confpb-ratio27-input-id").val(objConfig.game_ratio_27);
    $("#confpb-ratio28-input-id").val(objConfig.game_ratio_28);
    $("#confpb-ratio29-input-id").val(objConfig.game_ratio_29);
    $("#confpb-ratio30-input-id").val(objConfig.game_ratio_30);
    $("#confpb-ratio31-input-id").val(objConfig.game_ratio_31);

    


}

function requestConfPowerball() {

    let gameId = $(".confsite-game-panel").attr('id');
    var jsonData = { "game_index": gameId };
    jsonData = JSON.stringify(jsonData);

    $.ajax({
        type: "POST",
        dataType: "json",
        url: FURL + "/api/conf_game",
        data: { json_: jsonData },
        success: function(jResult) {
            //console.log(jResult);
            if (jResult.status == "success") {
                showConfPowerball(jResult.data);
            } else if (jResult.status == "fail") {

            }
        },
        error: function(request, status, error) {
            //console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
        }

    });

}

function readConfigToObject() {

    var jsonData = new Object();
    jsonData.game_index = $(".confsite-game-panel").attr('id');
    jsonData.game_bet_permit = $("#confpb-bet-check-id").prop('checked') ? 1 : 0;
    jsonData.game_time_countdown = $("#confpb-endsec-input-id").val();
    jsonData.game_time_delay = 0;
    jsonData.game_min_bet_money = $("#confpb-minmoney-input-id").val();
    jsonData.game_max_bet_money = $("#confpb-maxmoney-input-id").val();
    jsonData.game_max_win_money = $("#confpb-winmoney-input-id").val();
    jsonData.game_min2_bet_money = $("#confpb-min2money-input-id").val();
    jsonData.game_max2_bet_money = $("#confpb-max2money-input-id").val();
    jsonData.game_max2_win_money = $("#confpb-win2money-input-id").val();
    jsonData.game_min3_bet_money = $("#confpb-min3money-input-id").val();
    jsonData.game_max3_bet_money = $("#confpb-max3money-input-id").val();
    jsonData.game_max3_win_money = $("#confpb-win3money-input-id").val();
    if($("#confpb-min4money-input-id").length > 0){
        jsonData.game_min4_bet_money = $("#confpb-min4money-input-id").val();
        jsonData.game_max4_bet_money = $("#confpb-max4money-input-id").val();
        jsonData.game_max4_win_money = $("#confpb-win4money-input-id").val();
    }
    if($("#confpb-min5money-input-id").length > 0){
        jsonData.game_min5_bet_money = $("#confpb-min5money-input-id").val();
        jsonData.game_max5_bet_money = $("#confpb-max5money-input-id").val();
        jsonData.game_max5_win_money = $("#confpb-win5money-input-id").val();
    }
    if($("#confpb-min6money-input-id").length > 0){
        jsonData.game_min6_bet_money = $("#confpb-min6money-input-id").val();
        jsonData.game_max6_bet_money = $("#confpb-max6money-input-id").val();
        jsonData.game_max6_win_money = $("#confpb-win6money-input-id").val();
    }
    if($("#confpb-min7money-input-id").length > 0){
        jsonData.game_min7_bet_money = $("#confpb-min7money-input-id").val();
        jsonData.game_max7_bet_money = $("#confpb-max7money-input-id").val();
        jsonData.game_max7_win_money = $("#confpb-win7money-input-id").val();
    }
    if($("#confpb-percent-input-id").length > 0)
        jsonData.game_percent_1 = $("#confpb-percent-input-id").val();
    if($("#confpb-percent2-input-id").length > 0)
        jsonData.game_percent_2 = $("#confpb-percent2-input-id").val();
    if($("#confpb-percent3-input-id").length > 0)
        jsonData.game_percent_3 = $("#confpb-percent3-input-id").val();
    if($("#confpb-percent4-input-id").length > 0)
        jsonData.game_percent_4 = $("#confpb-percent4-input-id").val();
    if($("#confpb-percent5-input-id").length > 0)
        jsonData.game_percent_5 = $("#confpb-percent5-input-id").val();
    if($("#confpb-percent6-input-id").length > 0)
        jsonData.game_percent_6 = $("#confpb-percent6-input-id").val();
    if($("#confpb-percent7-input-id").length > 0)
        jsonData.game_percent_7 = $("#confpb-percent7-input-id").val();
        
    jsonData.game_ratio_1 = $("#confpb-ratio1-input-id").val();
    jsonData.game_ratio_2 = $("#confpb-ratio2-input-id").val();
    jsonData.game_ratio_3 = $("#confpb-ratio3-input-id").val();
    jsonData.game_ratio_4 = $("#confpb-ratio4-input-id").val();
    jsonData.game_ratio_5 = $("#confpb-ratio5-input-id").val();
    jsonData.game_ratio_6 = $("#confpb-ratio6-input-id").val();
    jsonData.game_ratio_7 = $("#confpb-ratio7-input-id").val();
    jsonData.game_ratio_8 = $("#confpb-ratio8-input-id").val();
    jsonData.game_ratio_9 = $("#confpb-ratio9-input-id").val();
    jsonData.game_ratio_10 = $("#confpb-ratio10-input-id").val();
    jsonData.game_ratio_11 = $("#confpb-ratio11-input-id").val();
    jsonData.game_ratio_12 = $("#confpb-ratio12-input-id").val();
    
    if($("#confpb-ratio13-input-id").length > 0){
        jsonData.game_ratio_13 = $("#confpb-ratio13-input-id").val();
        jsonData.game_ratio_14 = $("#confpb-ratio14-input-id").val();
        jsonData.game_ratio_15 = $("#confpb-ratio15-input-id").val();
        jsonData.game_ratio_16 = $("#confpb-ratio16-input-id").val();
        jsonData.game_ratio_17 = $("#confpb-ratio17-input-id").val();
        jsonData.game_ratio_18 = $("#confpb-ratio18-input-id").val();
        jsonData.game_ratio_19 = $("#confpb-ratio19-input-id").val();
        jsonData.game_ratio_20 = $("#confpb-ratio20-input-id").val();
    }
    if($("#confpb-ratio21-input-id").length > 0){
        jsonData.game_ratio_21 = $("#confpb-ratio21-input-id").val();
        jsonData.game_ratio_22 = $("#confpb-ratio22-input-id").val();
        jsonData.game_ratio_23 = $("#confpb-ratio23-input-id").val();
        jsonData.game_ratio_24 = $("#confpb-ratio24-input-id").val();
        jsonData.game_ratio_25 = $("#confpb-ratio25-input-id").val();
        jsonData.game_ratio_26 = $("#confpb-ratio26-input-id").val();
    }    
    if($("#confpb-ratio21-input-id").length > 0){
        jsonData.game_ratio_27 = $("#confpb-ratio27-input-id").val();
        jsonData.game_ratio_28 = $("#confpb-ratio28-input-id").val();
        jsonData.game_ratio_29 = $("#confpb-ratio29-input-id").val();
    }
    if($("#confpb-ratio30-input-id").length > 0){
        jsonData.game_ratio_30 = $("#confpb-ratio30-input-id").val();
    }
    if($("#confpb-ratio31-input-id").length > 0){
        jsonData.game_ratio_31 = $("#confpb-ratio31-input-id").val();
    }

    return jsonData;

}

function addBtnEvent() {

    $('#confsite-ok-btn-id').on('click', function() {
        if (!confirm("저장하시겠습니까?"))
            return;

        var jsonData = readConfigToObject();
        jsonData = JSON.stringify(jsonData);

        $.ajax({
            type: "POST",
            dataType: "json",
            url: FURL + "/api/saveconfgame",
            data: { json_: jsonData },
            success: function(jResult) {
                if (jResult.status == "success") {
                    location.reload();
                } else if (jResult.status == "logout") {
                    window.location.replace( FURL +'/');
                } else if (jResult.status == "fail") {
                    showAlert("저장이 실패되었습니다.", 0);
                } else if (jResult.status == "nopermit") {
                    showAlert("권한이 없습니다.", 0);
                }
            },
            error: function(request, status, error) {
                //console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
            }

        });
    });

    $('#confsite-cancel-btn-id').on('click', function() {
        location.reload();
    });

}