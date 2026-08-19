$(document).ready(function() {
    requestJoinIp();
    addBtnEvent();
});

function readPasswordToObject() {

    var jsonData = new Object();

    jsonData.password = $("#confsite-password-input-id").val();
    jsonData.password_new = $("#confsite-password-new-input-id").val();
    jsonData.password_newok = $("#confsite-password-newok-input-id").val();

    if ($("#confsite-ip-input-id").length > 0) {
        jsonData.ip_addr = $("#confsite-ip-input-id").val()+";"+$("#confsite-ip2-input-id").val()
            +";"+$("#confsite-ip3-input-id").val()+";"+$("#confsite-ip4-input-id").val()
            +";"+$("#confsite-ip5-input-id").val();
        jsonData.ip_check = $("#confsite-ip-check-id").prop('checked') ? 1 : 0;
    }
    return jsonData;

}

function addBtnEvent() {

    $("#confsite-ok-btn-id").click(function() {

        var jsonData = readPasswordToObject();

        if (jsonData.password.length < 1) {
            showAlert("현재 비밀번호를 입력해주세요.", 3);
            return;
        }

        if (jsonData.password_new.length > 0 && jsonData.password_new != jsonData.password_newok) {
            showAlert("새 비밀번호를 정확히 입력해주세요.", 3);
            return;
        }

        if (!confirm("저장하시겠습니까?"))
            return;

        jsonData = JSON.stringify(jsonData);

        $.ajax({
            type: "POST",
            dataType: "json",
            url: FURL + "/api/change_password",
            data: { json_: jsonData },
            success: function(jResult) {
                //console.log(jResult);
                if (jResult.status == "success") {
                    showAlert("정보가 변경되었습니다.");
                } else if (jResult.status == "fail") {
                    showAlert(jResult.msg, 0);
                }
            },
            error: function(request, status, error) {
                // console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
            }

        });
    });


    $("#confsite-cancel-btn-id").click(function() {
        location.reload();
    });

    $("#recovery_useregg").click(function() {
        $("#recovery_useregg").addClass("refresh");

        var jsonData = { "self":1 };
        jsonData = JSON.stringify(jsonData);

        $.ajax({
            type: "POST",
            dataType: "json",
            url: FURL + "/userapi/eggcollect",
            data: { json_: jsonData },
            success: function(jResult) {
                $("#recovery_useregg").removeClass("refresh");
                // console.log(jResult);
                if (jResult.status == "success") {
                    $("#confsite-egg-input-id").val(jResult.money);
                    if(jResult.egg > 0)
                        $("#recovery_useregg").show();
                    else 
                        $("#recovery_useregg").hide();
                } else if (jResult.status == "fail") {
                    showAlert("회수가 실패되었습니다.", 0);
                }
            },
            error: function(request, status, error) {
                $("#recovery_useregg").removeClass("refresh");
                // console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
            }

        });

    });

    $("#confsite-give-but-id").click(function() {
        var nAmount = parseFloat($("#confsite-money-input-id").val().replace(/,/g, ""));
        if (isNaN(nAmount) || nAmount == "" || !mObjUser) {
            nAmount = 0;
        }
        if (nAmount == 0) {
            showAlert("지급금액을 입력 해주세요.", 3);
            return false;
        }

        if (!confirm(nAmount.toLocaleString() + "원을 지급하시겠습니까?"))
            return;
    
        var jsonData = {
            'mb_fid': mObjUser.mb_fid,
            'amount': nAmount,
            'type':4
        }
        requestTrasnfer(jsonData);
    });

    $("#confsite-collect-but-id").click(function() {
        var nAmount = parseFloat($("#confsite-money-input-id").val().replace(/,/g, ""));
        if (isNaN(nAmount) || nAmount == "" || !mObjUser) {
            nAmount = 0;
        }
        if (nAmount == 0) {
            showAlert("회수금액을 입력 해주세요.", 3);
            return false;
        }

        if (!confirm(nAmount.toLocaleString() + "원을 회수하시겠습니까?"))
            return;
    
        var jsonData = {
            'mb_fid': mObjUser.mb_fid,
            'amount': nAmount,
            'type':5
        }
        requestTrasnfer(jsonData);
    });

}

function setJoinIp(ipInfo) {
    ips = ipInfo.ip_addr.split(';');
    
    if(ips.length > 0)
        $("#confsite-ip-input-id").val(ips[0]);
    else 
        $("#confsite-ip-input-id").val("");

    if(ips.length > 1)
        $("#confsite-ip2-input-id").val(ips[1]);
    else 
        $("#confsite-ip2-input-id").val("");

    if(ips.length > 2)
        $("#confsite-ip3-input-id").val(ips[2]);
    else 
        $("#confsite-ip3-input-id").val("");

    if(ips.length > 3)
        $("#confsite-ip4-input-id").val(ips[3]);
    else 
        $("#confsite-ip4-input-id").val("");

    if(ips.length > 4)
        $("#confsite-ip5-input-id").val(ips[4]);
    else 
        $("#confsite-ip5-input-id").val("");

    $("#confsite-ip-check-id").prop('checked', ipInfo.ip_check > 0 ? true : false);
    $("#confsite-egg-input-id").val(ipInfo.money);
    if(ipInfo.egg > 0)
        $("#recovery_useregg").show();
    else 
        $("#recovery_useregg").hide();

}

function requestJoinIp() {
    $.ajax({
        type: "POST",
        dataType: "json",
        url: FURL + "/userapi/empIp",
        success: function(jResult) {
            // console.log(jResult);
            if (jResult.status == "success") {
                setJoinIp(jResult.data);
            } else if (jResult.status == "logout") {

            }
        },
        error: function(request, status, error) {
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        }

    });
}