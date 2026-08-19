$(document).ready(function() {
    addEventListner();

    requestMember();
    setTimeout(function() {
        memberLoop();
    }, 60000);
});

function requestPageInfo() {
    requestMember();
}

function memberLoop() {
    requestMember(false);
    // 60초뒤에 다시 실행
    setTimeout(function() {
        memberLoop();
    }, 60000);
}

function getMemberTr(objMember, bChild = false, bShow=false){
    let strBuf = "<tr id ='tr_" + objMember.mb_fid + "' ";
    if(!bShow)
        strBuf += "class='hidden' ";
    if (mConfs.emp_level >= LEVEL_ADMIN && objMember.mb_color != null)
        strBuf += "bgcolor='" + objMember.mb_color + "' ";
    strBuf += ">";
    strBuf += "<td>";
    if(bChild && objMember.mb_level >= LEVEL_MARKET){
        strBuf += "<button id='exp-btn_"+objMember.mb_fid+"' class='expand' aria-expanded='true' onclick='toggle("+objMember.mb_level+", "+objMember.mb_fid+");' >";
        strBuf += "▼</button>";
    }
    // strBuf += (mOrder ++);
    strBuf += "</td> <td>";
    strBuf += getLevelTd(objMember, "popupMemberEdit"); //"/user/member_list/"
    strBuf += "</td> <td>";
    if(objMember.mb_empname !== undefined && objMember.mb_empname.length > 0)
        strBuf += "<a href='"+FURL+"/user/member_list/"+objMember.mb_emp_fid+"' class='link-member'>"+objMember.mb_empname+"</a>";
    strBuf += "</td> <td>";
    strBuf += objMember.mb_fid;
    strBuf += "</td> <td>";
    strBuf += "<a onclick='popupMemberEdit(" + objMember.mb_fid + ", "+objMember.mb_fid+ ")' class='link-member'>"+ objMember.mb_nickname+ "</a>";
    if(objMember.mb_state_delete > 0){
        strBuf += "<br>(오프라인)";    
    }
    if(objMember.mb_state_test > 0){
        strBuf += "<br>(테스트)";    
    }
    strBuf += "</td> <td>";
    strBuf += "Lv " + parseInt(objMember.mb_grade).toLocaleString();
    strBuf += "</td><td> <span id='mm_" + objMember.mb_fid + "'>";
    strBuf += parseFloat(objMember.mb_money_all).toLocaleString() + "</span>";
    strBuf += '<button class="refresh_btn" onclick="refreshEgg(' + objMember.mb_fid + ', this);"></button>';
    strBuf += "</td> <td id='mp_" + objMember.mb_fid + "'>";
    strBuf += parseFloat(objMember.mb_point).toLocaleString();
    strBuf += "</td> <td>";
    if (!mConfs.slot_deny)
        strBuf += "<span style='word-break: keep-all;'>슬롯:" + objMember.mb_game_sl_ratio + "</span><br>" ;
    if (!mConfs.evol_deny || !mConfs.cas_deny)
        strBuf += "<span style='word-break: keep-all;'>카지노:" + objMember.mb_game_cs_ratio + "</span><br>" ;
    if (!mConfs.hold_deny)
        strBuf += "<span style='word-break: keep-all;'>홀덤:" + objMember.mb_game_hl_ratio + "</span><br>" ;
    if (!mConfs.pbg_deny || !mConfs.bpg_deny || !mConfs.eos5_deny || !mConfs.coin5_deny){
        strBuf += "<span style='word-break: keep-all;'>미니단폴:" + objMember.mb_game_pb_ratio + "</span><br>" ;
        strBuf += "<span style='word-break: keep-all;'>미니조합:" + objMember.mb_game_pb2_ratio + "</span><br>" ;
    }
    strBuf += "</td> <td>";
    if (mConfs.emp_level >= LEVEL_ADMIN) {
        if (!mConfs.slot_deny) {
            strBuf += "<button name='" + objMember.mb_fid + "' class='blank-btn_" + objMember.mb_fid + "' >-</button>   ";
            strBuf += "<input type='text' id='blank_" + objMember.mb_fid + "' value='" + objMember.mb_blank_count + "' disabled>   ";
            strBuf += "<button name='" + objMember.mb_fid + "' class='blank-btn_" + objMember.mb_fid + "' >+</button>   ";
            strBuf += "<br><button name='" + objMember.mb_fid + "' class='blank-btn_" + objMember.mb_fid + "' >-10</button>   ";
            strBuf += "<button name='" + objMember.mb_fid + "' class='blank-btn_" + objMember.mb_fid + "' >+10</button>   ";
            strBuf += "</td> <td>";
        }
        strBuf += objMember.mb_ip_last.substring(0, 20);
        if (objMember.block_state == 1) {
            strBuf += "<br><br><button name='" + objMember.mb_ip_last + "' >차단해제</button>";
        } else
            strBuf += "<br><br><button name='" + objMember.mb_ip_last + "' >IP차단</button>";
        strBuf += "</td> <td>";
        
    }
    if (mConfs.emp_level >= LEVEL_ADMIN) {
        strBuf += "<button name='" + objMember.mb_fid + "'";
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
    
        var strEncodeURI = FURL+"/board/message_edit/0/" + objMember.mb_fid;
        strBuf += "<a href='" + strEncodeURI + "' >쪽지</a>";
        if(mConfs.delete)
            strBuf += "<button name='" + objMember.mb_fid + "'>삭제</button>   ";

        strBuf += "</td> <td>";
        if(!mConfs.pbg_deny){
            if (objMember.mb_game_pb == 1) {
                strBuf += "<button name='" + objMember.mb_fid + "'  class='button-active'>PBG</button>";
            } else {
                strBuf += "<button name='" + objMember.mb_fid + "' >PBG</button>";
            }
        }
        if(!mConfs.dhp_deny){
            if (objMember.mb_game_ps == 1) {
                strBuf += "<button name='" + objMember.mb_fid + "'  class='button-active'>동행</button>";
            } else {
                strBuf += "<button name='" + objMember.mb_fid + "' >동행</button>";
            }
        }
        if(!mConfs.spk_deny){
            if (objMember.mb_game_ks == 1) {
                strBuf += "<button name='" + objMember.mb_fid + "'  class='button-active'>키노</button>";
            } else {
                strBuf += "<button name='" + objMember.mb_fid + "' >키노</button>";
            }
        }
        if(!mConfs.bpg_deny){
            if (objMember.mb_game_bb == 1) {
                strBuf += "<button name='" + objMember.mb_fid + "'  class='button-active'>보글볼</button>";
            } else {
                strBuf += "<button name='" + objMember.mb_fid + "' >보글볼</button>";
            }
            if (objMember.mb_game_bs == 1) {
                strBuf += "<button name='" + objMember.mb_fid + "'  class='button-active'>보사달</button>";
            } else {
                strBuf += "<button name='" + objMember.mb_fid + "' >보사달</button>";
            }
        }
        if(!mConfs.eos5_deny || !mConfs.eos3_deny){
            if (objMember.mb_game_eo == 1) {
                strBuf += "<button name='" + objMember.mb_fid + "'  class='button-active'>EOS</button>";
            } else {
                strBuf += "<button name='" + objMember.mb_fid + "' >EOS</button>";
            }
        }
        if(!mConfs.coin5_deny || !mConfs.coin3_deny){
            if (objMember.mb_game_co == 1) {
                strBuf += "<button name='" + objMember.mb_fid + "'  class='button-active'>코인</button>";
            } else {
                strBuf += "<button name='" + objMember.mb_fid + "' >코인</button>";
            }
        }
        if(!mConfs.evol_deny || !mConfs.cas_deny){
            if (objMember.mb_game_cs == 1) {
                strBuf += "<button name='" + objMember.mb_fid + "'  class='button-active'>카지노</button>";
            } else {
                strBuf += "<button name='" + objMember.mb_fid + "' >카지노</button>";
            }
        }
        if(!mConfs.slot_deny){
            if (objMember.mb_game_sl == 1) {
                strBuf += "<button name='" + objMember.mb_fid + "'  class='button-active'>슬롯</button>";
            } else {
                strBuf += "<button name='" + objMember.mb_fid + "' >슬롯</button>";
            }
        }
        if(!mConfs.hold_deny){
            if (objMember.mb_game_hl == 1) {
                strBuf += "<button name='" + objMember.mb_fid + "'  class='button-active'>홀덤</button>";
            } else {
                strBuf += "<button name='" + objMember.mb_fid + "' >홀덤</button>";
            }
        }
    } else {
        strBuf += "<button onclick='showMemGive("+objMember.mb_fid+")'>이동</button>";
        strBuf += "<button onclick='showMemWithdraw("+objMember.mb_fid+")'>환수</button>";
    }
    strBuf += "</td></tr>";
    return strBuf;
}


function addEventListner() {
    $("#userpanel-list-view-but-id").click(function() {
        requestMember();
    });
    
    $("#userpanel-list-open-but-id").click(function() {
        togleList(true);
    });

    $("#userpanel-list-close-but-id").click(function() {
        togleList(false);
    });

    $(document).on("click", function(e){
        if($("#charge_modal").is(e.target)){
            closeChargeDlg();
        }
    });

    $("#charge_modal .close").click(function() {
        closeChargeDlg();
    });

    $("#charge_money").on("propertychange change keyup paste input", function() {
        calcAmount("#charge_money");
    });
     
}

function requestMember(bRefresh=true) {

    var search = $("#userpanel-userid-input-id").val();
    var type = $("#userpanel-type-select-id").val();
    var mode = $("#userpanel-mode-select-id").val();
    var jsonData = {
        "search": search,
        "type":type,
        "mode":mode,
        "auto":!bRefresh,
    };

    jsonData = JSON.stringify(jsonData);
    $(".loading").show();
    $.ajax({
        type: "POST",
        dataType: "json",
        url: FURL + "/userapi/getmembertree",
        data: { json_: jsonData },
        success: function(jResult) {
            $(".loading").hide();
            // console.log(jResult);
            if (jResult.status == "success") {
                showMember(jResult.data, jResult.confs, bRefresh);
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
        let jsonData = null;

        if (this.innerHTML.search("삭제") >= 0) {
            jsonData = { "mb_fid": this.name };
            requestDeleteMember(jsonData);
        } else if (this.innerHTML.search("IP차단") >= 0) {
            jsonData = { "block_ip": this.name, "block_state": 1 };
            requestAddBlock(jsonData);
        } else if (this.innerHTML.search("차단해제") >= 0) {
            jsonData = { "block_ip": this.name, "block_state": 0 };
            requestAddBlock(jsonData);
        } else if (this.innerHTML.search("승인") >= 0) {
            jsonData = { "mb_fid": this.name, "mb_state_active": 0 };
            requestUpdateMember(jsonData);
        } else if (this.innerHTML.search("차단") >= 0) {
            jsonData = { "mb_fid": this.name, "mb_state_active": 1 };
            requestUpdateMember(jsonData);
        } else if (this.innerHTML.search("신청") >= 0) {
            jsonData = { "mb_fid": this.name, "mb_state_active": 3 };
            requestWaitToPermit(this, jsonData);
        } else if (this.innerHTML.search("대기") >= 0) {
            jsonData = { "mb_fid": this.name, "mb_state_active": 1 };
            requestWaitToPermit(this, jsonData);
        } else if (this.innerHTML == "PBG") {
            if (this.className.search("button-active") >= 0) {
                jsonData = { "mb_fid": this.name, "mb_game_pb": 0 };
            } else {
                jsonData = { "mb_fid": this.name, "mb_game_pb": 1 };
            }
            requestUpdateMember(jsonData);
        } else if (this.innerHTML.search("동행") >= 0) {
            if (this.className.search("button-active") >= 0) {
                jsonData = { "mb_fid": this.name, "mb_game_ps": 0 };
            } else {
                jsonData = { "mb_fid": this.name, "mb_game_ps": 1 };
            }
            requestUpdateMember(jsonData);
        } else if (this.innerHTML.search("키노") >= 0) {
            if (this.className.search("button-active") >= 0) {
                jsonData = { "mb_fid": this.name, "mb_game_ks": 0 };
            } else {
                jsonData = { "mb_fid": this.name, "mb_game_ks": 1 };
            }
            requestUpdateMember(jsonData);
        } else if (this.innerHTML.search("보글볼") >= 0) {
            if (this.className.search("button-active") >= 0) {
                jsonData = { "mb_fid": this.name, "mb_game_bb": 0 };
            } else {
                jsonData = { "mb_fid": this.name, "mb_game_bb": 1 };
            }
            requestUpdateMember(jsonData);
        } else if (this.innerHTML.search("보사달") >= 0) {
            if (this.className.search("button-active") >= 0) {
                jsonData = { "mb_fid": this.name, "mb_game_bs": 0 };
            } else {
                jsonData = { "mb_fid": this.name, "mb_game_bs": 1 };
            }
            requestUpdateMember(jsonData);
        } else if (this.innerHTML.search("코인") >= 0) {
            if (this.className.search("button-active") >= 0) {
                jsonData = { "mb_fid": this.name, "mb_game_co": 0 };
            } else {
                jsonData = { "mb_fid": this.name, "mb_game_co": 1 };
            }
            requestUpdateMember(jsonData);
        } else if (this.innerHTML.search("카지노") >= 0) {
            if (this.className.search("button-active") >= 0) {
                jsonData = { "mb_fid": this.name, "mb_game_cs": 0 };
            } else {
                jsonData = { "mb_fid": this.name, "mb_game_cs": 1 };
            }
            requestUpdateMember(jsonData);
        } else if (this.innerHTML.search("슬롯") >= 0) {
            if (this.className.search("button-active") >= 0) {
                jsonData = { "mb_fid": this.name, "mb_game_sl": 0 };
            } else {
                jsonData = { "mb_fid": this.name, "mb_game_sl": 1 };
            }
            requestUpdateMember(jsonData);
        } else if (this.innerHTML === "EOS") {
            if (this.className.search("button-active") >= 0) {
                jsonData = { "mb_fid": this.name, "mb_game_eo": 0 };
            } else {
                jsonData = { "mb_fid": this.name, "mb_game_eo": 1 };
            }
            requestUpdateMember(jsonData);
        } else if (this.innerHTML === "홀덤") {
            if (this.className.search("button-active") >= 0) {
                jsonData = { "mb_fid": this.name, "mb_game_hl": 0 };
            } else {
                jsonData = { "mb_fid": this.name, "mb_game_hl": 1 };
            }
            requestUpdateMember(jsonData);
        } else if (this.innerHTML === "-") {
            countBlank(this.name, -1);
        } else if (this.innerHTML === "-10") {
            countBlank(this.name, -10);
        } else if (this.innerHTML === "+") {
            countBlank(this.name, 1);
        } else if (this.innerHTML === "+10") {
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


function showMemGive(mbFid){

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

    $("#charge_modal .c_type_forced").text("머니이동");
    $("#charge_modal .c_type_money").text("이동금액");
    $("#charge_user_name").val(member.mb_nickname);
    $("#charge_user_id").val(member.mb_uid);
    $("#charge_user_fid").val(member.mb_fid);
    $("#charge_user_money").val(parseFloat(member.mb_money_all).toLocaleString());
    $("#charge_money").val('');
    $("#btn-charge-apply").show();
    $("#btn-discharge-apply").hide();

    showChargeDlg();
}


function showMemWithdraw(mbFid){
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

    $("#charge_modal .c_type_forced").text("머니환수");
    $("#charge_modal .c_type_money").text("환수금액");
    $("#charge_user_name").val(member.mb_nickname);
    $("#charge_user_id").val(member.mb_uid);
    $("#charge_user_fid").val(member.mb_fid);
    $("#charge_user_money").val(parseFloat(member.mb_money_all).toLocaleString());
    $("#charge_money").val('');
    $("#btn-charge-apply").hide();
    $("#btn-discharge-apply").show();

    showChargeDlg();
}

function closeChargeDlg(){
    $('#charge_modal').slideUp(100);
}

function showChargeDlg(){
    $('#charge_modal').slideDown(200);
}

function tr_price(price) {
    if (price == 0) {
        $("#charge_money").val("0");
    } else {
        tmp_price = parseInt($("#charge_money").val().replace(/,/g, ""));

        if (isNaN(tmp_price) == false) {
            price += tmp_price;
        }

        $("#charge_money").val(price);
        calcAmount("#charge_money");
    }
}

function reqMemGive(){
    var nAmount = parseInt($("#charge_money").val().replace(/,/g, ""));
    if (isNaN(nAmount) || nAmount == "") {
        nAmount = 0;
    }
    if (nAmount == 0) {
        showAlert("이동금액을 입력 해주세요.", 3);
        return false;
    }

    if (!confirm(nAmount.toLocaleString() + "원을 회원에게 이동하시겠습니까?"))
        return;

    var jsonData = {
        'mb_fid': $("#charge_user_fid").val(),
        'amount': nAmount,
        'type':2
    }
    requestTrasnfer(jsonData, false);
    closeChargeDlg();
}

function reqMemWithdraw(){
    var nAmount = parseInt($("#charge_money").val().replace(/,/g, ""));
    if (isNaN(nAmount) || nAmount == "") {
        nAmount = 0;
    }
    if (nAmount == 0) {
        showAlert("환수금액을 입력 해주세요.", 3);
        return false;
    }

    if (!confirm(nAmount.toLocaleString() + "원을 회원에게서 환수하시겠습니까?"))
        return;

    var jsonData = {
        'mb_fid': $("#charge_user_fid").val(),
        'amount': nAmount,
        'type':3
    }
    requestTrasnfer(jsonData, false);
    closeChargeDlg();

}