$(document).ready(function() {
    setNavBarElement();
    requestConfGame();
    addBtnEvent();
    // onChangeElement();
});

function showConfGame(objConfig, objAgent) {

//    console.log($("#confpb-agent-token-id").length);
    if (objConfig.game_bet_permit == 1)
        $("#confpb-bet-check-id").prop('checked', true);
    else $("#confpb-bet-check-id").prop('checked', false);

    if($("#conf-account-check-id").length > 0){
        $("#conf-account-check-id").prop('checked', objConfig.game_percent_1 == 1);
        $("#conf-accwin-check-id").prop('checked', objConfig.game_percent_2 == 1);
        $("#conf-accpl-check-id").prop('checked', objConfig.game_percent_3 == 1);
        onChangeElement();
    }

    if (objAgent != null) {
//        console.log(objAgent);
        $("#confpb-agent-code-id").val(objAgent.code);
        $("#confpb-agent-token-id").val(objAgent.token);
        $("#confpb-agent-egg-id").val(parseInt(objAgent.egg).toLocaleString());
        
        if(objAgent.useregg != null)
            $("#confpb-user-egg-id").val(parseInt(objAgent.useregg).toLocaleString());
        else $("#confpb-user-egg-id").val(0);
    }
}

function onChangeElement(){

    if($("#conf-account-check-id").length > 0){
        acc_checked = $("#conf-account-check-id").prop('checked');
        accwin_checked = $("#conf-accwin-check-id").prop('checked');
 
        $("#conf-accwin-check-id").prop('disabled', !acc_checked);
        $("#conf-accpl-check-id").prop('disabled', !(acc_checked && accwin_checked));

    }    
}

function requestConfGame() {

    let gameId = $(".confsite-game-panel").attr('id');
    var jsonData = { "game_index": gameId };
    jsonData = JSON.stringify(jsonData);

    $.ajax({
        type: "POST",
        dataType: "json",
        url: FURL + "/api/conf_game",
        data: { json_: jsonData },
        success: function(jResult) {
            $("#refresh_egg").removeClass("refresh");
            $("#refresh_useregg").removeClass("refresh");
            // console.log(jResult);
            if (jResult.status == "success") {
                showConfGame(jResult.data, jResult.agent);
                if(jResult.msg ){
                    $("#err_msg").text(jResult.msg);
                }
            } else if (jResult.status == "fail") {

            }
        },
        error: function(request, status, error) {
            $("#refresh_egg").removeClass("refresh");
            $("#refresh_useregg").removeClass("refresh");
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        }

    });

}

function requestRecoveryEgg() {
    $("#recovery_useregg").addClass("refresh");

    let gameId = $(".confsite-game-panel").attr('id');
    var jsonData = { "game_index": gameId };
    jsonData = JSON.stringify(jsonData);

    $.ajax({
        type: "POST",
        dataType: "json",
        url: FURL + "/userapi/eggrecovery",
        data: { json_: jsonData },
        success: function(jResult) {
            $("#recovery_useregg").removeClass("refresh");

            // console.log(jResult);
            if (jResult.status == "success") {
                $("#confpb-agent-egg-id").val(parseInt(jResult.egg).toLocaleString());
                $("#confpb-user-egg-id").val(parseInt(jResult.useregg).toLocaleString());

            } else if (jResult.status == "fail") {

            }
        },
        error: function(request, status, error) {
            $("#recovery_useregg").removeClass("refresh");
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        }

    });

}

function readConfigToObject() {
    var jsonData = new Object();
    jsonData.game_index = $(".confsite-game-panel").attr('id');;
    jsonData.game_bet_permit = $("#confpb-bet-check-id").prop('checked') ? 1 : 0;
    if($("#conf-account-check-id").length > 0){
        jsonData.game_percent_1 = $("#conf-account-check-id").prop('checked') ? 1 : 0;
        jsonData.game_percent_2 = $("#conf-accwin-check-id").prop('checked') ? 1 : 0;
        jsonData.game_percent_3 = $("#conf-accpl-check-id").prop('checked') ? 1 : 0;
    }
    return jsonData;

}

function addBtnEvent() {

    $('#confsite-ok-btn-id').on('click', function() {

        if (!confirm("저장하시겠습니까?"))
            return;

        var jsonData = readConfigToObject();
        jsonData = JSON.stringify(jsonData);
//        console.log(jsonData);
        $.ajax({
            type: "POST",
            dataType: "json",
            url: FURL + "/api/saveconfgame",
            data: { json_: jsonData },
            success: function(jResult) {
                if (jResult.status == "success") {
                    showAlert("성공적으로 저장되었습니다.");
                    // location.reload();
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
        jsonData = new Object();
        jsonData.game_id = $(".confsite-game-panel").attr('id');
        jsonData.agent_id = $("#confpb-agent-code-id").val();
        jsonData.agent_token = $("#confpb-agent-token-id").val();
        jsonData = JSON.stringify(jsonData);
        // console.log(jsonData);
        $.ajax({
            type: "POST",
            dataType: "json",
            url: FURL + "/api/saveAgent",
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
                console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
            }

        });
    });


    $('#confsite-cancel-btn-id').on('click', function() {
        location.reload();
    });

    
    $('#refresh_egg').on('click', function() {
        $(this).addClass("refresh");
        requestConfGame();
    });

    $('#refresh_useregg').on('click', function() {
        $(this).addClass("refresh");
        requestConfGame();
    });

    $('#recovery_useregg').on('click', function() {
        if(confirm("알회수 진행시 몇분정도 소요될수 있습니다. 계속하시겠습니까?"))
            requestRecoveryEgg();
    });

    $('#confsite-agent-btn-id').on('click', function() {
        
        let gameId = $(".confsite-game-panel").attr('id');
        var openWindow = window.open("about:blank");
        if(gameId == GAME_CASINO_EVOL){
            openWindow.location.href = "https://www.hpplaycasion.com/";
        } else if(gameId == GAME_SLOT_THEPLUS){
            openWindow.location.href = "http://system-theplus.com/login";
        } else if(gameId == GAME_SLOT_GSPLAY){
            openWindow.location.href = "http://agent.gsplay-777.com/agent";
        } else if(gameId == GAME_CASINO_KGON || gameId == GAME_SLOT_KGON){
            openWindow.location.href = "https://v1.kgonapi.com";
        } else if(gameId == GAME_CASINO_RAVE || gameId == GAME_SLOT_RAVE){
            openWindow.location.href = "https://backoffice.rave-games.com/";
        } else if(gameId == GAME_CASINO_TREEM || gameId == GAME_SLOT_TREEM){
            openWindow.location.href = "https://backoffice.honorlink.org/";
        } else if(gameId == GAME_CASINO_SIGMA || gameId == GAME_SLOT_SIGMA){
            openWindow.location.href = "https://xsigma-gaming.com/";
        } else if(gameId == GAME_SLOT_GOLD){
            openWindow.location.href = "https://goldslot-link.com/";
        } 
        
    });
    
}