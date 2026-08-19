var mObjUser = null;
var mUserAudio = null;
var mChargeAudio = null;
var mDischarAudio = null;
var mMessageAudio = null;
var mEvolAudio = null;

$(document).ready(function() {
    initMainNavbar();
});

function initMainNavbar() {
    mUserAudio = new Audio();
    mChargeAudio = new Audio();
    mDischarAudio = new Audio();
    mMessageAudio = new Audio();
    mEvolAudio = new Audio();
    addNavbarButtonEvent();

    requestMemberInfo();

    setTimeout(function() { mainNavbarLoop(); }, 1500);
}



function requestMemberInfo() {

    $.ajax({
        type: "POST",
        dataType: "json",
        url: FURL + "/userapi/assets",
        success: function(jResult) {
            // console.log(jResult);
            if (jResult.status == "success") {
                mObjUser = jResult.data;
                showMemberInfo(jResult.data);
                setTimeout(function() { requestEmployeeInfo(); }, 1000);

            } else if (jResult.status == "fail") {
                showAlert('로그인정보를 가져오는데 실패했습니다. \n 다시 가입해 주세요.', 0);
            } else if (jResult.status == "logout") {
                window.location.replace( FURL +"/");
            }
        },
        error: function(request, status, error) {
            // console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
        }
    });

}



function requestEmployeeInfo() {
    if (mObjUser == null) return;
    else if (mObjUser.mb_level < LEVEL_ADMIN) return;

    $.ajax({
        type: "POST",
        dataType: "json",
        url: FURL + "/userapi/empinfo",
        success: function(jResult) {
            //console.log(jResult);            
            if (jResult.status == "success") {
                showEmpInfo(jResult.data, jResult.sound);
            } else if (jResult.status == "fail") {

            } else if (jResult.status == "logout") {
                window.location.replace( FURL +"/");
            }
        },
        error: function(request, status, error) {
            // console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
        }
    });

}


function requestBetInfo() {
    if (mObjUser == null) return;
    else if (mObjUser.mb_level < LEVEL_ADMIN) return;

    $.ajax({
        type: "POST",
        dataType: "json",
        url: FURL + "/userapi/empbetinfo",
        success: function(jResult) {
            // console.log(jResult);            
            if (jResult.status == "success") {
                showEmpBetInfo(jResult.data);
            } else if (jResult.status == "fail") {

            } else if (jResult.status == "logout") {
                window.location.replace( FURL +"/");
            }
        },
        error: function(request, status, error) {
            // console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
        }
    });

}

function requestAlarmState() {

    if($("#main-navbar-alarm-check-id").length == 0)
        return;

    var alarmState = $("#main-navbar-alarm-check-id").prop('checked') ? 1 : 0;

    var jsData = { "mb_state_alarm": alarmState }
    var jsonData = JSON.stringify(jsData);
    $.ajax({
        type: "POST",
        data: { json_: jsonData },
        dataType: "json",
        url: FURL + "/api/changealarmstate",
        success: function(jResult) {
            // console.log(jResult);
            if (jResult.status == "success") {

            } else if (jResult.status == "fail") {

            } else if (jResult.status == "logout") {
                window.location.replace( FURL +"/");
            }
        },
        error: function(request, status, error) {
            //console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
        }
    });

}

function showMemberInfo(objUser) {

    if (objUser == undefined || objUser == null)
        return;

    let strBuf = getMemberLevelString(objUser.mb_level);
    if (strBuf == null)
        strBuf = "";
    $("#main-navbar-level-id").text(strBuf);
    $("#main-navbar-emp-div-id").text(objUser.mb_nickname);

    if (objUser.mb_level < LEVEL_ADMIN) {

        if(objUser.mb_money_all == null)
            objUser.mb_money_all = 0;
        strBuf = parseFloat(objUser.mb_money_all).toLocaleString() + " 원";
        $("#main-navbar-emp_money-id").text(strBuf);

        if(objUser.mb_point == null)
            objUser.mb_point = 0;
        strBuf = parseFloat(objUser.mb_point).toLocaleString() + " P";
        $("#main-navbar-emp_point-id").text(strBuf);

        strBuf = parseInt(objUser.mb_money_charge).toLocaleString() + " 원";
        $("#main-navbar-emp_charge-id").text(strBuf);

        strBuf = parseInt(objUser.mb_money_exchange).toLocaleString() + " 원";
        $("#main-navbar-emp_exchange-id").text(strBuf);

        strBuf = objUser.mb_game_pb_ratio + " % | ";
        strBuf += objUser.mb_game_pb2_ratio + " %";
        $("#main-navbar-emp_pbrate-id").text(strBuf);

        strBuf = objUser.mb_game_cs_ratio + " %";
        $("#main-navbar-emp_evrate-id").text(strBuf);

        strBuf = objUser.mb_game_sl_ratio + " %";
        $("#main-navbar-emp_slrate-id").text(strBuf);

        strBuf = objUser.mb_game_hl_ratio + " %";
        $("#main-navbar-emp_hlrate-id").text(strBuf);

    } else {
        $("#main-navbar-ip-div-id").text(objUser.mb_ip_login);
    }

    if($("#main-navbar-alarm-check-id").length > 0)
        document.getElementById("main-navbar-alarm-check-id").checked = objUser.mb_state_alarm == 1 ? true : false;

}

function showEmpInfo(objEmpInfo, arrSoundInfo) {
    if (objEmpInfo == undefined || objEmpInfo == null)
        return;
    $("#main-navbar-table-id td a").removeClass("flicker");
    $("#main-navbar-bettable-id td a").removeClass("flicker");

    var strBuf = objEmpInfo.new_message + " 신청";
    $("#main-navbar-newmessage-id").text(strBuf);

    var nWaitCnt = 0;
    nWaitCnt += parseInt(objEmpInfo.wait_user);

    strBuf = nWaitCnt + " 신청";
    $("#main-navbar-user_wait-id").text(strBuf);

    strBuf = (parseInt(objEmpInfo.wait_charge) + parseInt(objEmpInfo.moment_charge))  + " 대기";
    $("#main-navbar-charge_wait-id").text(strBuf);

    strBuf = (parseInt(objEmpInfo.wait_exchange) + parseInt(objEmpInfo.moment_exchange))  + " 대기";
    $("#main-navbar-exchange_wait-id").text(strBuf);

    if(objEmpInfo.emp_money == null)
        objEmpInfo.emp_money = 0;
    strBuf = parseFloat(objEmpInfo.emp_money).toLocaleString() + " 원";
    $("#main-navbar-emp_money-id").text(strBuf);

    if(objEmpInfo.emp_point == null)
        objEmpInfo.emp_point = 0;
    strBuf = parseFloat(objEmpInfo.emp_point).toLocaleString() + " P";
    $("#main-navbar-emp_point-id").text(strBuf);

    strBuf = parseInt(objEmpInfo.emp_money_charge).toLocaleString() + " 원";
    $("#main-navbar-emp_charge-id").text(strBuf);

    strBuf = parseInt(objEmpInfo.emp_money_exchange).toLocaleString() + " 원";
    $("#main-navbar-emp_exchange-id").text(strBuf);

    strBuf = parseInt(objEmpInfo.emp_money_give).toLocaleString() + " 원";
    $("#main-navbar-emp_give-id").text(strBuf);

    strBuf = parseInt(objEmpInfo.emp_money_withdraw).toLocaleString() + " 원";
    $("#main-navbar-emp_withdraw-id").text(strBuf);

    let bAlarmEnable = true
    if($("#main-navbar-alarm-check-id").length > 0)
        bAlarmEnable = $("#main-navbar-alarm-check-id").prop('checked');
    let bAlarm = false;
    let nVolume = 1;
    if (nWaitCnt > 0) {
        $("#main-navbar-user_wait-id").addClass("flicker");
        if(bAlarmEnable){
            mUserAudio.src = FURL + '/assets/sound/' + arrSoundInfo[0][0];
            if (parseInt(arrSoundInfo[0][1]) <= 100) {
                nVolume = arrSoundInfo[0][1] / 100.0;
            }
            mUserAudio.volume = nVolume;
            mUserAudio.load();
            mUserAudio.play();
        }

    } 
    if (objEmpInfo.wait_charge > 0) {

        $("#main-navbar-charge_wait-id").addClass("flicker");
        if(bAlarmEnable){
            mChargeAudio.src = FURL + '/assets/sound/' + arrSoundInfo[1][0];
            if (parseInt(arrSoundInfo[1][1]) <= 100) {
                nVolume = arrSoundInfo[1][1] / 100.0;
            }
            mChargeAudio.volume = nVolume;
            mChargeAudio.load();
            mChargeAudio.play();
        }

    } 
    if (objEmpInfo.wait_exchange > 0) {
        $("#main-navbar-exchange_wait-id").addClass("flicker");

        if(bAlarmEnable){
            mDischarAudio.src = FURL + '/assets/sound/' + arrSoundInfo[2][0];
            if (parseInt(arrSoundInfo[2][1]) <= 100) {
                nVolume = arrSoundInfo[2][1] / 100.0;
            }
            mDischarAudio.volume = nVolume;
            mDischarAudio.load();
            mDischarAudio.play();
        }
    } 
    if (objEmpInfo.new_message > 0) {
        $("#main-navbar-newmessage-id").addClass("flicker");
        if(bAlarmEnable){
            mMessageAudio.src = FURL + '/assets/sound/' + arrSoundInfo[3][0];
            if (parseInt(arrSoundInfo[3][1]) <= 100) {
                nVolume = arrSoundInfo[3][1] / 100.0;
            }
            mMessageAudio.volume = nVolume;
            mMessageAudio.load();
            mMessageAudio.play();
        }
    }
    if (objEmpInfo.evol_captcha > 0) {
        $("#main-navbar-evol_wait-id").addClass("flicker");
        if(bAlarmEnable){
            mEvolAudio.src = FURL + '/assets/sound/' + arrSoundInfo[4][0];
            if (parseInt(arrSoundInfo[4][1]) <= 100) {
                nVolume = arrSoundInfo[4][1] / 100.0;
            }
            mEvolAudio.volume = nVolume;
            mEvolAudio.load();
            mEvolAudio.play();
        }
    }
    
}


function showEmpBetInfo(arrBetInfo) {
    if (arrBetInfo == undefined || arrBetInfo == null)
        return;

    $("#main-navbar-minibet-id").text(parseInt(arrBetInfo[0][0]).toLocaleString() + " 원");
    $("#main-navbar-minibetearn-id").text(parseInt(arrBetInfo[0][1]).toLocaleString() + " 원");

    $("#main-navbar-evbet-id").text(parseInt(arrBetInfo[1][0]).toLocaleString() + " 원");
    $("#main-navbar-evbetearn-id").text(parseInt(arrBetInfo[1][1]).toLocaleString() + " 원");
    // $("#main-navbar-evegg-id").text(parseInt(arrBetInfo[1][2]).toLocaleString() + " 원");
    // $("#main-navbar-evuser-id").text(parseInt(arrBetInfo[1][3]).toLocaleString() + " 원");

    $("#main-navbar-slbet-id").text(parseInt(arrBetInfo[2][0]).toLocaleString() + " 원");
    $("#main-navbar-slbetearn-id").text(parseInt(arrBetInfo[2][1]).toLocaleString() + " 원");
    $("#main-navbar-slegg-id").text(parseInt(arrBetInfo[2][2]).toLocaleString() + " 원");
    $("#main-navbar-sluser-id").text(parseInt(arrBetInfo[2][3]).toLocaleString() + " 원");
    
    $("#main-navbar-fslbet-id").text(parseInt(arrBetInfo[3][0]).toLocaleString() + " 원");
    $("#main-navbar-fslbetearn-id").text(parseInt(arrBetInfo[3][1]).toLocaleString() + " 원");
    $("#main-navbar-fslegg-id").text(parseInt(arrBetInfo[3][2]).toLocaleString() + " 원");
    $("#main-navbar-fsluser-id").text(parseInt(arrBetInfo[3][3]).toLocaleString() + " 원");

    $("#main-navbar-kgbet-id").text(parseInt(arrBetInfo[4][0]).toLocaleString() + " 원");
    $("#main-navbar-kgbetearn-id").text(parseInt(arrBetInfo[4][1]).toLocaleString() + " 원");
    $("#main-navbar-kgegg-id").text(parseInt(arrBetInfo[4][2]).toLocaleString() + " 원");
    $("#main-navbar-kguser-id").text(parseInt(arrBetInfo[4][3]).toLocaleString() + " 원");

    $("#main-navbar-hlbet-id").text(parseInt(arrBetInfo[5][0]).toLocaleString() + " 원");
    $("#main-navbar-hlbetearn-id").text(parseInt(arrBetInfo[5][1]).toLocaleString() + " 원");
    $("#main-navbar-hlegg-id").text(parseInt(arrBetInfo[5][2]).toLocaleString() + " 원");
    $("#main-navbar-hluser-id").text(parseInt(arrBetInfo[5][3]).toLocaleString() + " 원");

    $("#main-navbar-prbet-id").text(parseInt(arrBetInfo[6][0]).toLocaleString() + " 원");
    $("#main-navbar-prbetearn-id").text(parseInt(arrBetInfo[6][1]).toLocaleString() + " 원");
    
}



function mainNavbarLoop() {


    if (mObjUser != null) {
        if (parseInt(mObjUser.mb_level) >= LEVEL_ADMIN)
            requestBetInfo();
    }

    // 1초뒤에 다시 실행
    setTimeout(function() {
        mainNavbarLoop();
    }, 300000);

}


function addNavbarButtonEvent() {


    $("#main-navbar-alarm-check-id").click(function() {
        if (!this.checked) {
            mUserAudio.pause();
            mChargeAudio.pause();
            mDischarAudio.pause();
            mMessageAudio.pause();
        }
        requestAlarmState();
    });


    /**DropDown Menu***/
    var mainNavbarDropDown = document.getElementById("main-navbar-dropdown-container-id");
    var btnNavbarEmp = document.getElementById("main-navbar-emp-div-id");
    btnNavbarEmp.onclick = function() {
        if (mainNavbarDropDown.style.display == "none")
            mainNavbarDropDown.style.display = "block";
        else mainNavbarDropDown.style.display = "none";
    }


    var btnDropdownLogout = document.getElementById("main-navbar-dropdown-logout-id");
    btnDropdownLogout.onclick = function() {
        window.location.replace( FURL +"/pages/logout");

    }


    window.onclick = function(event) {
        if (event.target != btnNavbarEmp && mainNavbarDropDown.style.display == "block") {
            mainNavbarDropDown.style.display = "none";
        }
    }



}