$(document).ready(function() {
    addEventListner();
    requestMember();
});

function requestPageInfo() {
    requestMember();
}

function getMemberTr(objMember, bChild = false, bShow=false){

    let strBuf = "<tr id ='tr_" + objMember.mb_fid + "'";
    if(!bShow)
        strBuf += "class='hidden' ";
    if (objMember.mb_color != null)
        strBuf += "bgcolor='" + objMember.mb_color + "' ";
    strBuf += ">";
    strBuf += "<td>";
    if(bChild && objMember.mb_level >= LEVEL_MARKET){
        strBuf += "<button id='exp-btn_"+objMember.mb_fid+"' class='expand' aria-expanded='true' onclick='toggle("+objMember.mb_level+", "+objMember.mb_fid+");' >";
        strBuf += "▼</button>";
    }
    // strBuf += (mOrder++);
    strBuf += "</td> <td>";
    strBuf += getLevelTd(objMember, "popupMemberEdit"); //"/user/member_class/"
    strBuf += "</td> <td>";
    if(objMember.mb_empname !== undefined && objMember.mb_empname.length > 0)
        strBuf += "<a href='"+FURL+"/user/member_class/"+objMember.mb_emp_fid+"' class='link-member'>"+objMember.mb_empname+"</a>";
    strBuf += "</td> <td>";
    strBuf += objMember.mb_fid;
    strBuf += "</td> <td>";
    strBuf += "<a onclick='popupMemberEdit(" + + objMember.mb_fid + ", "+objMember.mb_fid + ")' class='link-member'>"+ objMember.mb_nickname+ "</a>";
    if(objMember.mb_state_delete > 0){
        strBuf += "<br>(오프라인)";    
    }
    if(objMember.mb_state_test > 0){
        strBuf += "<br>(테스트)";    
    }
    strBuf += "</td> <td>";
    strBuf += "Lv " + parseInt(objMember.mb_grade).toLocaleString() ;
    strBuf += "</td> <td>";
    strBuf += "<button name='" + objMember.mb_fid + "' data-fid='" + objMember.mb_fid + "' >충전</button>";
    strBuf += "</td> <td>";
    strBuf += "<button name='" + objMember.mb_fid + "' data-fid='" + objMember.mb_fid + "' >환전</button>";
    strBuf += "</td> <td>";
    strBuf += objMember.mb_phone;
    if(parseInt(objMember.mb_egg) > 0 )
        strBuf += "</td><td> <span id='mm_" + objMember.mb_fid + "' style='color:red'>";
    else strBuf += "</td><td> <span id='mm_" + objMember.mb_fid + "' style='color:black'>";
    strBuf += parseFloat(objMember.mb_money).toLocaleString() + "</span>";
    strBuf += '<button class="refresh_btn" onclick="refreshEgg(' + objMember.mb_fid + ', this);"></button>';
    if (mConfs.emp_level >= LEVEL_ADMIN) {
        strBuf += "</td><td>";
        if(parseInt(objMember.mb_egg) > 0 )
            strBuf += "<button name='" + objMember.mb_fid + "'>알회수</button>";
    }
    strBuf += "</td> <td id='mp_" + objMember.mb_fid + "'>";
    strBuf += parseFloat(objMember.mb_point).toLocaleString();
    strBuf += "</td> <td>";
    if(objMember.bet_sum == null)
        objMember.bet_sum = 0;
    strBuf += Math.floor(objMember.bet_sum).toLocaleString();
    strBuf += "</td> <td>";
    if(objMember.win_sum == null)
        objMember.win_sum = 0;
    strBuf += Math.floor(objMember.win_sum).toLocaleString();
    strBuf += "</td> <td>";
    if(objMember.rw_point == null)
        objMember.rw_point = 0;
    strBuf += Math.floor(objMember.rw_point).toLocaleString();
    strBuf += "</td> <td>";
    if(objMember.chg_point == null)
        objMember.chg_point = 0;
    strBuf += Math.floor(objMember.chg_point).toLocaleString();
    strBuf += "</td> <td>";
    strBuf += objMember.mb_time_join;
    strBuf += "</td> <td>";
    strBuf += objMember.mb_time_last;
    strBuf += "</td> <td>";
    strBuf += "<button name='" + objMember.mb_fid + "'";
    if (mConfs.emp_level < LEVEL_ADMIN) {
        strBuf += " disabled = 'true' ";
    }
    if (objMember.mb_state_active == 1) {
        strBuf += " class='button-active'>승인</button>";
    } else if (objMember.mb_state_active == 2) {
        strBuf += " class='button-req'>신청</button>";
    } else if (objMember.mb_state_active == 3) {
        strBuf += ">대기</button>";
    } else {
        strBuf += ">차단</button>";
    }
    strBuf += "<button onclick='popupMemberEdit("+objMember.mb_fid + ", "+objMember.mb_fid+")'>수정</button>";
    if(mConfs.delete == 1)
        strBuf += "<button name='" + objMember.mb_fid + "'>삭제</button>   ";
    if (!mConfs.slot_deny) {
        strBuf += "</td> <td>";
        strBuf += "<button name='" + objMember.mb_fid + "' class='blank-btn_" + objMember.mb_fid + "' >-</button>   ";
        strBuf += "<button name='" + objMember.mb_fid + "' class='blank-btn_" + objMember.mb_fid + "' >-10</button>   ";
        strBuf += "<input type='text' id='blank_" + objMember.mb_fid + "' value='" + objMember.mb_blank_count + "' disabled>   ";
        strBuf += "<button name='" + objMember.mb_fid + "' class='blank-btn_" + objMember.mb_fid + "' >+</button>   ";
        strBuf += "<button name='" + objMember.mb_fid + "' class='blank-btn_" + objMember.mb_fid + "' >+10</button>   ";
    }
    strBuf += "</td> <td>";
    strBuf += "<button name='" + objMember.mb_fid + "' data-nickname='" + objMember.mb_nickname + "'>강제아웃</button>   ";
    strBuf += "</td></tr>";
    return strBuf;

}

function addEventListner() {

    $(document).on("click", function(e){
        if($("#charge_modal").is(e.target)){
            closeChargeDlg();
        } else if($("#edit_member_modal").is(e.target)){
            closeMemEditDlg();
        }
    });

    $("#userpanel-list-view-but-id").click(function() {
        requestMember();
    });

    $("#userpanel-list-open-but-id").click(function() {
        togleList(true);
    });

    $("#userpanel-list-close-but-id").click(function() {
        togleList(false);
    });
    
    $("#charge_money").on("propertychange change keyup paste input", function() {
        calcAmount("#charge_money");
    });

    $("#charge_modal .close").click(function() {
        closeChargeDlg();
    });

    $("#edit_member_modal .close").click(function() {
        closeMemEditDlg();
    });

}


function requestMember() {

    var search = $("#userpanel-userid-input-id").val();
    var type = $("#userpanel-type-select-id").val();
    var mode = $("#userpanel-mode-select-id").val();
    
    var jsonData = {
        "search": search,
        "type": type,
        "mode":mode,
    };

    jsonData = JSON.stringify(jsonData);
    $(".loading").show();
    $.ajax({
        type: "POST",
        dataType: "json",
        url: FURL + "/userapi/getmemberclass",
        data: { json_: jsonData },
        success: function(jResult) {
            $(".loading").hide();
            // console.log(jResult);
            if (jResult.status == "success") {
                showMember(jResult.data, jResult.confs, true);
            } else if (jResult.status == "fail") {

            }
        },
        error: function(request, status, error) {
            // console.log("code:"+request.status+"\n"+"message:"+request.responseText+"\n"+"error:"+error);
            $(".loading").hide();
        }

    });

}

function addButtonElementListener(buttonElement) {
    buttonElement.addEventListener("click", function() {

        let tHtml = this.innerHTML; 
        if (tHtml.search("삭제") >= 0) {
            var jsonData = { "mb_fid": this.name };
            requestDeleteMember(jsonData);
        } else if (tHtml.search("승인") >= 0) {
            var jsonData = { "mb_fid": this.name, "mb_state_active": 0 };
            requestUpdateMember(jsonData);
        } else if (tHtml.search("차단") >= 0) {
            var jsonData = { "mb_fid": this.name, "mb_state_active": 1 };
            requestUpdateMember(jsonData);
        } else if (tHtml.search("신청") >= 0) {
            var jsonData = { "mb_fid": this.name, "mb_state_active": 3 };
            requestWaitToPermit(this, jsonData);
        } else if (tHtml.search("대기") >= 0) {
            var jsonData = { "mb_fid": this.name, "mb_state_active": 1 };
            requestWaitToPermit(this, jsonData);
        } else if (tHtml.search("충전") >= 0) {
            showMemCharge($(this).data('fid'));
        } else if (tHtml.search("환전") >= 0) {
            showMemDischarge($(this).data('fid'));
        } /*else if (tHtml.search("수정") >= 0) {
            showMemEdit($(this).data('fid'));
        } */ else if (tHtml.search("강제아웃") >= 0) {
            let nickname  = $(this).data('nickname');
            if (!confirm(nickname+" 회원을 강제아웃 시키겠습니까?"))
                return;
            var jsonData = { "mb_fid": this.name };
            requestLogoutMember(jsonData);
        } else if (tHtml.search("알회수") >= 0) {
            if(confirm("게임알을 회수하시겠습니까?"))
                collectEgg(this, this.name);
        } else if (tHtml === "-") {
            countBlank(this.name, -1);
        } else if (tHtml === "-10") {
            countBlank(this.name, -10);
        } else if (tHtml === "+") {
            countBlank(this.name, 1);
        } else if (tHtml === "+10") {
            countBlank(this.name, 10);
        } 
    });
}

function addBtnEvent() {

    var elemTable = document.getElementById("user-member-table-id");
    var elemTableBtns = elemTable.getElementsByTagName("button");
    if (elemTableBtns == null)
        return;

    var i;
    for (i = 0; i < elemTableBtns.length; i++) {
        addButtonElementListener(elemTableBtns[i]);
    }
}


/*=============================================================*/

function closeChargeDlg(){
    $('#charge_modal').slideUp(100);
}

function showChargeDlg(){
    $('#charge_modal').slideDown(200);
}

function showMemCharge(mbFid){

    if(mArrMember == null)
        return;
    
    let member = null;
    for(objMember of mArrMember){
        if(objMember.mb_fid == mbFid){
            member = objMember;
            break;
        }
    }
    if(member == null)
        return;

    $("#charge_modal .c_type_forced").text("강제충전");
    $("#charge_modal .c_type_admin").text("충전해주는 업체");
    $("#charge_modal .c_type_money").text("충전금액");
    $("#charge_user_name").val(member.mb_nickname);
    $("#charge_user_id").val(member.mb_uid);
    $("#charge_user_fid").val(member.mb_fid);
    $("#charge_user_money").val(parseFloat(member.mb_money).toLocaleString());
    $("#charge_money").val('');
    $("#btn-charge-apply").show();
    $("#btn-discharge-apply").hide();

    showChargeDlg();
}

function showMemDischarge(mbFid){
    if(mArrMember == null)
        return;
    
    let member = null;
    for(objMember of mArrMember){
        if(objMember.mb_fid == mbFid){
            member = objMember;
            break;
        }
    }
    if(member == null)
        return;

    $("#charge_modal .c_type_forced").text("강제환전");
    $("#charge_modal .c_type_admin").text("환전해주는 업체");
    $("#charge_modal .c_type_money").text("환전금액");
    $("#charge_user_name").val(member.mb_nickname);
    $("#charge_user_id").val(member.mb_uid);
    $("#charge_user_fid").val(member.mb_fid);
    $("#charge_user_money").val(parseFloat(member.mb_money).toLocaleString());
    $("#charge_money").val('');
    $("#btn-charge-apply").hide();
    $("#btn-discharge-apply").show();

    showChargeDlg();
}

function reqMemCharge(){
    var nAmount = parseInt($("#charge_money").val().replace(/,/g, ""));
    if (isNaN(nAmount) || nAmount == "") {
        nAmount = 0;
    }
    if (nAmount == 0) {
        showAlert("충전금액을 입력 해주세요.", 3);
        return false;
    }

    if (!confirm(nAmount.toLocaleString() + "원을 직충전하시겠습니까?"))
        return;

    var jsonData = {
        'mb_fid': $("#charge_user_fid").val(),
        'amount': nAmount,
        'type':0
    }
    requestTrasnfer(jsonData, false);
    closeChargeDlg();
}

function reqMemDischarge(){
    var nAmount = parseInt($("#charge_money").val().replace(/,/g, ""));
    if (isNaN(nAmount) || nAmount == "") {
        nAmount = 0;
    }
    if (nAmount == 0) {
        showAlert("환전금액을 입력 해주세요.", 3);
        return false;
    }

    if (!confirm(nAmount.toLocaleString() + "원을 직환전하시겠습니까?"))
        return;

    var jsonData = {
        'mb_fid': $("#charge_user_fid").val(),
        'amount': nAmount,
        'type':1
    }
    requestTrasnfer(jsonData, false);
    closeChargeDlg();

}


function showMemEditDlg(){
    $('#edit_member_modal').slideDown(200);

}

function closeMemEditDlg(){
    $('#edit_member_modal').slideUp(100);

}

function showMemCreate(){

    initMemEditDlg();
    $("#btn-mem-apply").text("추가 ");
    $("#edit_member_modal #type").text("회원정보 추가");
    showMemEditDlg();
}

function showMemEdit(mbFid){
    if(mArrMember == null)
        return;
    let member = null;
    for(objMember of mArrMember){
        if(objMember.mb_fid == mbFid){
            member = objMember;
            break;
        }
    }
    if(member == null)
        return;
        
    $("#partner_id").val(member.mb_empname);
    // $("#partner_id").attr("disabled", true);

    $("#offline_user").prop('checked', member.mb_state_delete == 1);
    $("#user_fid").val(member.mb_fid);
    $("#user_uid").val(member.mb_uid);
    $("#user_uid").attr("disabled", true);
    
    $("#user_name").val(member.mb_nickname);
    $("#user_name").attr("disabled", true);
    
    $("#user_password").val(member.mb_pwd);
    $("#user_phone").val(member.mb_phone);
    $("#user_status").val(member.mb_state_active);
    $("#user_level").val(member.mb_grade);
    if(member.mb_color != "")
        $("#user_color").val(member.mb_color);
    $("#memo").val(member.mb_memo);
    $("#pb_ratio").val(member.mb_game_pb_ratio);
    $("#pb2_ratio").val(member.mb_game_pb2_ratio);
    $("#cs_ratio").val(member.mb_game_cs_ratio);
    $("#sl_ratio").val(member.mb_game_sl_ratio);
    $("#hl_ratio").val(member.mb_game_hl_ratio);

    $("#pb_percent").val(member.mb_game_pb_percent);
    $("#pb2_percent").val(member.mb_game_pb2_percent);
    
    $("#bank_name").val(member.mb_bank_name);
    $("#bank_owner").val(member.mb_bank_own);
    // $("#bank_owner").attr("disabled", true);
    $("#bank_number").val(member.mb_bank_num);
    // $("#bank_number").attr("disabled", true);
    $("#bank_password").val(member.mb_bank_pwd);

    $("#btn-mem-apply").text("수정 ");
    $("#edit_member_modal #type").text("회원정보 수정");
    showMemEditDlg();
}

function memSaveApply(){

    var objMember = readConfigToObject();
    reqMemSave(objMember, closeMemEditDlg);
}

function initMemEditDlg(){
    $("#partner_id").val('');
    $("#partner_id").attr("disabled", false);
    $("#offline_user").prop('checked', false);
    $("#user_fid").val('0');
    $("#user_uid").val('');
    $("#user_uid").attr("disabled", false);
    $("#user_name").val('');
    $("#user_name").attr("disabled", false);
    $("#user_password").val('');
    $("#user_phone").val('');
    $("#user_status").val(2);
    $("#user_level").val(1);
    $("#user_color").val("#ffffff");

    $("#pb_ratio").val("0.00");
    $("#pb2_ratio").val("0.00");

    $("#pb_percent").val("100");
    $("#pb2_percent").val("100");

    $("#cs_ratio").val("0.00");
    $("#sl_ratio").val("0.00");

    $("#bank_name").val('');
    $("#bank_owner").val('');
    $("#bank_number").val('');
    $("#bank_password").val('');

}


function readConfigToObject() {

    var objMember = new Object();
    objMember.admin_level = LEVEL_ADMIN;

    objMember.mb_fid = $("#user_fid").val();
    objMember.mb_uid = $("#user_uid").val();
    objMember.mb_grade = $("#user_level").val();
    objMember.mb_nickname = $("#user_name").val();
    objMember.mb_color = $("#user_color").val();
    objMember.mb_state_active = $("#user_status").val();
    objMember.mb_pwd = $("#user_password").val();
    objMember.mb_emp_uid = $("#partner_id").val();
    objMember.mb_phone = $("#user_phone").val();
    objMember.mb_bank_name = $("#bank_name").val();
    objMember.mb_bank_own = $("#bank_owner").val();
    objMember.mb_bank_num = $("#bank_number").val();
    objMember.mb_bank_pwd = $("#bank_password").val();
    

    if($("#pb_ratio").length > 0){
        objMember.mb_game_pb_ratio = $("#pb_ratio").val();
        objMember.mb_game_pb2_ratio = $("#pb2_ratio").val();
    }
    
    if($("#pb_percent").length > 0){
        objMember.mb_game_pb_percent = $("#pb_percent").val();
        objMember.mb_game_pb2_percent = $("#pb2_percent").val();
    }
    
    if($("#cs_ratio").length > 0){
        objMember.mb_game_cs_ratio = $("#cs_ratio").val();
    } 

    if($("#sl_ratio").length > 0){
        objMember.mb_game_sl_ratio = $("#sl_ratio").val();
    } 
    
    if($("#hl_ratio").length > 0){
        objMember.mb_game_hl_ratio = $("#hl_ratio").val();
    } 

    if($("#memo").length > 0){
        objMember.mb_memo = $("#memo").val();
    } 

    if ($("#offline_user").length > 0){
        objMember.mb_state_delete = $("#offline_user").prop('checked') ? 1 : 0;
    } else objMember.mb_state_delete = 0;

    return objMember;

}

function sumOfMember(){
    let sumMoney = 0, sumPoint = 0, sumBet = 0, sumWin = 0, sumRwPt = 0, sumChgPt = 0;
    for (let objMember of mArrMember) {

        if(objMember.mb_money_all !== undefined){
            $("#mm_"+objMember.mb_fid).text(parseFloat(objMember.mb_money_all).toLocaleString());
        }
        else $("#mm_"+objMember.mb_fid).text(parseFloat(objMember.mb_money).toLocaleString());
        $("#mp_"+objMember.mb_fid).text(parseFloat(objMember.mb_point).toLocaleString());

        sumMoney += parseFloat(objMember.mb_money);
        sumPoint += parseFloat(objMember.mb_point);
        sumBet += parseFloat(objMember.bet_sum);
        sumWin += parseFloat(objMember.win_sum);
        sumRwPt += parseFloat(objMember.rw_point);
        sumChgPt += parseFloat(objMember.chg_point);
    }

    $("#sumOfMoney").text(sumMoney.toLocaleString());
    $("#sumOfPoint").text(sumPoint.toLocaleString());
    $("#sumOfBet").text(sumBet.toLocaleString());
    $("#sumOfWin").text(sumWin.toLocaleString());
    $("#sumOfRwPt").text(sumRwPt.toLocaleString());
    $("#sumOfChgpt").text(sumChgPt.toLocaleString());
}