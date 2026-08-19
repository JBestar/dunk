$(document).ready(function() {
    setNavBarElement();
    requestConfBetSite(true);
    addBtnEvent();
    if($("#confev-bet-allcheck-id").length > 0)
        requestConfBetState();
});


function showConfSite(arrData, all) {
    if (arrData.length < 3)
        return;

    for(let i = 1; i <= arrData.length; i ++){
        let data = arrData[i-1];
        if(all){
            $("#confev-bet-check-id"+i).prop('checked', data[3] > 0 ? true : false);
            $("#confev-betsite-input-id"+i).val(data[0]);
            $("#confev-userid-input-id"+i).val(data[1]);
            $("#confev-userpwd-input-id"+i).val(data[2]);
            $("#confev-bettype-select-id"+i).val(data[4]);
            $("#confev-conbet-check-id"+i).prop('checked', data[6] > 0 ? true : false);
            $("#confev-conallbet-check-id"+i).prop('checked', data[19] > 0 ? true : false);
            $("#confev-signal-check-id"+i).prop('checked', data[13] > 0 ? true : false);
            $("#confev-multiroom-check-id"+i).val(data[15]);
            $("#confev-balmax-input-id"+i).val(data[20]);
        
            if( i == 1){
                $("#confev-betend-input-id").val(data[5]);
                $("#confev-betmin-input-id").val(data[8]);
                $("#confev-betmax-input-id").val(data[9]);
                $("#confev-conbet-input-id").val(data[11]);
                $("#confev-maxuser-input-id").val(data[12]);
                
            } else if( i == 2){
                $("#confev-betmode-select-id").val(data[5]);
            }

            //captcha code
            $('#confev-captcha-input-id'+i).val(data[18]);
            
        }
        
        $('#confev-captcha-div-id'+i).hide();

        if(data[4] == 23 && data[17].length > 0){
            $('#confev-captcha-img-id'+i).attr('src', data[17]); //captcha image
            $('#confev-captcha-div-id'+i).show();
        }


        if( i == 1){
            $('#confev-conuser-span-id').text('접속자수: '+data[14]+"명, ");
            $('#confev-follower-span-id').text('따라가기: '+data[16]+"명");
        }

        $('#confev-balance-input-id'+i).val('');
        $('#confev-balance-label-id'+i).text('');
    
        if(parseInt(data[7]) >= 0){
            $('#confev-balance-input-id'+i).val(data[7].toLocaleString());
        }
        if(parseInt(data[7]) < -2){
            $('#confev-balance-label-id'+i).text(`( 정지됨 )`);
        } else if(data[10].length > 0)
            $('#confev-balance-label-id'+i).text(`( ${data[10]} )`);
    
    
    }

    setTimeout(() =>{
        this.requestConfBetSite();
    }, 5000);

}


function addBtnEvent() {

    $("#confsite-ok-btn-id").click(function() {

        let arrData = [];
        for(i = 1; i <= 7; i++){
            let objData = new Object();
            if($("#confev-betsite-input-id"+i).length < 1)
                continue;

            objData.game = mGameId;
            objData.site_ev = $("#confev-betsite-input-id"+i).val().trim();
            objData.userid_ev = $("#confev-userid-input-id"+i).val().trim();
            objData.userpwd_ev = $("#confev-userpwd-input-id"+i).val().trim();
            objData.active_ev = $("#confev-bet-check-id"+i).prop('checked') ? 1 : 0;
            objData.type_ev = $("#confev-bettype-select-id"+i).val();
            objData.con_ev = $("#confev-conbet-check-id"+i).prop('checked') ? 1 : 0;
            objData.is_signal = $("#confev-signal-check-id"+i).prop('checked') ? 1 : 0;
            if($("#confev-multiroom-check-id"+i).length > 0)
                objData.multiroom = $("#confev-multiroom-check-id"+i).val();
            else objData.multiroom = 0;
            if($("#confev-conallbet-check-id"+i).length > 0)
                objData.conall_ev = $("#confev-conallbet-check-id"+i).prop('checked') ? 1 : 0;
            else objData.conall_ev = 0;
            objData.bal_max = $("#confev-balmax-input-id"+i).val();
            
            if(i == 1){
                objData.bet_ev = $("#confev-betend-input-id").val();
                objData.bet_min = $("#confev-betmin-input-id").val();
                objData.bet_max = $("#confev-betmax-input-id").val();
                objData.con_min = $("#confev-conbet-input-id").val();
                objData.user_max = $("#confev-maxuser-input-id").val();
            } else if( i== 2){
                objData.betmode_ev = $("#confev-betmode-select-id").val(); 
            }
            
            arrData.push(objData);
        } 
        
        var jsonData = JSON.stringify(arrData);

        if (!confirm("저장하시겠습니까?"))
            return;
        if($("#confev-bet-allcheck-id").length > 0)
            setEbalState($("#confev-bet-allcheck-id").prop('checked') ? 1 : 0);

        $.ajax({
            type: "POST",
            dataType: "json",
            url: FURL + "/api/setEvolSite",
            data: { json_: jsonData },
            success: function(jResult) {
                // console.log(jResult);
                if (jResult.status == "success") {
                    // window.location.reload();
                } else if (jResult.status == "logout") {
                    window.location.replace( FURL +'/');
                } else if (jResult.status == "fail") {
                    showAlert("저장이 실패되었습니다.", 0);
                } else if (jResult.status == "nopermit") {
                    showAlert("권한이 없습니다.", 0);
                }
            },
            error: function(request, status, error) {
                // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
            }

        });

    });

    $("#confsite-cancel-btn-id").click(function() {
        location.reload();
    });

    $("#confsite-betrange-reset-id").click(function() {
        
        if (!confirm("전체 리셋하시겠습니까?"))
            return;

        let data = {
            mb_range_ev:'0:0',
        }
        requestUpdateMember(data);
    });
    
    $("#confsite-press-check-id").click(function() {
        
        if (!confirm("전체 누르기체크 하시겠습니까?"))
            return;

        let data = {
            mb_state_view:1,
            mb_state_test:0,
        }
        requestUpdateMember(data);
    });
    
    $("#confsite-press-reset-id").click(function() {
        
        if (!confirm("전체 누르기해제 하시겠습니까?"))
            return;

        let data = {
            mb_state_view:0,
            mb_state_test:0,
        }
        requestUpdateMember(data);
    });
}



function requestConfBetSite(all=false) {
    var jsonData = { "game": mGameId };
    jsonData = JSON.stringify(jsonData);

    $.ajax({
        type: "POST",
        dataType: "json",
        data: { json_: jsonData },
        url: FURL + "/api/getEvolSite",
        success: function(jResult) {
            // console.log(jResult);
            if (jResult.status == "success") {
                showConfSite(jResult.data, all);
            } else if (jResult.status == "logout") {
                window.location.replace( FURL +'/');
            } else if (jResult.status == "nopermit") {
                showAlert("권한이 없습니다.", 0);
            }
        },
        error: function(request, status, error) {
            // console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
        }

    });
}


function requestConfBetState() {
    var jsonData = { "game": mGameId };
    jsonData = JSON.stringify(jsonData);

    $.ajax({
        type: "POST",
        dataType: "json",
        data: { json_: jsonData },
        url: FURL + "/api/getEvolState",
        success: function(jResult) {
            // console.log(jResult);
            if (jResult.status == "success") {
                $("#confev-bet-allcheck-id").prop('checked', jResult.data > 0 ? true : false);
            } else if (jResult.status == "logout") {
                window.location.replace( FURL +'/');
            } else if (jResult.status == "nopermit") {
                showAlert("권한이 없습니다.", 0);
            }
        },
        error: function(request, status, error) {
            // console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
        }

    });
}


function setEbalState(state){
    
    var objData = new Object();

    objData.active_ev = state;
    objData.game = mGameId;

    var jsonData = JSON.stringify(objData);

    $.ajax({
        type: "POST",
        dataType: "json",
        url: FURL + "/api/setEvolState",
        data: { json_: jsonData },
        success: function(jResult) {
            // console.log(jResult);
            if (jResult.status == "success") {
                window.location.reload();
            } else if (jResult.status == "logout") {
                window.location.replace( FURL +'/');
            } else if (jResult.status == "fail") {
                showAlert("조작이 실패되었습니다.", 0);
            } else if (jResult.status == "nopermit") {
                showAlert("권한이 없습니다.", 0);
            }
        },
        error: function(request, status, error) {
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        }

    });
}

function requestUpdateMember(data){
    let jsonData = JSON.stringify(data);
    $.ajax({
        type: "POST",
        dataType: "json",
        url: FURL + "/userapi/updatemembers",
        data: { json_: jsonData },
        success: function(jResult) {
            // console.log(jResult);
            if (jResult.status == "success") {
                showAlert("전체 적용되었습니다.")
            } else if (jResult.status == "logout") {
                window.location.replace( FURL +'/');
            }
        },
        error: function(request, status, error) {
            $(".loading").hide();
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        }

    });
}

function saveCaptcha(i){

    let code = $('#confev-captcha-input-id'+i).val();

    objData = {index:i, code:code, game:mGameId}
    let jsonData = JSON.stringify(objData);

    $.ajax({
        type: "POST",
        dataType: "json",
        data: { json_: jsonData },
        url: FURL + "/api/setEvolCaptcha",
        success: function(jResult) {
            // console.log(jResult);
            if (jResult.status == "success") {
                showAlert("저장되었니다.");
                requestEmployeeInfo();
            } else if (jResult.status == "logout") {
                window.location.replace( FURL +'/');
            } else if (jResult.status == "nopermit") {
                showAlert("권한이 없습니다.", 0);
            }
        },
        error: function(request, status, error) {
            // console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
        }

    });

}
