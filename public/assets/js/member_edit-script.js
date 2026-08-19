$(document).ready(function() {
    addBtnEvent();
});

function readConfigToObject() {

    var objMember = new Object();
    objMember.admin_level = $("#subnavbar-emplevel-p-id").text();

    objMember.mb_fid = $("#subnavbar-fid-p-id").text();
    objMember.mb_uid = $("#useredit-id-input-id").val();
    objMember.mb_grade = $("#useredit-level-select-id").val();
    objMember.mb_nickname = $("#useredit-nickname-input-id").val();
    objMember.mb_color = $("#useredit-color-input-id").val();

    if (objMember.admin_level >= LEVEL_ADMIN) {
        objMember.mb_pwd = $("#useredit-pwd-input-id").val();
        objMember.mb_emp_uid = $("#useredit-sort-select-id").val();
        if ($("#useredit-phone-input-id").length > 0)
            objMember.mb_phone = $("#useredit-phone-input-id").val();
        if ($("#useredit-bankname-input-id").length > 0)
            objMember.mb_bank_name = $("#useredit-bankname-input-id").val();
        if ($("#useredit-bankaccount-input-id").length> 0)
            objMember.mb_bank_own = $("#useredit-bankaccount-input-id").val();
        if ($("#useredit-bankserial-input-id").length > 0)
            objMember.mb_bank_num = $("#useredit-bankserial-input-id").val();
        if ($("#useredit-bankpwd-input-id").length > 0)
            objMember.mb_bank_pwd = $("#useredit-bankpwd-input-id").val();
        var nAmount = parseInt($("#useredit-money-input-id").val().replace(/,/g, ""));
        if (isNaN(nAmount) || nAmount == "") {
            nAmount = 0;
        }
        objMember.mb_money = nAmount;
        var nAmount = parseInt($("#useredit-point-input-id").val().replace(/,/g, ""));
        if (isNaN(nAmount) || nAmount == "") {
            nAmount = 0;
        }
        objMember.mb_point = nAmount;

    }
    if($("#useredit-pbbetrate-input-id").length > 0){
        objMember.mb_game_pb_ratio = $("#useredit-pbbetrate-input-id").val();
        objMember.mb_game_pb2_ratio = $("#useredit-pbbetrate2-input-id").val();
    }  

    if($("#useredit-pbbetpercent-input-id").length > 0){
        objMember.mb_game_pb_percent = $("#useredit-pbbetpercent-input-id").val();
        objMember.mb_game_pb2_percent = $("#useredit-pbbetpercent2-input-id").val();
    } 
    
    if($("#useredit-evbetrate-input-id").length > 0){
        objMember.mb_game_cs_ratio = $("#useredit-evbetrate-input-id").val();
    }

    if($("#useredit-slbetrate-input-id").length > 0){
        objMember.mb_game_sl_ratio = $("#useredit-slbetrate-input-id").val();
    }
    
    if($("#useredit-hlbetrate-input-id").length > 0){
        objMember.mb_game_hl_ratio = $("#useredit-hlbetrate-input-id").val();
    }

    if ($("#useredit-offline-check-id").length > 0){
        objMember.mb_state_delete = $("#useredit-offline-check-id").prop('checked') ? 1 : 0;
    } 

    if ($("#useredit-test-check-id").length > 0){
        objMember.mb_state_test = $("#useredit-test-check-id").prop('checked') ? 1 : 0;
    } 

    if ($("#useredit-memo-text-id").length > 0){
        objMember.mb_memo = $("#useredit-memo-text-id").val();
    } 
    
    if ($("#useredit-balance-check-id").length > 0){
        objMember.mb_state_view = $("#useredit-balance-check-id").prop('checked') ? 1 : 0;
        let min = parseInt($("#useredit-rangemin-input-id").val().replace(/,/g, ""));
        let max = parseInt($("#useredit-rangemax-input-id").val().replace(/,/g, ""));

        if(min < 0) min = 0;
        if(max < 0) max = 0;

        objMember.mb_range_ev = min + ":" + max; 
    } 

    if ($("#useredit-rangelimit-input-id").length > 0){
        let limit = parseInt($("#useredit-rangelimit-input-id").val().replace(/,/g, ""));

        if(limit < 0) limit = 0;
        objMember.mb_range_limit = limit; 
    } 

    if($("#useredit-press-check-id").length > 0){
        let pressEnable = 0;
        let pressAmount = 0;
        pressEnable = $("#useredit-press-check-id").prop('checked') ? 1 : 0;
        pressAmount = parseInt($("#useredit-press-input-id").val().replace(/,/g, ""));
        if(pressAmount < 0) pressAmount = 0;

        objMember.mb_press_ev = pressEnable + ":" + pressAmount; 
        if($("#useredit-press-count-id").length > 0){
            let pressCount = $("#useredit-press-count-id").val();
            objMember.mb_press_ev += ":"+ pressCount;
        }
    }

    if($("#useredit-pressat-check-id").length > 0){
        let pressatEnable = 0;
        let pressatAmount = 0;
        pressatEnable = $("#useredit-pressat-check-id").prop('checked') ? 1 : 0;
        pressatAmount = parseInt($("#useredit-pressat-input-id").val().replace(/,/g, ""));
        if(pressatAmount < 0) pressatAmount = 0;

        objMember.mb_pressat_ev = pressatEnable + ":" + pressatAmount; 
    }

    if($("#useredit-follow-check-id").length > 0){
        let followEnable = $("#useredit-follow-check-id").prop('checked') ? 1 : 0;
        let followId = $("#useredit-follow-input-id").val();
        let followPercent = $("#useredit-follow-percent-id").val();

        objMember.mb_follow_ev = followEnable + ":" + followId.trim() + ":" + parseInt(followPercent); 
    }
    if($("#useredit-followen-check-id").length > 0){
        let followEnable = $("#useredit-followen-check-id").prop('checked') ? 1 : 0;
        objMember.mb_follow_en = followEnable; 
    }
    if($("#useredit-overbet-percent-id").length > 0){
        let overbetPercent = $("#useredit-overbet-percent-id").val();
        objMember.mb_overbet_percent = overbetPercent; 
    }
    let elApps = $("input[name=useredit-auto-apps]");
    if(elApps.length > 0){
        let data = '';
        for(var i=0; i<elApps.length; i++){
            let act = $(elApps[i]).prop('checked') ? 1 : 0;
            data+=`${act}#`;
        }
        objMember.mb_autoapps = data;
    }
    if($("#useredit-charge-bankname-id").length > 0){
        objMember.mb_charge_info = $("#useredit-charge-bankname-id").val().trim()+"#"+
            $("#useredit-charge-bankaccount-id").val().trim()+"#"+$("#useredit-charge-bankserial-id").val().trim();
    }
    if($("#useredit-transfer-subs-id").length > 0){
        objMember.mb_transfer_subs = $("#useredit-transfer-subs-id").prop('checked') ? 1 : 0;
    }
    if($("#useredit-recommender-deny-id").length > 0){
        objMember.mb_recommender_deny = $("#useredit-recommender-deny-id").prop('checked') ? 1 : 0;
    }
    return objMember;

}

function addBtnEvent() {

    $("#useredit-save-btn-id").click(function() {

        var objMember = readConfigToObject();
        reqMemSave(objMember);

    });


    $("#useredit-cancel-btn-id").click(function() {
        window.close();
    });

    
    // $("#useredit-withdraw-money-id").click(function() {
    //     if (!confirm("보유금액을 회수하시겠습니까?"))
    //         return;

    //     requestWithdraw(0);
    // });

    $("#useredit-withdraw-point-id").click(function() {
        if (!confirm("보유포인트를 회수하시겠습니까?"))
            return;

        requestWithdraw(1)
    });

    $("#useredit-change-point-id").click(function() {
        if (!confirm("포인트를 머니로 전환하시겠습니까?"))
            return;

        requestWithdraw(2)
    });
    
    $("#useredit-deposit-but-id").click(function() {
        var nAmount = parseFloat($("#useredit-transfer-input-id").val().replace(/,/g, ""));
        if (isNaN(nAmount) || nAmount == "") {
            nAmount = 0;
        }
        if (nAmount == 0) {
            showAlert("충전금액을 입력 해주세요.", 0);
            return false;
        }

        if (!confirm(nAmount.toLocaleString() + "원을 직충전하시겠습니까?"))
            return;
    
        var jsonData = {
            'mb_fid': $("#subnavbar-fid-p-id").html(),
            'amount': nAmount,
            'type':0
        }
        requestTrasnfer(jsonData);
    });

    //직환전
    $("#useredit-withdraw-but-id").click(function() {
        var nAmount = parseFloat($("#useredit-transfer-input-id").val().replace(/,/g, ""));
        if (isNaN(nAmount) || nAmount == "") {
            nAmount = 0;
        }
        if (nAmount == 0) {
            showAlert("환전금액을 입력 해주세요.", 0);
            return false;
        }

        if (!confirm(nAmount.toLocaleString() + "원을 직환전하시겠습니까?"))
            return;
    
        var jsonData = {
            'mb_fid': $("#subnavbar-fid-p-id").html(),
            'amount': nAmount,
            'type':1
        }
        requestTrasnfer(jsonData);

    });
    

    $("#useredit-transfer-but-id").click(function() {
        var nAmount = parseFloat($("#useredit-transfer-input-id").val().replace(/,/g, ""));
        if (isNaN(nAmount) || nAmount == "") {
            nAmount = 0;
        }
        if (nAmount == 0) {
            showAlert("이동금액을 입력 해주세요.", 0);
            return false;
        }

        if (!confirm(nAmount.toLocaleString() + "원을 회원에게 이동하시겠습니까?"))
            return;

        var jsonData = {
            'mb_fid': $("#subnavbar-fid-p-id").html(),
            'amount': nAmount,
            'type':2
        }
        requestTrasnfer(jsonData);

    });
    
    
    $("#useredit-return-but-id").click(function() {
        var nAmount = parseFloat($("#useredit-transfer-input-id").val().replace(/,/g, ""));
        if (isNaN(nAmount) || nAmount == "") {
            nAmount = 0;
        }
        if (nAmount == 0) {
            showAlert("환수금액을 입력 해주세요.", 0);
            return false;
        }

        if (!confirm(nAmount.toLocaleString() + "원을 회원에게서 환수하시겠습니까?"))
            return;

        var jsonData = {
            'mb_fid': $("#subnavbar-fid-p-id").html(),
            'amount': nAmount,
            'type':3
        }
        requestTrasnfer(jsonData);

    });


    //give money
    $("#useredit-give-but-id").click(function() {
        var nAmount = parseFloat($("#useredit-transfer-input-id").val().replace(/,/g, ""));
        if (isNaN(nAmount) || nAmount == "") {
            nAmount = 0;
        }
        if (nAmount == 0) {
            showAlert("지급금액을 입력 해주세요.", 0);
            return false;
        }

        if (!confirm(nAmount.toLocaleString() + "원을 지급하시겠습니까?"))
            return;
    
        var jsonData = {
            'mb_fid': $("#subnavbar-fid-p-id").html(),
            'amount': nAmount,
            'type':4
        }
        requestTrasnfer(jsonData);
    });

    //collect money
    $("#useredit-collect-but-id").click(function() {
        var nAmount = parseFloat($("#useredit-transfer-input-id").val().replace(/,/g, ""));
        if (isNaN(nAmount) || nAmount == "") {
            nAmount = 0;
        }
        if (nAmount == 0) {
            showAlert("회수금액을 입력 해주세요.", 0);
            return false;
        }

        if (!confirm(nAmount.toLocaleString() + "원을 회수하시겠습니까?"))
            return;
    
        var jsonData = {
            'mb_fid': $("#subnavbar-fid-p-id").html(),
            'amount': nAmount,
            'type':5
        }
        requestTrasnfer(jsonData);
    });
    
    //머니전환
    $("#useredit-change-money-id").click(function() {
        var nAmount = parseFloat($("#useredit-transfer-input-id").val().replace(/,/g, ""));
        if (isNaN(nAmount) || nAmount == "") {
            nAmount = 0;
        }
        if (nAmount == 0) {
            showAlert("전환금액을 입력 해주세요.", 0);
            return false;
        }

        if (!confirm(nAmount.toLocaleString() + "원을 포인트로 전환하시겠습니까?"))
            return;
    
        var jsonData = {
            'mb_fid': $("#subnavbar-fid-p-id").html(),
            'amount': nAmount,
            'type':6
        }
        requestTrasnfer(jsonData);

    });
    
    $("#useredit-return-emp-id").click(function() {
        var nAmount = parseFloat($("#useredit-transfer-input-id").val().replace(/,/g, ""));
        if (isNaN(nAmount) || nAmount == "") {
            nAmount = 0;
        }
        if (nAmount == 0) {
            showAlert("환수금액을 입력 해주세요.", 0);
            return false;
        }
        let empId = $("#useredit-emp-select-id").val();

        if (!confirm(nAmount.toLocaleString() + "원을 상부회원("+empId+")에 환수하시겠습니까?"))
            return;

        var jsonData = {
            'mb_fid': $("#subnavbar-fid-p-id").html(),
            'mb_emp': empId,
            'amount': nAmount,
            'type':7
        }
        requestTrasnfer(jsonData);

    });

    $("#useredit-press-but-id").click(function() {
        if($("#useredit-press-check-id").length < 1)
            return;
        
        if (!confirm("전체 적용하시겠습니까?"))
            return;

        let pressEnable = 0;
        let pressAmount = 0;
        pressEnable = $("#useredit-press-check-id").prop('checked') ? 1 : 0;
        pressAmount = parseInt($("#useredit-press-input-id").val().replace(/,/g, ""));
        if(pressAmount < 0) pressAmount = 0;

        let mb_press_ev = pressEnable + ":" + pressAmount;
        if($("#useredit-press-count-id").length > 0){
            let pressCount = $("#useredit-press-count-id").val();
            mb_press_ev += ":"+ pressCount;
        }
        var jsonData = {
            mb_press_ev:mb_press_ev,
        }
        jsonData = JSON.stringify(jsonData);
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
    });

    $("#useredit-pressat-but-id").click(function() {
        if($("#useredit-pressat-check-id").length < 1)
            return;
        
        if (!confirm("전체 적용하시겠습니까?"))
            return;

        let pressatEnable = 0;
        let pressatAmount = 0;
        pressatEnable = $("#useredit-pressat-check-id").prop('checked') ? 1 : 0;
        pressatAmount = parseInt($("#useredit-pressat-input-id").val().replace(/,/g, ""));
        if(pressatAmount < 0) pressatAmount = 0;

        let mb_pressat_ev = pressatEnable + ":" + pressatAmount;
        var jsonData = {
            mb_pressat_ev:mb_pressat_ev,
        }
        jsonData = JSON.stringify(jsonData);
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
    });
    
    $("#useredit-followen-but-id").click(function() {
        if($("#useredit-followen-check-id").length < 1)
            return;
        
        if (!confirm("전체 적용하시겠습니까?"))
            return;

        let followEnable = $("#useredit-followen-check-id").prop('checked') ? 1 : 0;

        var jsonData = {
            mb_follow_en:followEnable,
        }
        jsonData = JSON.stringify(jsonData);
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
    });
}


function requestWithdraw(iType){
    var jsonData = {
        'mb_fid': $("#subnavbar-fid-p-id").html(),
        'type': iType
    }
    $(".loading").show();

    jsonData = JSON.stringify(jsonData);
    $.ajax({
        type: "POST",
        dataType: "json",
        url: FURL + "/userapi/withdraw",
        data: { json_: jsonData },
        success: function(jResult) {
            $(".loading").hide();
            // console.log(jResult);
            if (jResult.status == "success") {
                location.reload();
            } else if (jResult.status == "logout") {
                window.location.replace( FURL +'/');
            } else if (jResult.status == "fail") {
                if(jResult.msg)
                    showAlert(jResult.msg, 0);
                else showAlert("회수가 실패되었습니다.", 0);
            }
        },
        error: function(request, status, error) {
            // $(".loading").hide();
            // console.log("code:" + request.status + "\n" + "message:" + request.responseText + "\n" + "error:" + error);
        }

    });
}


$(function() {
    $("#useredit-transfer-input-id").on("propertychange change keyup paste input", function() {
        calcAmount("#useredit-transfer-input-id");
    });
    $("#useredit-money-input-id").on("propertychange change keyup paste input", function() {
        calcAmount("#useredit-money-input-id");
    });
    $("#useredit-point-input-id").on("propertychange change keyup paste input", function() {
        calcAmount("#useredit-point-input-id");
    });
    
    if($("#useredit-rangemin-input-id").length > 0){
        $("#useredit-rangemin-input-id").on("propertychange change keyup paste input", function() {
            calcAmount("#useredit-rangemin-input-id");
        });
    }
    
    if($("#useredit-rangemax-input-id").length > 0){
        $("#useredit-rangemax-input-id").on("propertychange change keyup paste input", function() {
            calcAmount("#useredit-rangemax-input-id");
        });
    }
    
    if($("#useredit-rangelimit-input-id").length > 0){
        $("#useredit-rangelimit-input-id").on("propertychange change keyup paste input", function() {
            calcAmount("#useredit-rangelimit-input-id");
        });
    }

    if($("#useredit-press-input-id").length > 0){
        $("#useredit-press-input-id").on("propertychange change keyup paste input", function() {
            calcAmount("#useredit-press-input-id");
        });
    }

    // 1만원
    $("#money_1").on("click", function(e) {
        e.preventDefault();
        tr_price(10000);
    });
    // 3만원
    $("#money_2").on("click", function(e) {
        e.preventDefault();
        tr_price(30000);
    });
    // 5만원
    $("#money_3").on("click", function(e) {
        e.preventDefault();
        tr_price(50000);
    });
    // 10만원
    $("#money_4").on("click", function(e) {
        e.preventDefault();
        tr_price(100000);
    });
    // 50만원
    $("#money_5").on("click", function(e) {
        e.preventDefault();
        tr_price(500000);
    });
    // 100만원
    $("#money_6").on("click", function(e) {
        e.preventDefault();
        tr_price(1000000);
    });
    // 전체
    $("#money_9").on("click", function(e) {
        e.preventDefault();
        $("#useredit-transfer-input-id").val($("#useredit-money-input-id").val());
    });
    // 정정
    $("#money_0").on("click", function(e) {
        e.preventDefault();
        tr_price(0);
    });
});



function tr_price(price) {
    if (price == 0) {
        $("#useredit-transfer-input-id").val("0");
    } else {
        let tmp_price = parseInt($("#useredit-transfer-input-id").val().replace(/,/g, ""));

        if (isNaN(tmp_price) == false) {
            price += tmp_price;
        }

        $("#useredit-transfer-input-id").val(price);
        calcAmount("#useredit-transfer-input-id");
    }
}


