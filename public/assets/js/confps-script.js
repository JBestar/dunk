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
    $("#confpb-delaysec-input-id").val(objConfig.game_time_delay);
    $("#confpb-minmoney-input-id").val(objConfig.game_min_bet_money);
    $("#confpb-maxmoney-input-id").val(objConfig.game_max_bet_money);
    $("#confpb-winmoney-input-id").val(objConfig.game_max_win_money);
    $("#confpb-percent-input-id").val(objConfig.game_percent_1);
    $("#confpb-min2money-input-id").val(objConfig.game_min2_bet_money);
    $("#confpb-max2money-input-id").val(objConfig.game_max2_bet_money);
    $("#confpb-win2money-input-id").val(objConfig.game_max2_win_money);
    $("#confpb-percent2-input-id").val(objConfig.game_percent_2);
    $("#confpb-ratio1-input-id").val(objConfig.game_ratio_1);
    $("#confpb-ratio2-input-id").val(objConfig.game_ratio_2);
    $("#confpb-ratio3-input-id").val(objConfig.game_ratio_3);
    $("#confpb-ratio4-input-id").val(objConfig.game_ratio_4);
    $("#confpb-ratio5-input-id").val(objConfig.game_ratio_5);
    $("#confpb-ratio6-input-id").val(objConfig.game_ratio_6);
    $("#confpb-ratio7-input-id").val(objConfig.game_ratio_7);

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
    jsonData.game_index = $(".confsite-game-panel").attr('id');;
    jsonData.game_bet_permit = $("#confpb-bet-check-id").prop('checked') ? 1 : 0;
    jsonData.game_time_countdown = $("#confpb-endsec-input-id").val();
    jsonData.game_time_delay = $("#confpb-delaysec-input-id").val();
    jsonData.game_min_bet_money = $("#confpb-minmoney-input-id").val();
    jsonData.game_max_bet_money = $("#confpb-maxmoney-input-id").val();
    jsonData.game_max_win_money = $("#confpb-winmoney-input-id").val();
    jsonData.game_min2_bet_money = $("#confpb-min2money-input-id").val();
    jsonData.game_max2_bet_money = $("#confpb-max2money-input-id").val();
    jsonData.game_max2_win_money = $("#confpb-win2money-input-id").val();
    if($("#confpb-percent-input-id").length > 0)
        jsonData.game_percent_1 = $("#confpb-percent-input-id").val();
    if($("#confpb-percent2-input-id").length > 0)
        jsonData.game_percent_2 = $("#confpb-percent2-input-id").val();
    jsonData.game_ratio_1 = $("#confpb-ratio1-input-id").val();
    jsonData.game_ratio_2 = $("#confpb-ratio2-input-id").val();
    jsonData.game_ratio_3 = $("#confpb-ratio3-input-id").val();
    jsonData.game_ratio_4 = $("#confpb-ratio4-input-id").val();
    jsonData.game_ratio_5 = $("#confpb-ratio5-input-id").val();
    jsonData.game_ratio_6 = $("#confpb-ratio6-input-id").val();
    jsonData.game_ratio_7 = $("#confpb-ratio7-input-id").val();

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